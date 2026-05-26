<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcLedger;
use Illuminate\Http\Request;

class UcLedgerController extends Controller
{
    public function index()
    {
        return response()->json(UcLedger::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string',
            'description' => 'required|string',
            'debit' => 'nullable|numeric',
            'credit' => 'nullable|numeric',
        ]);

        $debit = $validated['debit'] ?? 0;
        $credit = $validated['credit'] ?? 0;

        // Fetch last balance to calculate next balance
        $lastLedger = UcLedger::where('company', $validated['company'])->orderBy('id', 'desc')->first();
        $lastBalance = $lastLedger ? $lastLedger->balance : 0;
        $newBalance = $lastBalance + $credit - $debit;

        $validated['custom_id'] = 'LED-' . rand(1000, 9999);
        $validated['date'] = date('Y-m-d');
        $validated['balance'] = $newBalance;

        $ledger = UcLedger::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ledger adjustment created successfully!',
            'data' => $ledger
        ], 201);
    }
}
