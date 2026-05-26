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

        $query = UcCustomer::query()->orderBy('id', 'desc');

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'company' => 'required|string',
            'contact' => 'nullable|string',
        ]);

        $count = UcCustomer::count() + 1;
        $validated['custom_id'] = "#CST-{$count}";
        $validated['registered_by'] = 'umrahcab (Today)';
        $validated['last_update'] = 'No edits';

        $customer = UcCustomer::create($validated);

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
        ]);
        $validated['last_update'] = 'umrahcab (Edited Today)';

        $customer->update($validated);

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

        // Fetch records linked via the foreign key customer_id
        $bookings = \App\Models\UmrahCab\UcBooking::where('customer_id', $customer->id)->get();
        $services = \App\Models\UmrahCab\UcService::where('customer_id', $customer->id)->get();
        $flights = \App\Models\UmrahCab\UcFlight::where('customer_id', $customer->id)->get();
        $trains = \App\Models\UmrahCab\UcTrain::where('customer_id', $customer->id)->get();

        return response()->json([
            'customer' => $customer,
            'bookings' => $bookings,
            'services' => $services,
            'flights' => $flights,
            'trains' => $trains
        ]);
    }
}
