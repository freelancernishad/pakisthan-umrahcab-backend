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
        $query = UcBooking::query()->with('driver')->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        if ($request->has('page')) {
            $perPage = $request->query('per_page', 10);
            return response()->json($query->paginate($perPage));
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:uc_customers,id',
            'driver_id' => 'nullable|integer|exists:uc_drivers,id',
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
            'payment_method' => 'nullable|string',
            'received_amount' => 'nullable|numeric',
            'pending_amount' => 'nullable|numeric',
        ]);

        if (empty($validated['customer_id'])) {
            $validated['customer_id'] = $this->resolveCustomerId($validated);
        }

        // Proactive Balance Check for B2B agent bookings
        $customer = \App\Models\UmrahCab\UcCustomer::find($validated['customer_id']);
        if ($customer && !empty($customer->company)) {
            $companyName = $customer->company;
            $lastLedger = \App\Models\UmrahCab\UcLedger::where('company', $companyName)->orderBy('id', 'desc')->first();
            $lastBalance = $lastLedger ? $lastLedger->balance : 0;
            $amount = $validated['car_price'];

            // Enforce balance verification unless performed by an authenticated administrator
            if (!\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
                if ($lastBalance < $amount) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient balance! Your current balance is SAR " . number_format($lastBalance, 2) . ", but this booking requires SAR " . number_format($amount, 2) . ". Please deposit funds."
                    ], 400);
                }
            }
        }

        $validated['booking_code'] = 'UCB-' . rand(100000, 999999);
        $validated['status'] = 'Pending Check';

        $booking = UcBooking::create($validated);

        // Charge B2B agent balance if booking is created under a company
        $customer = \App\Models\UmrahCab\UcCustomer::find($booking->customer_id);
        if ($customer && !empty($customer->company)) {
            $companyName = $customer->company;
            $lastLedger = \App\Models\UmrahCab\UcLedger::where('company', $companyName)->orderBy('id', 'desc')->first();
            $lastBalance = $lastLedger ? $lastLedger->balance : 0;
            $amount = $booking->car_price;
            $newBalance = $lastBalance - $amount;

            \App\Models\UmrahCab\UcLedger::create([
                'company' => $companyName,
                'custom_id' => 'LED-' . rand(1000, 9999),
                'date' => date('Y-m-d'),
                'description' => 'Booking Created: ' . ($booking->booking_code ?? 'UCB-'.$booking->id),
                'debit' => $amount,
                'credit' => 0,
                'balance' => $newBalance
            ]);
        }

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

    public function show($id)
    {
        $booking = UcBooking::with('driver')->where('id', $id)->orWhere('booking_code', $id)->firstOrFail();
        if (\Illuminate\Support\Facades\Auth::guard('company')->check()) {
            $company = \Illuminate\Support\Facades\Auth::guard('company')->user();
            $customer = \App\Models\UmrahCab\UcCustomer::find($booking->customer_id);
            if (!$customer || $customer->company !== $company->name) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access to this booking.'], 403);
            }
        }
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
        if (\Illuminate\Support\Facades\Auth::guard('company')->check()) {
            $company = \Illuminate\Support\Facades\Auth::guard('company')->user();
            $customer = \App\Models\UmrahCab\UcCustomer::find($booking->customer_id);
            if (!$customer || $customer->company !== $company->name) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access to this booking.'], 403);
            }
        }
        $oldStatus = $booking->status;

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:uc_customers,id',
            'driver_id' => 'nullable|integer|exists:uc_drivers,id',
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
            'payment_method' => 'nullable|string',
            'received_amount' => 'nullable|numeric',
            'pending_amount' => 'nullable|numeric',
            'driver_trip_status' => 'nullable|string',
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

        // Refund/Charge logic on status change for B2B agent
        $customer = \App\Models\UmrahCab\UcCustomer::find($booking->customer_id);
        if ($customer && !empty($customer->company)) {
            $companyName = $customer->company;
            $isNewCancelled = in_array(strtolower($booking->status), ['cancelled', 'rejected']) || str_contains(strtolower($booking->status), 'cancel') || str_contains(strtolower($booking->status), 'reject');
            $isOldCancelled = in_array(strtolower($oldStatus), ['cancelled', 'rejected']) || str_contains(strtolower($oldStatus), 'cancel') || str_contains(strtolower($oldStatus), 'reject');

            if ($isNewCancelled && !$isOldCancelled) {
                // Refund: Credit booking price back to ledger
                $lastLedger = \App\Models\UmrahCab\UcLedger::where('company', $companyName)->orderBy('id', 'desc')->first();
                $lastBalance = $lastLedger ? $lastLedger->balance : 0;
                $amount = $booking->car_price;
                $newBalance = $lastBalance + $amount;

                \App\Models\UmrahCab\UcLedger::create([
                    'company' => $companyName,
                    'custom_id' => 'LED-' . rand(1000, 9999),
                    'date' => date('Y-m-d'),
                    'description' => 'Booking Refund (Admin Rejected): ' . ($booking->booking_code ?? 'UCB-'.$booking->id),
                    'debit' => 0,
                    'credit' => $amount,
                    'balance' => $newBalance
                ]);
            } elseif (!$isNewCancelled && $isOldCancelled) {
                // Re-charge: Debit booking price from ledger
                $lastLedger = \App\Models\UmrahCab\UcLedger::where('company', $companyName)->orderBy('id', 'desc')->first();
                $lastBalance = $lastLedger ? $lastLedger->balance : 0;
                $amount = $booking->car_price;
                $newBalance = $lastBalance - $amount;

                \App\Models\UmrahCab\UcLedger::create([
                    'company' => $companyName,
                    'custom_id' => 'LED-' . rand(1000, 9999),
                    'date' => date('Y-m-d'),
                    'description' => 'Booking Re-charged: ' . ($booking->booking_code ?? 'UCB-'.$booking->id),
                    'debit' => $amount,
                    'credit' => 0,
                    'balance' => $newBalance
                ]);
            }
        }

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

    public function upcomingReminders()
    {
        $enabled = \App\Models\UmrahCab\UcWebsiteSetting::getValue('ride_notification_enabled', '1');
        if ($enabled !== '1' && $enabled !== 1 && $enabled !== 'true') {
            return response()->json([]);
        }

        $now = now();
        $twentyFourHoursFromNow = now()->addHours(24);

        // Fetch bookings for today and tomorrow where driver is not assigned and status is not Cancelled/Rejected
        $bookings = UcBooking::with('driver')
            ->whereNull('driver_id')
            ->whereNotIn('status', ['Cancelled', 'Rejected'])
            ->whereBetween('date', [now()->toDateString(), now()->addDay()->toDateString()])
            ->get();

        $upcomingBookings = $bookings->filter(function($booking) use ($now, $twentyFourHoursFromNow) {
            try {
                $bookingDateTime = \Carbon\Carbon::parse($booking->date . ' ' . $booking->time);
                return $bookingDateTime->between($now, $twentyFourHoursFromNow);
            } catch (\Exception $e) {
                return false;
            }
        });

        return response()->json(array_values($upcomingBookings->toArray()));
    }
}
