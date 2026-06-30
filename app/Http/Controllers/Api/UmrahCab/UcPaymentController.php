<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcPayment;
use Illuminate\Http\Request;

class UcPaymentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $search = $request->query('search');
        $company = $request->query('company');
        $method = $request->query('method');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = UcPayment::orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('custom_id', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhere('transaction_ref', 'like', "%{$search}%");
            });
        }

        if ($company) {
            $query->where('company', $company);
        }

        if ($method && $method !== 'all') {
            $query->where('method', $method);
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string',
            'method' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'transaction_ref' => 'nullable|string',
            'proof_details' => 'nullable|string',
            'proof_file' => 'nullable',
        ]);

        $validated['custom_id'] = 'PAY-' . rand(9000, 9999);
        $validated['date'] = date('Y-m-d');
        $validated['status'] = 'Pending';

        if ($request->hasFile('proof_file')) {
            $file = $request->file('proof_file');
            $filename = 'proof_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Upload to S3 if configured, otherwise fall back to local disk
            if (config('filesystems.disks.s3.key') && config('filesystems.disks.s3.secret') && config('filesystems.disks.s3.bucket')) {
                $path = \Illuminate\Support\Facades\Storage::disk('s3')->putFileAs('proofs', $file, $filename);
                $proofPath = \Illuminate\Support\Facades\Storage::disk('s3')->url($path);
            } else {
                $file->move(public_path('uploads/proofs'), $filename);
                $proofPath = '/uploads/proofs/' . $filename;
            }
            
            $validated['proof_file'] = $proofPath;
        }

        $payment = UcPayment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'General payment logged successfully!',
            'data' => $payment
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'approved_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $payment = UcPayment::findOrFail($id);
        $oldStatus = $payment->status;
        $newStatus = $validated['status'];
        $approvedAmount = isset($validated['approved_amount']) && !is_null($validated['approved_amount']) ? floatval($validated['approved_amount']) : $payment->amount;

        if ($request->has('notes') && !empty($request->notes)) {
            $payment->proof_details = trim(($payment->proof_details ?? '') . "\n[Approval Note: " . $request->notes . "]");
        }

        // If status is approved and approved_amount is less than original amount, we record a due amount
        if (in_array(strtolower($newStatus), ['approved', 'success', 'verified']) && $approvedAmount < $payment->amount && $approvedAmount > 0) {
            $dueAmount = $payment->amount - $approvedAmount;
            
            // Note: Since agent gets the full requested balance (e.g. 150), we DO NOT change the current payment's amount.
            // We keep $payment->amount as requested so it credits the full amount to the ledger.
            $payment->proof_details = trim(($payment->proof_details ?? '') . "\n[Partial Payment: Received " . $approvedAmount . " of " . $payment->amount . ", Remaining Due: " . $dueAmount . "]");

            // Create a new pending payment for the remaining due.
            // Since they already got the full credit, this due payment when approved should NOT credit the ledger again.
            UcPayment::create([
                'custom_id' => 'PAY-' . rand(9000, 9999),
                'company' => $payment->company,
                'date' => date('Y-m-d'),
                'method' => $payment->method,
                'amount' => $dueAmount,
                'currency' => $payment->currency,
                'status' => 'Pending',
                'transaction_ref' => $payment->custom_id . ' (Due Payment)',
                'proof_details' => 'Auto-generated outstanding due payment for ' . $payment->custom_id . '. (No additional ledger credit on approval)',
                'proof_file' => $payment->proof_file
            ]);
        }

        $payment->status = $newStatus;
        $payment->save();

        // If transitioning from non-approved/non-verified to approved/verified/success
        $isNewCleared = in_array(strtolower($newStatus), ['approved', 'success', 'verified']);
        $isOldCleared = in_array(strtolower($oldStatus), ['approved', 'success', 'verified']);

        if ($isNewCleared && !$isOldCleared) {
            // Check if this is an auto-generated due payment (so it doesn't double-credit the ledger)
            $isDuePayment = (strpos(strtolower($payment->transaction_ref ?? ''), 'due') !== false) || 
                             (strpos(strtolower($payment->proof_details ?? ''), 'no additional ledger credit') !== false);
            
            if (!$isDuePayment) {
                // Fetch last balance to calculate next balance
                $lastLedger = \App\Models\UmrahCab\UcLedger::where('company', $payment->company)->orderBy('id', 'desc')->first();
                $lastBalance = $lastLedger ? $lastLedger->balance : 0;
                $newBalance = $lastBalance + $payment->amount;

                \App\Models\UmrahCab\UcLedger::create([
                    'company' => $payment->company,
                    'custom_id' => 'LED-' . rand(1000, 9999),
                    'date' => date('Y-m-d'),
                    'description' => 'Payment Cleared: ' . ($payment->custom_id ?? 'PAY-'.$payment->id),
                    'debit' => 0,
                    'credit' => $payment->amount,
                    'balance' => $newBalance
                ]);
            }
        } elseif (!$isNewCleared && $isOldCleared) {
            // Revoke credit: Debit the amount from the ledger (Admin Rejected/Cancelled after Approval)
            $isDuePayment = (strpos(strtolower($payment->transaction_ref ?? ''), 'due') !== false) || 
                             (strpos(strtolower($payment->proof_details ?? ''), 'no additional ledger credit') !== false);

            if (!$isDuePayment) {
                $lastLedger = \App\Models\UmrahCab\UcLedger::where('company', $payment->company)->orderBy('id', 'desc')->first();
                $lastBalance = $lastLedger ? $lastLedger->balance : 0;
                $newBalance = $lastBalance - $payment->amount;

                \App\Models\UmrahCab\UcLedger::create([
                    'company' => $payment->company,
                    'custom_id' => 'LED-' . rand(1000, 9999),
                    'date' => date('Y-m-d'),
                    'description' => 'Payment Rejected/Revoked: ' . ($payment->custom_id ?? 'PAY-'.$payment->id),
                    'debit' => $payment->amount,
                    'credit' => 0,
                    'balance' => $newBalance
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully!',
            'data' => $payment
        ]);
    }
}
