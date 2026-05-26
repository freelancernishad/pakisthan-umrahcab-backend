<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcBooking;
use App\Models\UmrahCab\UcPayment;
use App\Models\UmrahCab\UcCompany;
use App\Models\UmrahCab\UcLedger;
use Illuminate\Http\Request;

class UcPerformanceController extends Controller
{
    public function index()
    {
        // 1. Get Top Summary Metrics
        $totalBookings = UcBooking::count();
        
        $activeRoutes = UcBooking::select('pickup', 'destination')
            ->distinct()
            ->get()
            ->count();
        
        $verifiedPayments = UcPayment::where('status', 'Verified')
            ->orWhere('status', 'Completed')
            ->sum('amount');
            
        $totalTurnover = UcBooking::sum('car_price');

        // 2. Get Branch Performance Rows
        $companies = UcCompany::all();
        $branches = [];

        foreach ($companies as $company) {
            $compName = $company->name;

            // Join bookings with customers to filter by company name
            $bookingsQuery = UcBooking::join('uc_customers', 'uc_bookings.customer_id', '=', 'uc_customers.id')
                ->where('uc_customers.company', $compName);

            $totalB = (clone $bookingsQuery)->count();
            
            $completedB = (clone $bookingsQuery)
                ->whereIn('uc_bookings.status', ['Completed', 'Confirmed', 'Dispatched'])
                ->count();
                
            $cancelledB = (clone $bookingsQuery)
                ->where('uc_bookings.status', 'Cancelled')
                ->count();
            
            $revenue = (clone $bookingsQuery)->sum('uc_bookings.car_price');

            // Get latest ledger balance for company
            $latestLedger = UcLedger::where('company', $compName)
                ->orderBy('id', 'desc')
                ->first();
            $pendingBalance = $latestLedger ? $latestLedger->balance : 0;

            $branches[] = [
                'company' => $compName,
                'total_bookings' => $totalB,
                'completed_bookings' => $completedB,
                'cancelled_bookings' => $cancelledB,
                'total_revenue' => (float)$revenue,
                'pending_balance' => (float)$pendingBalance,
            ];
        }

        return response()->json([
            'summary' => [
                'total_bookings' => $totalBookings,
                'active_routes' => $activeRoutes,
                'verified_payments' => (float)$verifiedPayments,
                'total_turnover' => (float)$totalTurnover,
            ],
            'branches' => $branches
        ]);
    }
}
