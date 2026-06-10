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
            $file->move(public_path('uploads/proofs'), $filename);
            $validated['proof_file'] = '/uploads/proofs/' . $filename;
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
        ]);

        $payment = UcPayment::findOrFail($id);
        $oldStatus = $payment->status;
        $newStatus = $validated['status'];

        $payment->status = $newStatus;
        $payment->save();

        // If transitioning from non-approved/non-verified to approved/verified/success
        $isNewCleared = in_array(strtolower($newStatus), ['approved', 'success', 'verified']);
        $isOldCleared = in_array(strtolower($oldStatus), ['approved', 'success', 'verified']);

        if ($isNewCleared && !$isOldCleared) {
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

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully!',
            'data' => $payment
        ]);
    }
}
