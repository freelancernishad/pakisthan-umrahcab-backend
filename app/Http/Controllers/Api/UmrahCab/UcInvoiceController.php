<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcInvoice;
use Illuminate\Http\Request;

class UcInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = UcInvoice::with('customer_relation')->orderBy('id', 'desc');

        // Search filter
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_code', 'like', "%{$search}%")
                  ->orWhere('customer', 'like', "%{$search}%")
                  ->orWhereHas('customer_relation', function ($cQ) use ($search) {
                      $cQ->where('company', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($status = $request->get('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Company filter
        if ($company = $request->get('company')) {
            $query->whereHas('customer_relation', function ($cQ) use ($company) {
                $cQ->where('company', $company);
            });
        }

        // Type filter
        if ($type = $request->get('type')) {
            if ($type !== 'all') {
                $query->where('type', $type);
            }
        }

        // Date Range filters
        if ($startDate = $request->get('start_date')) {
            $query->whereDate('date', '>=', $startDate);
        }
        if ($endDate = $request->get('end_date')) {
            $query->whereDate('date', '<=', $endDate);
        }

        $perPage = $request->get('per_page', 10);
        $invoices = $query->paginate($perPage);

        return response()->json($invoices);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|integer',
            'customer' => 'required|string',
            'amount' => 'required|numeric',
            'balance' => 'required|numeric',
            'status' => 'nullable|string',
            'date' => 'nullable|date',
            'period' => 'nullable|string',
            'type' => 'nullable|string',
            'remarks' => 'nullable|string',
            'entered_by' => 'nullable|string',
        ]);

        $validated['invoice_code'] = $request->get('invoice_code') ?: 'INV-2026-' . sprintf('%03d', UcInvoice::count() + 3);
        $validated['date'] = ($validated['date'] ?? null) ?: date('Y-m-d');
        $validated['status'] = ($validated['status'] ?? null) ?: ($validated['balance'] <= 0 ? 'Paid' : 'Unpaid');

        $invoice = UcInvoice::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Invoice created successfully!',
            'data' => $invoice
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $invoice = UcInvoice::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'nullable|integer',
            'customer' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'status' => 'nullable|string',
            'date' => 'nullable|date',
            'period' => 'nullable|string',
            'type' => 'nullable|string',
            'remarks' => 'nullable|string',
            'entered_by' => 'nullable|string',
        ]);

        if (isset($validated['balance'])) {
            // Auto update status if balance becomes <= 0
            if ($validated['balance'] <= 0) {
                $validated['status'] = 'Paid';
            }
        }

        $invoice->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Invoice updated successfully!',
            'data' => $invoice
        ]);
    }

    public function show($id)
    {
        $invoice = UcInvoice::with('customer_relation')->findOrFail($id);

        $company = $invoice->customer_relation?->company ?: $invoice->customer;
        $startDate = null;
        $endDate = null;
        if ($invoice->period) {
            $parts = preg_split('/\s+(?:-|to)\s+/', $invoice->period);
            if (count($parts) === 2) {
                $startDate = trim($parts[0]);
                $endDate = trim($parts[1]);
            }
        }

        $startDate = $startDate ?: $invoice->date;
        $endDate = $endDate ?: $invoice->date;

        $customerIds = \App\Models\UmrahCab\UcCustomer::where('company', $company)->pluck('id');

        // Calculate Previous Balance
        $prevBookingsSum = \DB::table('uc_bookings')
            ->whereIn('customer_id', $customerIds)
            ->where('date', '<', $startDate)
            ->where('status', '!=', 'Cancelled')
            ->sum('car_price');

        $prevServicesSum = \DB::table('uc_services')
            ->whereIn('customer_id', $customerIds)
            ->where('date', '<', $startDate)
            ->where('status', '!=', 'Cancelled')
            ->sum('base_price');

        $prevPaymentsSum = \DB::table('uc_payments')
            ->where('company', $company)
            ->where('date', '<', $startDate)
            ->sum('amount');

        $prevBalance = ($prevBookingsSum + $prevServicesSum) - $prevPaymentsSum;

        // Fetch current cycle items
        $bookings = \DB::table('uc_bookings')
            ->whereIn('customer_id', $customerIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', '!=', 'Cancelled')
            ->select('id', 'date', 'booking_code', 'pickup', 'destination', 'car_price', 'status')
            ->get();

        $services = \DB::table('uc_services')
            ->whereIn('customer_id', $customerIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', '!=', 'Cancelled')
            ->select('id', 'date', 'custom_id as service_code', 'name', 'type', 'base_price', 'status')
            ->get();

        $payments = \DB::table('uc_payments')
            ->where('company', $company)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('id', 'date', 'amount', 'method', 'status')
            ->get();

        $activities = \DB::table('uc_audits')
            ->whereIn('customer_id', $customerIds)
            ->orWhere('performed_action', 'like', "%{$company}%")
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'invoice' => $invoice,
                'breakdown' => [
                    'prev_balance' => (float)$prevBalance,
                    'bookings' => $bookings,
                    'bookings_sum' => (float)$bookings->sum('car_price'),
                    'services' => $services,
                    'services_sum' => (float)$services->sum('base_price'),
                    'payments' => $payments,
                    'payments_sum' => (float)$payments->sum('amount'),
                    'cycle_subtotal' => (float)($bookings->sum('car_price') + $services->sum('base_price')),
                    'total_balance_due' => (float)($prevBalance + ($bookings->sum('car_price') + $services->sum('base_price')) - $payments->sum('amount')),
                    'activities' => $activities,
                ]
            ]
        ]);
    }

    public function destroy($id)
    {
        $invoice = UcInvoice::findOrFail($id);
        $invoice->delete();

        return response()->json([
            'success' => true,
            'message' => 'Invoice deleted successfully!'
        ]);
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'company' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => 'required|string',
        ]);

        $company = $request->get('company');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $type = $request->get('type');

        // Fetch customer IDs belonging to this company name
        $customerIds = \App\Models\UmrahCab\UcCustomer::where('company', $company)->pluck('id');

        // 1. Calculate Previous Balance (all items < start_date)
        $prevBookingsSum = \DB::table('uc_bookings')
            ->whereIn('customer_id', $customerIds)
            ->where('date', '<', $startDate)
            ->where('status', '!=', 'Cancelled')
            ->sum('car_price');

        $prevServicesSum = \DB::table('uc_services')
            ->whereIn('customer_id', $customerIds)
            ->where('date', '<', $startDate)
            ->where('status', '!=', 'Cancelled')
            ->sum('base_price');

        $prevPaymentsSum = \DB::table('uc_payments')
            ->where('company', $company)
            ->where('date', '<', $startDate)
            ->sum('amount');

        $prevBalance = ($prevBookingsSum + $prevServicesSum) - $prevPaymentsSum;

        // 2. Fetch Current Cycle Bookings
        $cycleBookings = \DB::table('uc_bookings')
            ->whereIn('customer_id', $customerIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', '!=', 'Cancelled')
            ->select('id', 'date', 'booking_code', 'pickup', 'destination', 'car_price', 'status')
            ->get();

        $cycleBookingsSum = $cycleBookings->sum('car_price');

        // 3. Fetch Current Cycle Services
        $cycleServices = \DB::table('uc_services')
            ->whereIn('customer_id', $customerIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', '!=', 'Cancelled')
            ->select('id', 'date', 'custom_id as service_code', 'name', 'type', 'base_price', 'status')
            ->get();

        $cycleServicesSum = $cycleServices->sum('base_price');

        // 4. Fetch Current Cycle Payments
        $cyclePayments = \DB::table('uc_payments')
            ->where('company', $company)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('id', 'date', 'amount', 'method', 'status')
            ->get();

        $cyclePaymentsSum = $cyclePayments->sum('amount');

        // 5. Audit Trail/Activity History
        $activities = \DB::table('uc_audits')
            ->whereIn('customer_id', $customerIds)
            ->orWhere('performed_action', 'like', "%{$company}%")
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $cycleSubtotal = $cycleBookingsSum + $cycleServicesSum;
        $totalBalanceDue = $prevBalance + $cycleSubtotal - $cyclePaymentsSum;

        return response()->json([
            'success' => true,
            'data' => [
                'company' => $company,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'type' => $type,
                'prev_balance' => (float)$prevBalance,
                'bookings' => $cycleBookings,
                'bookings_sum' => (float)$cycleBookingsSum,
                'services' => $cycleServices,
                'services_sum' => (float)$cycleServicesSum,
                'payments' => $cyclePayments,
                'payments_sum' => (float)$cyclePaymentsSum,
                'cycle_subtotal' => (float)$cycleSubtotal,
                'total_balance_due' => (float)$totalBalanceDue,
                'activities' => $activities,
            ]
        ]);
    }
}
