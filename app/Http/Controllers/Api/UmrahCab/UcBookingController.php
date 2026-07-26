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

        if (!isset($validated['received_amount']) || is_null($validated['received_amount'])) {
            $validated['received_amount'] = 0;
        }
        if (!isset($validated['pending_amount']) || is_null($validated['pending_amount'])) {
            $validated['pending_amount'] = 0;
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
        $oldDriverId = $booking->driver_id;
        $oldDriverTripStatus = $booking->driver_trip_status;

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

        if (array_key_exists('received_amount', $validated) && is_null($validated['received_amount'])) {
            $validated['received_amount'] = 0;
        }
        if (array_key_exists('pending_amount', $validated) && is_null($validated['pending_amount'])) {
            $validated['pending_amount'] = 0;
        }

        $driverChanged = false;
        $tripStatusChanged = false;
        $statusChanged = false;

        if (array_key_exists('driver_id', $validated) && $validated['driver_id'] != $oldDriverId) {
            $driverChanged = true;
        }
        if (array_key_exists('driver_trip_status', $validated) && $validated['driver_trip_status'] !== $oldDriverTripStatus) {
            $tripStatusChanged = true;
        }
        if (array_key_exists('status', $validated) && $validated['status'] !== $oldStatus) {
            $statusChanged = true;
        }

        if ($driverChanged || $tripStatusChanged || $statusChanged) {
            $validated['reminder1_sent'] = false;
            $validated['reminder2_sent'] = false;
            $validated['reminder3_sent'] = false;
        }

        $booking->update($validated);

        if ($driverChanged || $tripStatusChanged) {
            $newDriver = $booking->driver()->first();
            $driverName = $newDriver ? $newDriver->name : 'No Driver';
            \App\Models\UmrahCab\UcReminderLog::create([
                'booking_id' => $booking->id,
                'type' => 'BKG',
                'reminder_type' => 4, // Driver Status Change System Log
                'recipient' => 'System Update',
                'driver_name' => $driverName,
                'driver_trip_status' => $booking->driver_trip_status ?: 'N/A',
            ]);
        }

        if ($statusChanged) {
            $driverName = $booking->driver ? $booking->driver->name : null;
            \App\Models\UmrahCab\UcReminderLog::create([
                'booking_id' => $booking->id,
                'type' => 'BKG',
                'reminder_type' => 5, // Booking Status Change System Log
                'recipient' => 'System Update',
                'driver_name' => $driverName,
                'driver_trip_status' => $booking->status ?: 'N/A',
            ]);
        }

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

    public function remindersList(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));

        $bookings = UcBooking::with(['driver', 'customer'])
            ->where('date', $date)
            ->whereNotIn('status', ['Cancelled', 'Rejected'])
            ->get();

        $services = \App\Models\UmrahCab\UcService::with('customer')
            ->where('date', $date)
            ->whereNotIn('status', ['Cancelled', 'Rejected'])
            ->get();

        $formattedBookings = $bookings->map(function ($b, $idx) {
            return [
                'id' => $b->booking_code ?: '#BKG-87' . ($b->id + 10),
                'rawId' => (string) $b->id,
                'type' => 'BKG',
                'date' => $b->date,
                'time' => $b->time,
                'customerName' => $b->full_name ?: ($b->customer ? $b->customer->name : 'Guest'),
                'companyName' => $b->customer ? $b->customer->company : 'Zahid Travels',
                'details' => ($b->pickup ?: 'Jeddah Airport') . ' → ' . ($b->destination ?: 'Makkah Hotel'),
                'vehicle' => $b->car_type ?: 'Sedan (Standard)',
                'phones' => $b->whatsapp ? [$b->whatsapp] : ($b->customer && $b->customer->contact ? [explode(' ', $b->customer->contact)[0]] : ['+966501234567']),
                'customerId' => $b->customer ? ($b->customer->custom_id ?: (string)$b->customer->id) : '1',
                'driverName' => $b->driver ? $b->driver->name : null,
                'driverPhone' => $b->driver ? $b->driver->phone : null,
                'driverTripStatus' => $b->driver_trip_status ?: '',
                'reminder1_sent' => (bool) $b->reminder1_sent,
                'reminder2_sent' => (bool) $b->reminder2_sent,
                'reminder3_sent' => (bool) $b->reminder3_sent,
            ];
        });

        $formattedServices = $services->map(function ($s, $idx) {
            return [
                'id' => $s->custom_id ?: '#SRV-' . $s->id,
                'rawId' => (string) $s->id,
                'type' => 'SRV',
                'date' => $s->date,
                'time' => $s->time,
                'customerName' => $s->customer ? $s->customer->name : 'Zubair Ahmad',
                'companyName' => $s->customer ? $s->customer->company : 'Zahid Travels',
                'details' => $s->name . ' (' . ($s->description ?: 'Service Details') . ')',
                'vehicle' => 'N/A',
                'phones' => $s->customer && $s->customer->contact ? [explode(' ', $s->customer->contact)[0]] : ['+966549876543'],
                'customerId' => $s->customer ? ($s->customer->custom_id ?: (string)$s->customer->id) : '3',
                'driverName' => null,
                'driverPhone' => null,
                'driverTripStatus' => '',
                'reminder1_sent' => (bool) $s->reminder1_sent,
                'reminder2_sent' => (bool) $s->reminder2_sent,
                'reminder3_sent' => (bool) $s->reminder3_sent,
            ];
        });

        $merged = $formattedBookings->concat($formattedServices);

        return response()->json($merged);
    }

    public function markReminderSent(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'type' => 'required|string|in:BKG,SRV',
            'template_id' => 'required|integer|in:1,2,3',
        ]);

        $id = $validated['id'];
        $type = $validated['type'];
        $templateId = $validated['template_id'];

        if ($type === 'BKG') {
            $record = UcBooking::with('driver')->where('id', $id)->orWhere('booking_code', $id)->firstOrFail();
        } else {
            $record = \App\Models\UmrahCab\UcService::where('id', $id)->orWhere('custom_id', $id)->firstOrFail();
        }

        $fieldName = 'reminder' . $templateId . '_sent';
        $record->$fieldName = true;
        $record->save();

        // Create log record
        $recipient = null;
        if ($type === 'BKG') {
            $recipient = $record->whatsapp ?: ($record->customer ? explode(' ', $record->customer->contact)[0] : null);
        } else {
            $recipient = $record->customer && $record->customer->contact ? explode(' ', $record->customer->contact)[0] : null;
        }

        \App\Models\UmrahCab\UcReminderLog::create([
            'booking_id' => $type === 'BKG' ? $record->id : null,
            'service_id' => $type === 'SRV' ? $record->id : null,
            'type' => $type,
            'reminder_type' => $templateId,
            'recipient' => $recipient,
            'driver_name' => $type === 'BKG' && $record->driver ? $record->driver->name : null,
            'driver_trip_status' => $type === 'BKG' ? $record->driver_trip_status : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reminder sent status marked and logged successfully.',
            'data' => $record
        ]);
    }

    public function reminderHistory($id, Request $request)
    {
        $type = $request->query('type', 'BKG');

        $query = \App\Models\UmrahCab\UcReminderLog::orderBy('created_at', 'desc');

        if ($type === 'BKG') {
            $booking = UcBooking::where('id', $id)->orWhere('booking_code', $id)->firstOrFail();
            $query->where('booking_id', $booking->id)->where('type', 'BKG');
        } else {
            $service = \App\Models\UmrahCab\UcService::where('id', $id)->orWhere('custom_id', $id)->firstOrFail();
            $query->where('service_id', $service->id)->where('type', 'SRV');
        }

        $logs = $query->get();

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
}
