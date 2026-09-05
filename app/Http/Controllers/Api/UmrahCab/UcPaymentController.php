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
        $status = $request->query('status');

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
            $trimmedComp = trim($company);
            $query->where(function($q) use ($company, $trimmedComp) {
                $q->where('company', $company)
                  ->orWhere('company', 'like', "%{$trimmedComp}%");
            });
        }

        if ($method && $method !== 'all') {
            $query->where('method', $method);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
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

        $methodLower = strtolower($payment->method ?? '');
        $isLoanMethod = (strpos($methodLower, 'loan') !== false || strpos($methodLower, 'credit') !== false);
        $isDuePayment = (strtolower($payment->method ?? '') === 'loan due') || 
                         (strpos(strtolower($payment->method ?? ''), 'due') !== false) || 
                         (strpos(strtolower($payment->transaction_ref ?? ''), 'due') !== false) || 
                         (strpos(strtolower($payment->proof_details ?? ''), 'no additional ledger credit') !== false);

        $isNewCleared = in_array(strtolower($newStatus), ['approved', 'success', 'verified']);
        $isOldCleared = in_array(strtolower($oldStatus), ['approved', 'success', 'verified']);

        // Handle Loan approval & Due Payment adjustments
        if ($isNewCleared && !$isOldCleared) {
            if ($isLoanMethod && !$isDuePayment) {
                // Admin is approving a LOAN / CREDIT request:
                $payment->amount = $approvedAmount;
                $payment->proof_details = trim(($payment->proof_details ?? '') . "\n[Loan Approved: SAR " . number_format($approvedAmount, 2) . "]");

                // Create auto-generated Due Payment record so it displays under Payable Amount (Admin)
                UcPayment::create([
                    'custom_id' => 'PAY-' . rand(9000, 9999),
                    'company' => $payment->company,
                    'date' => date('Y-m-d'),
                    'method' => 'Loan Due',
                    'amount' => $approvedAmount,
                    'currency' => $payment->currency,
                    'status' => 'Pending',
                    'transaction_ref' => ($payment->custom_id ?? ('PAY-'.$payment->id)) . ' (Loan Due)',
                    'proof_details' => 'Auto-generated loan due payable to Admin for Loan Request ' . ($payment->custom_id ?? ('PAY-'.$payment->id)),
                    'proof_file' => $payment->proof_file
                ]);
            } elseif ($isDuePayment) {
                // Admin is approving/adjusting a DUE PAYMENT repayment:
                if ($approvedAmount < $payment->amount && $approvedAmount > 0) {
                    $remainingDue = $payment->amount - $approvedAmount;
                    $payment->proof_details = trim(($payment->proof_details ?? '') . "\n[Partial Loan Due Repayment Received: SAR " . number_format($approvedAmount, 2) . " of SAR " . number_format($payment->amount, 2) . ", Remaining Due: SAR " . number_format($remainingDue, 2) . "]");
                    $payment->amount = $approvedAmount;

                    // Create pending record for remaining due amount
                    UcPayment::create([
                        'custom_id' => 'PAY-' . rand(9000, 9999),
                        'company' => $payment->company,
                        'date' => date('Y-m-d'),
                        'method' => $payment->method,
                        'amount' => $remainingDue,
                        'currency' => $payment->currency,
                        'status' => 'Pending',
                        'transaction_ref' => ($payment->transaction_ref ?? 'Due Payment'),
                        'proof_details' => 'Auto-generated remaining due payable to Admin for Loan ' . ($payment->transaction_ref ?? ''),
                        'proof_file' => $payment->proof_file
                    ]);
                } else {
                    $payment->proof_details = trim(($payment->proof_details ?? '') . "\n[Loan Due Repayment Received (Full): SAR " . number_format($payment->amount, 2) . " for Loan " . ($payment->transaction_ref ?? '') . "]");
                }

                // Also update the original loan entry if referenced
                if ($payment->transaction_ref) {
                    preg_match('/PAY-\d+/', $payment->transaction_ref, $matches);
                    if (!empty($matches[0])) {
                        $loanRef = $matches[0];
                        $origLoan = UcPayment::where('custom_id', $loanRef)->first();
                        if ($origLoan) {
                            $origLoan->proof_details = trim(($origLoan->proof_details ?? '') . "\n[Loan Repayment Received: SAR " . number_format($payment->amount, 2) . " (Ref: " . ($payment->custom_id ?? ('PAY-'.$payment->id)) . ")]");
                            $origLoan->save();
                        }
                    }
                }
            } else {
                // Normal Cash / Bank Transfer / Credit Card deposit approval:
                if ($approvedAmount > 0 && $approvedAmount != $payment->amount) {
                    $payment->proof_details = trim(($payment->proof_details ?? '') . "\n[Approved Received Amount: SAR " . number_format($approvedAmount, 2) . " of requested SAR " . number_format($payment->amount, 2) . "]");
                    $payment->amount = $approvedAmount;
                }
            }
        }

        $payment->status = $newStatus;
        $payment->save();

        // Handle Ledger Credit / Revocation
        if ($isNewCleared && !$isOldCleared && !$isDuePayment) {
            // Prevent duplicate ledger entries for the same payment
            $paymentRef = $payment->custom_id ?? ('PAY-' . $payment->id);
            $existingLedger = \App\Models\UmrahCab\UcLedger::where('company', $payment->company)
                ->where('description', 'like', '%' . $paymentRef . '%')
                ->first();

            if (!$existingLedger) {
                $lastLedger = \App\Models\UmrahCab\UcLedger::where('company', $payment->company)->orderBy('id', 'desc')->first();
                $lastBalance = $lastLedger ? $lastLedger->balance : 0;
                $newBalance = $lastBalance + $payment->amount;

                $description = $isLoanMethod 
                    ? 'Loan Credit Approved (Ref: ' . $paymentRef . ', Amount: SAR ' . number_format($payment->amount, 2) . ')'
                    : 'Payment Cleared (Ref: ' . $paymentRef . ', Amount: SAR ' . number_format($payment->amount, 2) . ')';

                \App\Models\UmrahCab\UcLedger::create([
                    'company' => $payment->company,
                    'custom_id' => 'LED-' . rand(1000, 9999),
                    'date' => date('Y-m-d'),
                    'description' => $description,
                    'debit' => 0,
                    'credit' => $payment->amount,
                    'balance' => $newBalance
                ]);
            }
        } elseif ($isOldCleared && !$isNewCleared) {
            // Revoke credit: Debit the amount from the ledger (Admin Rejected/Cancelled after Approval)
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

    public function destroy($id)
    {
        $payment = UcPayment::findOrFail($id);
        
        // If the payment was approved and credited the ledger, revoke the credited amount
        $isCleared = in_array(strtolower($payment->status), ['approved', 'success', 'verified']);
        $isDuePayment = (strtolower($payment->method ?? '') === 'loan due') ||
                         (strpos(strtolower($payment->method ?? ''), 'due') !== false) || 
                         (strpos(strtolower($payment->transaction_ref ?? ''), 'due') !== false) || 
                         (strpos(strtolower($payment->proof_details ?? ''), 'no additional ledger credit') !== false);

        if ($isCleared && !$isDuePayment) {
            $lastLedger = \App\Models\UmrahCab\UcLedger::where('company', $payment->company)->orderBy('id', 'desc')->first();
            $lastBalance = $lastLedger ? $lastLedger->balance : 0;
            $newBalance = $lastBalance - $payment->amount;

            \App\Models\UmrahCab\UcLedger::create([
                'company' => $payment->company,
                'custom_id' => 'LED-' . rand(1000, 9999),
                'date' => date('Y-m-d'),
                'description' => 'Payment Deleted/Revoked: ' . ($payment->custom_id ?? 'PAY-'.$payment->id),
                'debit' => $payment->amount,
                'credit' => 0,
                'balance' => $newBalance
            ]);
        }

        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment record deleted successfully!'
        ]);
    }
}
