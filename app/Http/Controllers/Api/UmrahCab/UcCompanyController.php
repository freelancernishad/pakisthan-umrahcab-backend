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

        if (isset($validated['logo_path']) && preg_match('/^data:image\/(\w+);base64,/', $validated['logo_path'], $type)) {
            $data = substr($validated['logo_path'], strpos($validated['logo_path'], ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif, webp

            if (in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $data = base64_decode($data);
                $fileName = 'logo_' . time() . '.' . $type;
                $uploadPath = public_path('uploads');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                file_put_contents($uploadPath . '/' . $fileName, $data);
                $validated['logo_path'] = 'uploads/' . $fileName;
            }
        }

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

        if (isset($validated['logo_path']) && preg_match('/^data:image\/(\w+);base64,/', $validated['logo_path'], $type)) {
            $data = substr($validated['logo_path'], strpos($validated['logo_path'], ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif, webp

            if (in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $data = base64_decode($data);
                $fileName = 'logo_' . time() . '.' . $type;
                $uploadPath = public_path('uploads');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                file_put_contents($uploadPath . '/' . $fileName, $data);
                $validated['logo_path'] = 'uploads/' . $fileName;
            }
        }

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

    public function show($id)
    {
        $company = UcCompany::where('id', $id)->firstOrFail();

        // Fetch related customers
        $customers = \App\Models\UmrahCab\UcCustomer::where('company', $company->name)->get();
        $customerIds = $customers->pluck('id');

        // Fetch related bookings
        $bookings = \App\Models\UmrahCab\UcBooking::whereIn('customer_id', $customerIds)->get();

        // Fetch related ledgers
        $ledgers = \App\Models\UmrahCab\UcLedger::where('company', $company->name)->orderBy('id', 'desc')->get();

        // Fetch related payments
        $payments = \App\Models\UmrahCab\UcPayment::where('company', $company->name)->orderBy('id', 'desc')->get();

        return response()->json([
            'company' => $company,
            'customers' => $customers,
            'bookings' => $bookings,
            'ledgers' => $ledgers,
            'payments' => $payments
        ]);
    }
}
