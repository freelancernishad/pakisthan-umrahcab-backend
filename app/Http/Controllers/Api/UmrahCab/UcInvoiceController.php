<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcInvoice;
use Illuminate\Http\Request;

class UcInvoiceController extends Controller
{
    public function index()
    {
        return response()->json(UcInvoice::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer' => 'required|string',
            'amount' => 'required|numeric',
            'balance' => 'required|numeric',
        ]);

        $validated['invoice_code'] = 'INV-2026-' . sprintf('%03d', UcInvoice::count() + 3);
        $validated['date'] = date('Y-m-d');
        $validated['status'] = $validated['balance'] <= 0 ? 'Paid' : 'Unpaid';

        $invoice = UcInvoice::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Invoice created successfully!',
            'data' => $invoice
        ], 201);
    }
}
