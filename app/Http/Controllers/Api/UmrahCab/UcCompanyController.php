<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcCompany;
use Illuminate\Http\Request;

class UcCompanyController extends Controller
{
    public function index()
    {
        return response()->json(UcCompany::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'address' => 'nullable|string',
            'invoice' => 'required|boolean',
            'vouchers' => 'nullable|boolean',
            'reminders' => 'nullable|boolean',
            'statement_status' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $company = UcCompany::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Company registered successfully!',
            'data' => $company
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $company = UcCompany::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'address' => 'nullable|string',
            'invoice' => 'required|boolean',
            'vouchers' => 'nullable|boolean',
            'reminders' => 'nullable|boolean',
            'statement_status' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $company->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Company updated successfully!',
            'data' => $company
        ]);
    }
}
