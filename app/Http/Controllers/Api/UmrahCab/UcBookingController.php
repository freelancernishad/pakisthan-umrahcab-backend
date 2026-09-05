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
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $query = UcBooking::query()->with('driver')->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        if ($request->has('page')) {
            $perPage = $request->query('per_page', 10);
            return response()->json($query->paginate($perPage));
        }

        return response()->json($query->get());
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
            'customer_id' => 'nullable|exists:uc_customers,id',
            'driver_id' => 'nullable|integer|exists:uc_drivers,id',
            'pickup' => 'required|string',
            'destination' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'passengers' => 'required|string',
            'car_type' => 'required|string',
            'car_price' => 'required|numeric',
            'full_name' => 'required|string',
            'email' => 'nullable|email',
            'whatsapp' => 'required|string',
            'flight_no' => 'nullable|string',
            'notes' => 'nullable|string',
            'visa_type' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'received_amount' => 'nullable|numeric',
            'pending_amount' => 'nullable|numeric',
        ]);

        if (!empty($validated['whatsapp'])) {
            $validated['whatsapp'] = $this->normalizePhoneNumber($validated['whatsapp']);
        }

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

        if (empty($validated['booking_code'])) {
            $lastBooking = UcBooking::orderBy('id', 'desc')->first();
            $nextNum = 10000 + ($lastBooking ? ($lastBooking->id + 1) : 1);
            $validated['booking_code'] = 'HCB-' . $nextNum;
        } else {
            $validated['booking_code'] = preg_replace('/^UCB-/i', 'HCB-', $validated['booking_code']);
        }
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
                'description' => 'Booking Created: ' . ($booking->booking_code ?? ('HCB-' . (10000 + $booking->id))),
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

    private function findBookingFlexible($id, $withDriver = false)
    {
        $idVariants = [
            $id,
            preg_replace('/^HCB-/i', 'UCB-', $id),
            preg_replace('/^UCB-/i', 'HCB-', $id),
            preg_replace('/^(HCB|UCB)-/i', '', $id),
        ];

        $query = UcBooking::query();
        if ($withDriver) {
            $query->with('driver');
        }

        $booking = $query->where(function ($q) use ($id, $idVariants) {
            $q->whereIn('booking_code', $idVariants);
            if (is_numeric($id)) {
                $q->orWhere('id', $id);
            }
        })->first();

        // Also check if id is derived from HCB-10000 + id
        if (!$booking && preg_match('/^(HCB|UCB)-(\d+)$/i', $id, $matches)) {
            $possibleId = intval($matches[2]) - 10000;
            if ($possibleId > 0) {
                $booking = $withDriver ? UcBooking::with('driver')->find($possibleId) : UcBooking::find($possibleId);
            }
            if (!$booking) {
                $booking = $withDriver ? UcBooking::with('driver')->find(intval($matches[2])) : UcBooking::find(intval($matches[2]));
            }
        }

        return $booking;
    }

    public function show($id)
    {
        $booking = $this->findBookingFlexible($id, true);
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking details not found.'], 404);
        }
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
        $booking = $this->findBookingFlexible($id, false);
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found.'], 404);
        }
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
            'date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($booking) {
                    if ($value !== $booking->date && \Carbon\Carbon::parse($value)->lt(today())) {
                        $fail('The ' . $attribute . ' cannot be set to a past date.');
                    }
                }
            ],
            'time' => 'nullable',
            'passengers' => 'nullable|string',
            'car_type' => 'nullable|string',
            'car_price' => 'nullable|numeric',
            'full_name' => 'nullable|string',
            'email' => 'nullable|email',
            'whatsapp' => 'nullable|string',
            'flight_no' => 'nullable|string',
            'notes' => 'nullable|string',
            'visa_type' => 'nullable|string',
            'status' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'received_amount' => 'nullable|numeric',
            'pending_amount' => 'nullable|numeric',
            'driver_trip_status' => 'nullable|string',
        ]);

        if (!empty($validated['whatsapp'])) {
            $validated['whatsapp'] = $this->normalizePhoneNumber($validated['whatsapp']);
        }

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
                    'description' => 'Booking Refund (Admin Rejected): ' . ($booking->booking_code ?? ('HCB-' . (10000 + $booking->id))),
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
                    'description' => 'Booking Re-charged: ' . ($booking->booking_code ?? ('HCB-' . (10000 + $booking->id))),
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

    /**
     * Public method to check booking/invoice status by code, name, phone or invoice.
     */
    public function getStatus($code)
    {
        $code = trim($code);
        if (empty($code)) {
            return response()->json([]);
        }

        $upperCode = strtoupper($code);
        $results = [];

        // 1. Check exact match for WCB-, UCI-, UCO-, or INV- invoice & individual order codes
        if (str_starts_with($upperCode, 'WCB-') || str_starts_with($upperCode, 'UCI-') || str_starts_with($upperCode, 'UCO-') || str_starts_with($upperCode, 'INV-')) {
            $invoices = \App\Models\UmrahCab\UcInvoice::with('individual_order')
                ->where('invoice_code', $code)
                ->orWhere('invoice_code', $upperCode)
                ->get();

            foreach ($invoices as $inv) {
                $ord = $inv->individual_order;
                $results[] = [
                    'id' => $inv->invoice_code,
                    'booking_code' => $inv->invoice_code,
                    'pickup' => $ord ? $ord->pickup : '—',
                    'destination' => $ord ? $ord->destination : '—',
                    'date' => $inv->date,
                    'time' => $ord ? $ord->time : '—',
                    'car_type' => $ord ? $ord->car_type : 'Standard',
                    'car_price' => $inv->amount,
                    'full_name' => $inv->customer,
                    'status' => $inv->status
                ];
            }

            $orders = \App\Models\UmrahCab\UcIndividualOrder::with('invoice')
                ->where('order_code', $code)
                ->orWhere('order_code', $upperCode)
                ->orWhereHas('invoice', function($q) use ($code, $upperCode) {
                    $q->where('invoice_code', $code)->orWhere('invoice_code', $upperCode);
                })
                ->get();

            foreach ($orders as $ord) {
                $invCode = $ord->invoice ? $ord->invoice->invoice_code : $ord->order_code;
                if (!collect($results)->pluck('id')->contains($invCode)) {
                    $results[] = [
                        'id' => $invCode,
                        'booking_code' => $invCode,
                        'pickup' => $ord->pickup,
                        'destination' => $ord->destination,
                        'date' => $ord->date,
                        'time' => $ord->time,
                        'car_type' => $ord->car_type,
                        'car_price' => $ord->car_price,
                        'full_name' => $ord->full_name,
                        'status' => $ord->status ?: 'Pending'
                    ];
                }
            }

            if (!empty($results)) {
                return response()->json($results);
            }
        }

        // 2. Check exact match for HCB- or UCB- booking codes
        if (preg_match('/^(?:HCB|UCB)-?\d+$/i', $code)) {
            $hcbCode = preg_replace('/^UCB-/i', 'HCB-', $code);
            $ucbCode = preg_replace('/^HCB-/i', 'UCB-', $code);

            // First: Search exact booking_code match
            $bookings = UcBooking::with(['customer', 'driver'])
                ->where('booking_code', $code)
                ->orWhere('booking_code', $hcbCode)
                ->orWhere('booking_code', $ucbCode)
                ->get();

            // Fallback: Check numeric ID match ONLY if booking_code is null/empty or matches
            if ($bookings->isEmpty() && preg_match('/^(?:HCB|UCB)-(\d+)$/i', $code, $m)) {
                $num = (int)$m[1];
                $possibleId = $num > 10000 ? $num - 10000 : $num;
                $bookings = UcBooking::with(['customer', 'driver'])
                    ->where(function($q) use ($num, $possibleId) {
                        $q->where('id', $num)->orWhere('id', $possibleId);
                    })
                    ->where(function($q) use ($code, $hcbCode, $ucbCode) {
                        $q->whereNull('booking_code')
                          ->orWhere('booking_code', '')
                          ->orWhere('booking_code', $code)
                          ->orWhere('booking_code', $hcbCode)
                          ->orWhere('booking_code', $ucbCode);
                    })
                    ->get();
            }

            foreach ($bookings as $b) {
                $bCode = $b->booking_code ? preg_replace('/^UCB-/i', 'HCB-', $b->booking_code) : ('HCB-' . (10000 + $b->id));
                $results[] = [
                    'id' => $bCode,
                    'booking_code' => $bCode,
                    'pickup' => $b->pickup,
                    'destination' => $b->destination,
                    'date' => $b->date,
                    'time' => $b->time,
                    'car_type' => $b->car_type,
                    'car_price' => $b->car_price,
                    'full_name' => $b->full_name,
                    'status' => $b->status ?: 'Active'
                ];
            }

            if (!empty($results)) {
                return response()->json($results);
            }
        }

        // 3. Search exact numeric ID (e.g. 5000 -> WCB-5000 or HCB-10004)
        if (is_numeric($code)) {
            $num = (int)$code;
            $possibleId = $num > 10000 ? $num - 10000 : ($num >= 5000 ? $num - 5000 : $num);

            if ($num >= 5000) {
                $orders = \App\Models\UmrahCab\UcIndividualOrder::with('invoice')
                    ->where('order_code', 'WCB-' . $num)
                    ->orWhere('order_code', 'UCO-' . $num)
                    ->orWhereHas('invoice', function($q) use ($num) {
                        $q->where('invoice_code', 'WCB-' . $num)->orWhere('invoice_code', 'UCI-' . $num);
                    })
                    ->get();

                foreach ($orders as $ord) {
                    $invCode = $ord->invoice ? $ord->invoice->invoice_code : $ord->order_code;
                    $results[] = [
                        'id' => $invCode,
                        'booking_code' => $invCode,
                        'pickup' => $ord->pickup,
                        'destination' => $ord->destination,
                        'date' => $ord->date,
                        'time' => $ord->time,
                        'car_type' => $ord->car_type,
                        'car_price' => $ord->car_price,
                        'full_name' => $ord->full_name,
                        'status' => $ord->status ?: 'Pending'
                    ];
                }

                if (!empty($results)) {
                    return response()->json($results);
                }
            }

            // First: Search exact booking_code match
            $bookings = UcBooking::with(['customer', 'driver'])
                ->where('booking_code', 'HCB-' . $num)
                ->orWhere('booking_code', 'UCB-' . $num)
                ->get();

            // Fallback: Check numeric ID match ONLY if booking_code is null/empty or matches
            if ($bookings->isEmpty()) {
                $bookings = UcBooking::with(['customer', 'driver'])
                    ->where(function($q) use ($num, $possibleId) {
                        $q->where('id', $num)->orWhere('id', $possibleId);
                    })
                    ->where(function($q) use ($num) {
                        $q->whereNull('booking_code')
                          ->orWhere('booking_code', '')
                          ->orWhere('booking_code', 'HCB-' . $num)
                          ->orWhere('booking_code', 'UCB-' . $num);
                    })
                    ->get();
            }

            foreach ($bookings as $b) {
                $bCode = $b->booking_code ? preg_replace('/^UCB-/i', 'HCB-', $b->booking_code) : ('HCB-' . (10000 + $b->id));
                $results[] = [
                    'id' => $bCode,
                    'booking_code' => $bCode,
                    'pickup' => $b->pickup,
                    'destination' => $b->destination,
                    'date' => $b->date,
                    'time' => $b->time,
                    'car_type' => $b->car_type,
                    'car_price' => $b->car_price,
                    'full_name' => $b->full_name,
                    'status' => $b->status ?: 'Active'
                ];
            }

            if (!empty($results)) {
                return response()->json($results);
            }
        }

        // 4. Phone number search (Require at least 7 digits to prevent short string false positives)
        $digits = preg_replace('/[^0-9]/', '', $code);
        if (strlen($digits) >= 7) {
            $bookings = UcBooking::with(['customer', 'driver'])
                ->where('whatsapp', 'like', "%{$digits}%")
                ->get();

            foreach ($bookings as $b) {
                $bCode = $b->booking_code ? preg_replace('/^UCB-/i', 'HCB-', $b->booking_code) : ('HCB-' . (10000 + $b->id));
                $results[] = [
                    'id' => $bCode,
                    'booking_code' => $bCode,
                    'pickup' => $b->pickup,
                    'destination' => $b->destination,
                    'date' => $b->date,
                    'time' => $b->time,
                    'car_type' => $b->car_type,
                    'car_price' => $b->car_price,
                    'full_name' => $b->full_name,
                    'status' => $b->status ?: 'Active'
                ];
            }

            $orders = \App\Models\UmrahCab\UcIndividualOrder::with('invoice')
                ->where('whatsapp', 'like', "%{$digits}%")
                ->get();

            foreach ($orders as $ord) {
                $invCode = $ord->invoice ? $ord->invoice->invoice_code : $ord->order_code;
                if (!collect($results)->pluck('id')->contains($invCode)) {
                    $results[] = [
                        'id' => $invCode,
                        'booking_code' => $invCode,
                        'pickup' => $ord->pickup,
                        'destination' => $ord->destination,
                        'date' => $ord->date,
                        'time' => $ord->time,
                        'car_type' => $ord->car_type,
                        'car_price' => $ord->car_price,
                        'full_name' => $ord->full_name,
                        'status' => $ord->status ?: 'Pending'
                    ];
                }
            }

            if (!empty($results)) {
                return response()->json($results);
            }
        }

        // 5. Passenger Name search (Length >= 3)
        if (strlen($code) >= 3) {
            $bookings = UcBooking::with(['customer', 'driver'])
                ->where('full_name', 'like', "%{$code}%")
                ->get();

            foreach ($bookings as $b) {
                $bCode = $b->booking_code ? preg_replace('/^UCB-/i', 'HCB-', $b->booking_code) : ('HCB-' . (10000 + $b->id));
                $results[] = [
                    'id' => $bCode,
                    'booking_code' => $bCode,
                    'pickup' => $b->pickup,
                    'destination' => $b->destination,
                    'date' => $b->date,
                    'time' => $b->time,
                    'car_type' => $b->car_type,
                    'car_price' => $b->car_price,
                    'full_name' => $b->full_name,
                    'status' => $b->status ?: 'Active'
                ];
            }

            $orders = \App\Models\UmrahCab\UcIndividualOrder::with('invoice')
                ->where('full_name', 'like', "%{$code}%")
                ->orWhere('email', 'like', "%{$code}%")
                ->get();

            foreach ($orders as $ord) {
                $invCode = $ord->invoice ? $ord->invoice->invoice_code : $ord->order_code;
                if (!collect($results)->pluck('id')->contains($invCode)) {
                    $results[] = [
                        'id' => $invCode,
                        'booking_code' => $invCode,
                        'pickup' => $ord->pickup,
                        'destination' => $ord->destination,
                        'date' => $ord->date,
                        'time' => $ord->time,
                        'car_type' => $ord->car_type,
                        'car_price' => $ord->car_price,
                        'full_name' => $ord->full_name,
                        'status' => $ord->status ?: 'Pending'
                    ];
                }
            }
        }

        return response()->json($results);
    }
}

