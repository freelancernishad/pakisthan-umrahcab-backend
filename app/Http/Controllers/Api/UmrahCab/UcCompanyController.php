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
            'price_group' => 'nullable|string',
        ]);

        $validated['logo_path'] = $this->processLogoBase64($validated['logo_path'] ?? null);

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
        $company = UcCompany::find($id);
        if (!$company && ($request->filled('name') || $request->filled('company'))) {
            $compName = trim($request->input('name') ?? $request->input('company'));
            $company = UcCompany::where('name', $compName)->first();
            if (!$company && !empty($compName)) {
                $company = UcCompany::create([
                    'name' => $compName,
                    'statement_status' => $request->input('statement_status', 'Pending'),
                    'remarks' => $request->input('remarks', ''),
                ]);
            }
        }
        if (!$company) {
            $company = UcCompany::findOrFail($id);
        }
        $companyId = $company->id;
        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'agent_username' => 'nullable|string|unique:uc_companies,agent_username,' . $companyId,
            'agent_password' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'logo_path' => 'nullable|string',
            'address' => 'nullable|string',
            'invoice' => 'sometimes|boolean',
            'vouchers' => 'nullable|boolean',
            'reminders' => 'nullable|boolean',
            'statement_status' => 'nullable|string',
            'remarks' => 'nullable|string',
            'ledger_frequency' => 'nullable|string',
            'tomorrow_reminder' => 'nullable|boolean',
            'exempt_bulk_lock' => 'nullable|boolean',
            'price_group' => 'nullable|string',
        ]);

        if (array_key_exists('logo_path', $validated)) {
            $processedLogo = $this->processLogoBase64($validated['logo_path']);
            if (!empty($processedLogo)) {
                $validated['logo_path'] = $processedLogo;
            } elseif (empty($validated['logo_path']) && !empty($company->logo_path)) {
                // Do not overwrite existing valid logo_path with empty string
                unset($validated['logo_path']);
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

    /**
     * Decode base64 logo string and save to public/uploads directory
     */
    private function processLogoBase64(?string $logoPath): ?string
    {
        if (empty($logoPath) || !is_string($logoPath)) {
            return null;
        }

        $trimmed = trim($logoPath);
        if (empty($trimmed)) {
            return null;
        }

        // If it's already a relative path or http URL (not base64), return as is
        if (!str_starts_with($trimmed, 'data:')) {
            return $trimmed;
        }

        // Extract mime type and base64 string
        $ext = 'png';
        if (preg_match('/^data:image\/([^;]+);base64,/i', $trimmed, $matches)) {
            $rawMime = strtolower($matches[1]);
            if (str_contains($rawMime, 'jpeg') || str_contains($rawMime, 'jpg')) {
                $ext = 'jpg';
            } elseif (str_contains($rawMime, 'webp')) {
                $ext = 'webp';
            } elseif (str_contains($rawMime, 'gif')) {
                $ext = 'gif';
            } elseif (str_contains($rawMime, 'svg')) {
                $ext = 'svg';
            }
        }

        $base64Data = substr($trimmed, strpos($trimmed, ',') + 1);
        $data = base64_decode($base64Data);
        if ($data === false || empty($data)) {
            return null;
        }

        $fileName = 'logo_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $uploadPath = public_path('uploads');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        file_put_contents($uploadPath . '/' . $fileName, $data);
        return 'uploads/' . $fileName;
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
