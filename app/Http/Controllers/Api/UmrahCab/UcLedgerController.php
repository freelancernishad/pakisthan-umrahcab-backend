<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcLedger;
use Illuminate\Http\Request;

class UcLedgerController extends Controller
{
    public function index()
    {
        return response()->json(UcLedger::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string',
            'description' => 'required|string',
            'debit' => 'nullable|numeric',
            'credit' => 'nullable|numeric',
        ]);

        $debit = $validated['debit'] ?? 0;
        $credit = $validated['credit'] ?? 0;

        // Fetch last balance to calculate next balance
        $lastLedger = UcLedger::where('company', $validated['company'])->orderBy('id', 'desc')->first();
        $lastBalance = $lastLedger ? $lastLedger->balance : 0;
        $newBalance = $lastBalance + $credit - $debit;

        $validated['custom_id'] = 'LED-' . rand(1000, 9999);
        $validated['date'] = date('Y-m-d');
        $validated['balance'] = $newBalance;

        $ledger = UcLedger::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ledger adjustment created successfully!',
            'data' => $ledger
        ], 201);
    }

    public function directClientsLedger(Request $request)
    {
        $registeredCompanies = \App\Models\UmrahCab\UcCompany::pluck('company_name')->filter()->toArray();

        $query = \App\Models\UmrahCab\UcBooking::with('customer')
            ->where(function ($q) use ($registeredCompanies) {
                $q->whereNull('customer_id')
                  ->orWhereDoesntHave('customer')
                  ->orWhereHas('customer', function ($cq) use ($registeredCompanies) {
                      $cq->whereNull('company')
                        ->orWhere('company', '')
                        ->orWhere('company', 'Direct')
                        ->orWhere('company', 'None');
                      if (!empty($registeredCompanies)) {
                          $cq->orWhereNotIn('company', $registeredCompanies);
                      }
                  });
            });

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->where('date', '<=', $request->end_date);
        }

        $bookings = $query->orderBy('id', 'desc')->get();

        // Calculate dynamic values for each booking
        $bookings->transform(function ($b) {
            $b->calculated_pending = max(0, (float)($b->car_price ?? 0) - (float)($b->received_amount ?? 0));
            return $b;
        });

        $totalBilled = $bookings->sum(function ($b) { return (float) ($b->car_price ?? 0); });
        $totalReceived = $bookings->sum(function ($b) { return (float) ($b->received_amount ?? 0); });
        $totalPending = $bookings->sum('calculated_pending');

        return response()->json([
            'success' => true,
            'summary' => [
                'total_bookings' => $bookings->count(),
                'total_billed' => $totalBilled,
                'total_received' => $totalReceived,
                'total_pending' => $totalPending,
            ],
            'data' => $bookings
        ]);
    }
}


