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
            'agent_username' => 'nullable|string|unique:uc_companies,agent_username',
            'agent_password' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'logo_path' => 'nullable|string',
            'address' => 'nullable|string',
            'invoice' => 'required|boolean',
            'vouchers' => 'nullable|boolean',
            'reminders' => 'nullable|boolean',
            'statement_status' => 'nullable|string',
            'remarks' => 'nullable|string',
            'ledger_frequency' => 'nullable|string',
            'tomorrow_reminder' => 'nullable|boolean',
            'exempt_bulk_lock' => 'nullable|boolean',
        ]);

        if (!empty($validated['agent_password'])) {
            $validated['agent_password'] = bcrypt($validated['agent_password']);
        }

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
            'agent_username' => 'nullable|string|unique:uc_companies,agent_username,' . $id,
            'agent_password' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'logo_path' => 'nullable|string',
            'address' => 'nullable|string',
            'invoice' => 'required|boolean',
            'vouchers' => 'nullable|boolean',
            'reminders' => 'nullable|boolean',
            'statement_status' => 'nullable|string',
            'remarks' => 'nullable|string',
            'ledger_frequency' => 'nullable|string',
            'tomorrow_reminder' => 'nullable|boolean',
            'exempt_bulk_lock' => 'nullable|boolean',
        ]);

        if (!empty($validated['agent_password'])) {
            $validated['agent_password'] = bcrypt($validated['agent_password']);
        } else {
            unset($validated['agent_password']);
        }

        $company->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Company updated successfully!',
            'data' => $company
        ]);
    }
}
