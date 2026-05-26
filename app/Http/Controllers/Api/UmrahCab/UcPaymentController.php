<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcPayment;
use Illuminate\Http\Request;

class UcPaymentController extends Controller
{
    public function index()
    {
        return response()->json(UcPayment::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string',
            'method' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'required|string',
        ]);

        $validated['custom_id'] = 'PAY-' . rand(9000, 9999);
        $validated['date'] = date('Y-m-d');
        $validated['status'] = 'Pending';

        $payment = UcPayment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'General payment logged successfully!',
            'data' => $payment
        ], 201);
    }
}
