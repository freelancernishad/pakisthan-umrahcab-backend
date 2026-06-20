<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcBooking;
use Illuminate\Http\Request;

class UcBookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = UcBooking::query()->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:uc_customers,id',
            'pickup' => 'required|string',
            'destination' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'passengers' => 'required|string',
            'car_type' => 'required|string',
            'car_price' => 'required|numeric',
            'full_name' => 'required|string',
            'email' => 'nullable|email',
            'whatsapp' => 'required|string',
            'flight_no' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['customer_id']) && !empty($validated['full_name'])) {
            $customer = \App\Models\UmrahCab\UcCustomer::where('name', 'like', trim($validated['full_name']))->first();
            if ($customer) {
                $validated['customer_id'] = $customer->id;
            }
        }

        $validated['booking_code'] = 'UCB-' . rand(100000, 999999);
        $validated['status'] = 'Pending Check';

        $booking = UcBooking::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully!',
            'data' => $booking
        ], 201);
    }

    public function getStatus($code)
    {
        $booking = UcBooking::where('booking_code', $code)
            ->orWhere('full_name', 'like', "%{$code}%")
            ->get();

        return response()->json($booking);
    }

    public function dashboardSummary()
    {
        return response()->json([
            'total' => UcBooking::count(),
            'active' => UcBooking::where('status', 'Active Dispatch')->count(),
            'confirmed' => UcBooking::where('status', 'Confirmed Booking')->count(),
            'pending' => UcBooking::where('status', 'Pending Check')->count(),
            'list' => UcBooking::orderBy('id', 'desc')->take(10)->get()
        ]);
    }

    public function update(Request $request, $id)
    {
        $booking = UcBooking::where('id', $id)->orWhere('booking_code', $id)->firstOrFail();

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:uc_customers,id',
            'pickup' => 'nullable|string',
            'destination' => 'nullable|string',
            'date' => 'nullable|date',
            'time' => 'nullable',
            'passengers' => 'nullable|string',
            'car_type' => 'nullable|string',
            'car_price' => 'nullable|numeric',
            'full_name' => 'nullable|string',
            'email' => 'nullable|email',
            'whatsapp' => 'nullable|string',
            'flight_no' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        if (empty($validated['customer_id']) && !empty($validated['full_name'])) {
            $customer = \App\Models\UmrahCab\UcCustomer::where('name', 'like', trim($validated['full_name']))->first();
            if ($customer) {
                $validated['customer_id'] = $customer->id;
            }
        }

        $booking->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Booking updated successfully!',
            'data' => $booking
        ]);
    }
}
