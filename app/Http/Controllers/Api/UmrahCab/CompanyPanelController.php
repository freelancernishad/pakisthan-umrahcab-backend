<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UmrahCab\UcBooking;
use App\Models\UmrahCab\UcCustomer;
use App\Models\UmrahCab\UcInvoice;
use App\Models\UmrahCab\UcLedger;
use App\Models\UmrahCab\UcPayment;

class CompanyPanelController extends Controller
{
    private function getCompany()
    {
        return Auth::guard('company')->user();
    }

    public function dashboardSummary()
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customerIds = UcCustomer::where('company', $company->name)->pluck('id');
        $bookingsQuery = UcBooking::whereIn('customer_id', $customerIds);

        $totalBookings = $bookingsQuery->count();
        $activeBookings = (clone $bookingsQuery)->where('status', 'Active Dispatch')->count();
        $confirmedBookings = (clone $bookingsQuery)->where('status', 'Confirmed Booking')->count();
        $pendingBookings = (clone $bookingsQuery)->where('status', 'Pending Check')->count();
        
        // Latest bookings
        $latestBookings = $bookingsQuery->orderBy('id', 'desc')->take(10)->get();

        // Calculate corporate ledger summary
        $ledgerTransactions = UcLedger::where('company', $company->name)->orderBy('id', 'desc')->get();
        $totalDebit = $ledgerTransactions->sum('debit');
        $totalCredit = $ledgerTransactions->sum('credit');
        // Get the latest balance from the most recent ledger entry, fallback to 0
        $currentBalance = $ledgerTransactions->first() ? $ledgerTransactions->first()->balance : 0;

        // Pending payments details
        $pendingPaymentsQuery = UcPayment::where('company', $company->name)
            ->whereNotIn('status', ['Approved', 'Success', 'Verified']);
        $pendingPaymentsCount = $pendingPaymentsQuery->count();
        $pendingPaymentsTotal = $pendingPaymentsQuery->sum('amount');
        $pendingPaymentsList = $pendingPaymentsQuery->orderBy('id', 'desc')->take(10)->get();

        return response()->json([
            'company' => $company,
            'total_bookings' => $totalBookings,
            'active_bookings' => $activeBookings,
            'confirmed_bookings' => $confirmedBookings,
            'pending_bookings' => $pendingBookings,
            'latest_bookings' => $latestBookings,
            'ledger_summary' => [
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'current_balance' => $currentBalance
            ],
            'pending_payments_count' => $pendingPaymentsCount,
            'pending_payments_total' => $pendingPaymentsTotal,
            'pending_payments_list' => $pendingPaymentsList
        ]);
    }

    public function bookings(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customerIds = UcCustomer::where('company', $company->name)->pluck('id');
        $search = $request->query('search');
        $query = UcBooking::whereIn('customer_id', $customerIds)->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        $filter = $request->query('filter');
        if ($filter) {
            $today = \Carbon\Carbon::today()->toDateString();
            if ($filter === 'cancelled') {
                $query->where(function($q) {
                    $q->where('status', 'like', '%cancel%')
                      ->orWhere('status', 'like', '%cancelled%');
                });
            } elseif ($filter === 'current') {
                $query->where(function($q) use ($today) {
                    $q->where('date', '=', $today)
                      ->orWhere(function($sub) use ($today) {
                          $sub->where('date', '<=', $today)
                              ->where(function($s) {
                                  $s->where('status', 'like', '%dispatch%')
                                    ->orWhere('status', 'like', '%pending%')
                                    ->orWhere('status', 'like', '%confirm%');
                              });
                      });
                })->where('status', 'not like', '%cancel%')
                  ->where('status', 'not like', '%completed%');
            } elseif ($filter === 'upcoming') {
                $query->where('date', '>', $today)
                      ->where('status', 'not like', '%cancel%')
                      ->where('status', 'not like', '%completed%');
            }
        }

        if ($request->has('page')) {
            $perPage = $request->query('per_page', 10);
            return response()->json($query->paginate($perPage));
        }

        return response()->json($query->get());
    }

    public function customers(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $search = $request->query('search');
        $query = UcCustomer::where('company', $company->name)->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('custom_id', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%");
            });
        }

        return response()->json($query->get());
    }

    public function invoices(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customerIds = UcCustomer::where('company', $company->name)->pluck('id');
        $query = UcInvoice::whereIn('customer_id', $customerIds)->orderBy('id', 'desc');

        return response()->json($query->get());
    }

    public function ledgers(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $query = UcLedger::where('company', $company->name)->orderBy('id', 'desc');

        return response()->json($query->get());
    }

    public function payments(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $query = UcPayment::where('company', $company->name)->orderBy('id', 'desc');

        return response()->json($query->get());
    }

    public function createCustomer(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'contact' => 'nullable|string',
        ]);

        $validated['company'] = $company->name;

        $count = UcCustomer::count() + 1;
        $validated['custom_id'] = "#CST-{$count}";
        $validated['registered_by'] = 'B2B Agent (' . $company->name . ')';
        $validated['last_update'] = 'No edits';

        $customer = UcCustomer::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Customer registered successfully!',
            'data' => $customer
        ], 201);
    }
}
