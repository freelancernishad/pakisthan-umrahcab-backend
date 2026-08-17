<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcCustomer;
use Illuminate\Http\Request;

class UcCustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $company = $request->query('company');
        $perPage = $request->query('per_page', 10);

        $query = UcCustomer::with(['bookings', 'flights', 'trains'])->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('custom_id', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%");
            });
        }

        if ($company && $company !== 'All') {
            $query->where('company', $company);
        }

        // Allow fetching all if requested or if no pagination parameter is sent
        if ($request->query('all') === 'true' || !$request->has('page')) {
            return response()->json($query->get());
        }

        return response()->json($query->paginate($perPage));
    }

    private function normalizePhoneNumber(?string $phone): ?string
    {
        if (empty($phone)) return null;
        $cleaned = trim($phone);
        if (str_starts_with($cleaned, '00')) {
            return '+' . substr($cleaned, 2);
        }
        if (!str_starts_with($cleaned, '+')) {
            if (preg_match('/^(966|92|880|91|971|44|1)/', $cleaned)) {
                return '+' . $cleaned;
            }
        }
        return $cleaned;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'company' => 'required|string',
            'contact' => 'nullable|string',
            'phone' => 'nullable|string',
            'secondary_phone' => 'nullable|string',
            'alternative_phone' => 'nullable|string',
            'email' => 'nullable|email',
            'passport_no' => 'nullable|string',
            'hotel_info' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if (!empty($validated['phone'])) {
            $validated['phone'] = $this->normalizePhoneNumber($validated['phone']);
        }
        if (!empty($validated['secondary_phone'])) {
            $validated['secondary_phone'] = $this->normalizePhoneNumber($validated['secondary_phone']);
        }
        if (!empty($validated['alternative_phone'])) {
            $validated['alternative_phone'] = $this->normalizePhoneNumber($validated['alternative_phone']);
        }

        if (empty($validated['contact'])) {
            $phones = collect([$validated['phone'] ?? null, $validated['secondary_phone'] ?? null, $validated['alternative_phone'] ?? null])->filter()->implode(' / ');
            $emailInfo = $request->email ? " | Email: {$request->email}" : "";
            $passportInfo = $request->passport_no ? " | Passport: {$request->passport_no}" : "";
            $hotelInfo = $request->hotel_info ? " | Hotel: {$request->hotel_info}" : "";
            $notesInfo = $request->notes ? " | Notes: {$request->notes}" : "";
            $validated['contact'] = trim("{$phones}{$emailInfo}{$passportInfo}{$hotelInfo}{$notesInfo}") ?: 'N/A';
        }

        $count = UcCustomer::count() + 1;
        $validated['custom_id'] = "#CST-{$count}";
        
        $authUser = $request->user();
        $adminName = $authUser ? ($authUser->name ?: ($authUser->username ?: 'hebacab')) : 'hebacab';
        $validated['registered_by'] = $request->input('registered_by', "{$adminName} (Today)");
        $validated['last_update'] = 'No edits';

        $customer = UcCustomer::create($validated);

        $this->syncUnlinkedBookings($customer);

        return response()->json([
            'success' => true,
            'message' => 'Customer registered successfully!',
            'data' => $customer
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $customer = UcCustomer::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string',
            'company' => 'required|string',
            'contact' => 'nullable|string',
            'phone' => 'nullable|string',
            'secondary_phone' => 'nullable|string',
            'alternative_phone' => 'nullable|string',
            'email' => 'nullable|email',
            'passport_no' => 'nullable|string',
            'hotel_info' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if (!empty($validated['phone'])) {
            $validated['phone'] = $this->normalizePhoneNumber($validated['phone']);
        }
        if (!empty($validated['secondary_phone'])) {
            $validated['secondary_phone'] = $this->normalizePhoneNumber($validated['secondary_phone']);
        }
        if (!empty($validated['alternative_phone'])) {
            $validated['alternative_phone'] = $this->normalizePhoneNumber($validated['alternative_phone']);
        }

        if (empty($validated['contact'])) {
            $phones = collect([$validated['phone'] ?? null, $validated['secondary_phone'] ?? null, $validated['alternative_phone'] ?? null])->filter()->implode(' / ');
            $emailInfo = $request->email ? " | Email: {$request->email}" : "";
            $passportInfo = $request->passport_no ? " | Passport: {$request->passport_no}" : "";
            $hotelInfo = $request->hotel_info ? " | Hotel: {$request->hotel_info}" : "";
            $notesInfo = $request->notes ? " | Notes: {$request->notes}" : "";
            $validated['contact'] = trim("{$phones}{$emailInfo}{$passportInfo}{$hotelInfo}{$notesInfo}") ?: 'N/A';
        }

        $authUser = $request->user();
        $adminName = $authUser ? ($authUser->name ?: ($authUser->username ?: 'hebacab')) : 'hebacab';
        $validated['last_update'] = $request->input('last_update', "{$adminName} (Edited Today)");

        $customer->update($validated);

        // Sync updated customer phone and name to linked booking records
        $primaryPhone = $customer->phone ?: ($customer->secondary_phone ?: $customer->alternative_phone);
        if (!empty($primaryPhone)) {
            \App\Models\UmrahCab\UcBooking::where('customer_id', $customer->id)->update([
                'whatsapp' => $primaryPhone,
                'full_name' => $customer->name,
            ]);
        }

        $this->syncUnlinkedBookings($customer);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully!',
            'data' => $customer
        ]);
    }

    public function show($id)
    {
        $customer = UcCustomer::where('id', $id)
            ->orWhere('custom_id', $id)
            ->firstOrFail();

        $this->syncUnlinkedBookings($customer);

        // Fetch records linked via the foreign key customer_id
        $bookings = \App\Models\UmrahCab\UcBooking::where('customer_id', $customer->id)->get();
        $services = \App\Models\UmrahCab\UcService::where('customer_id', $customer->id)->get();
        $flights = \App\Models\UmrahCab\UcFlight::where('customer_id', $customer->id)->get();
        $trains = \App\Models\UmrahCab\UcTrain::where('customer_id', $customer->id)->get();
        $hotels = \App\Models\UmrahCab\UcHotel::where('customer_id', $customer->id)->get();

        return response()->json([
            'customer' => $customer,
            'bookings' => $bookings,
            'services' => $services,
            'flights' => $flights,
            'trains' => $trains,
            'hotels' => $hotels
        ]);
    }

    private function syncUnlinkedBookings(UcCustomer $customer)
    {
        $invalidPhones = ['+966501199008', '+966567799616', '+966501234567', '+966', 'N/A', '123456', '000000'];
        
        $validPhones = collect([$customer->phone, $customer->secondary_phone, $customer->alternative_phone])
            ->map(fn($p) => trim($p ?? ''))
            ->filter(fn($p) => !empty($p) && strlen($p) >= 8 && !in_array($p, $invalidPhones));

        $email = trim($customer->email ?? '');
        $validEmail = !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && !str_contains($email, 'example.com') && !str_contains($email, 'test.com');

        if ($validPhones->isEmpty() && !$validEmail) {
            return;
        }

        $unlinkedBookingsQuery = \App\Models\UmrahCab\UcBooking::whereNull('customer_id');

        $unlinkedBookingsQuery->where(function($q) use ($validPhones, $validEmail, $email) {
            $first = true;

            if ($validEmail) {
                $q->where('email', 'like', $email);
                $first = false;
            }

            foreach ($validPhones as $phone) {
                if ($first) {
                    $q->where('whatsapp', 'like', "%{$phone}%");
                    $first = false;
                } else {
                    $q->orWhere('whatsapp', 'like', "%{$phone}%");
                }
            }
        });

        $unlinkedBookingsQuery->update(['customer_id' => $customer->id]);
    }

    public function destroy($id)
    {
        $customer = UcCustomer::where('id', $id)
            ->orWhere('custom_id', $id)
            ->firstOrFail();

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully!'
        ]);
    }
}
