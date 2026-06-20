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

        if (empty($validated['customer_id'])) {
            $validated['customer_id'] = $this->resolveCustomerId($validated);
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

        if (!array_key_exists('customer_id', $validated)) {
            if (isset($validated['full_name']) || isset($validated['email']) || isset($validated['whatsapp'])) {
                $validated['customer_id'] = $this->resolveCustomerId(array_merge($booking->toArray(), $validated));
            }
        } else {
            if (empty($validated['customer_id'])) {
                $validated['customer_id'] = $this->resolveCustomerId(array_merge($booking->toArray(), $validated));
            }
        }

        $booking->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Booking updated successfully!',
            'data' => $booking
        ]);
    }

    private function resolveCustomerId(array $data)
    {
        $fullName = isset($data['full_name']) ? trim($data['full_name']) : '';
        $email = isset($data['email']) ? trim($data['email']) : '';
        $whatsapp = isset($data['whatsapp']) ? trim($data['whatsapp']) : '';

        if (empty($fullName) && empty($email) && empty($whatsapp)) {
            return null;
        }

        $customerQuery = \App\Models\UmrahCab\UcCustomer::query();
        $hasCondition = false;

        if (!empty($fullName)) {
            $customerQuery->where('name', 'like', $fullName);
            $hasCondition = true;
        }

        if (!empty($email)) {
            if ($hasCondition) {
                $customerQuery->orWhere('email', '=', $email);
            } else {
                $customerQuery->where('email', '=', $email);
                $hasCondition = true;
            }
        }

        if (!empty($whatsapp)) {
            if ($hasCondition) {
                $customerQuery->orWhere(function($sub) use ($whatsapp) {
                    $sub->where('phone', 'like', "%{$whatsapp}%")
                        ->orWhere('secondary_phone', 'like', "%{$whatsapp}%")
                        ->orWhere('alternative_phone', 'like', "%{$whatsapp}%")
                        ->orWhere('contact', 'like', "%{$whatsapp}%");
                });
            } else {
                $customerQuery->where(function($sub) use ($whatsapp) {
                    $sub->where('phone', 'like', "%{$whatsapp}%")
                        ->orWhere('secondary_phone', 'like', "%{$whatsapp}%")
                        ->orWhere('alternative_phone', 'like', "%{$whatsapp}%")
                        ->orWhere('contact', 'like', "%{$whatsapp}%");
                });
                $hasCondition = true;
            }
        }

        $customer = $customerQuery->first();
        return $customer ? $customer->id : null;
    }
}
