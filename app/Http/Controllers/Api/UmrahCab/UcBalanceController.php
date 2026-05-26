<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcCompany;
use App\Models\UmrahCab\UcLedger;
use App\Models\UmrahCab\UcPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UcBalanceController extends Controller
{
    public function summary(Request $request)
    {
        $filterCompany    = $request->get('company', '');
        $filterTab        = $request->get('tab', 'all'); // all|due_today|overdue|cleared|paid_advance|upcoming

        // ── 1. Latest ledger balance per company ─────────────────────────────
        $latestLedgers = UcLedger::select('company', DB::raw('MAX(id) as max_id'))
            ->groupBy('company');

        $ledgerBalances = UcLedger::joinSub($latestLedgers, 'latest', fn($j) =>
                $j->on('uc_ledgers.id', '=', 'latest.max_id'))
            ->select('uc_ledgers.company', 'uc_ledgers.balance')
            ->pluck('balance', 'company');

        // ── 2. Unpaid invoice totals + last invoice info per company (via customer join) ─
        $invoiceStats = DB::table('uc_invoices')
            ->join('uc_customers', 'uc_invoices.customer_id', '=', 'uc_customers.id')
            ->select(
                'uc_customers.company',
                DB::raw('SUM(uc_invoices.amount) as total_business'),
                DB::raw('SUM(CASE WHEN uc_invoices.status NOT IN ("Paid","paid","completed") THEN uc_invoices.amount ELSE 0 END) as total_receivable_vw'),
                DB::raw('SUM(CASE WHEN uc_invoices.status NOT IN ("Paid","paid","completed") THEN uc_invoices.balance ELSE 0 END) as total_receivable_pw'),
                DB::raw('COUNT(CASE WHEN uc_invoices.status NOT IN ("Paid","paid","completed") THEN 1 END) as unpaid_count')
            )
            ->groupBy('uc_customers.company')
            ->get()
            ->keyBy('company');

        // ── 3. Last invoice details per company ───────────────────────────────
        $lastInvoiceIds = DB::table('uc_invoices')
            ->join('uc_customers', 'uc_invoices.customer_id', '=', 'uc_customers.id')
            ->select('uc_customers.company', DB::raw('MAX(uc_invoices.id) as max_id'))
            ->groupBy('uc_customers.company');

        $lastInvoices = DB::table('uc_invoices')
            ->joinSub($lastInvoiceIds, 'li', fn($j) => $j->on('uc_invoices.id', '=', 'li.max_id'))
            ->select('li.company', 'uc_invoices.amount as last_inv_amt', 'uc_invoices.date as last_inv_date', 'uc_invoices.invoice_code as inv_period')
            ->get()
            ->keyBy('company');

        // ── 4. Last payment info per company ─────────────────────────────────
        $lastPaymentIds = UcPayment::select('company', DB::raw('MAX(id) as max_id'))
            ->groupBy('company');

        $lastPayments = UcPayment::joinSub($lastPaymentIds, 'lp', fn($j) =>
                $j->on('uc_payments.id', '=', 'lp.max_id'))
            ->select('uc_payments.company', 'uc_payments.amount as last_pay_amt', 'uc_payments.date as last_pay_date')
            ->get()
            ->keyBy('company');

        // ── 5. Last followup info per company (agent = company name in followups) ─
        $lastFollowupIds = DB::table('uc_followups')
            ->select('agent', DB::raw('MAX(id) as max_id'))
            ->groupBy('agent');

        $lastFollowups = DB::table('uc_followups')
            ->joinSub($lastFollowupIds, 'lf', fn($j) => $j->on('uc_followups.id', '=', 'lf.max_id'))
            ->select('lf.agent as company', 'uc_followups.date as last_followup', 'uc_followups.notes as followup_remarks', 'uc_followups.status as followup_status')
            ->get()
            ->keyBy('company');

        // ── 5a. Last/Next Pickup and Service info per company ────────────────
        $today = now()->toDateString();

        $lastPickups = DB::table('uc_bookings')
            ->join('uc_customers', 'uc_bookings.customer_id', '=', 'uc_customers.id')
            ->where('uc_bookings.date', '<=', $today)
            ->select('uc_customers.company', DB::raw('MAX(uc_bookings.date) as date'))
            ->groupBy('uc_customers.company')
            ->pluck('date', 'company');

        $nextPickups = DB::table('uc_bookings')
            ->join('uc_customers', 'uc_bookings.customer_id', '=', 'uc_customers.id')
            ->where('uc_bookings.date', '>=', $today)
            ->select('uc_customers.company', DB::raw('MIN(uc_bookings.date) as date'))
            ->groupBy('uc_customers.company')
            ->pluck('date', 'company');

        $lastServices = DB::table('uc_services')
            ->join('uc_customers', 'uc_services.customer_id', '=', 'uc_customers.id')
            ->where('uc_services.date', '<=', $today)
            ->select('uc_customers.company', DB::raw('MAX(uc_services.date) as date'))
            ->groupBy('uc_customers.company')
            ->pluck('date', 'company');

        $nextServices = DB::table('uc_services')
            ->join('uc_customers', 'uc_services.customer_id', '=', 'uc_customers.id')
            ->where('uc_services.date', '>=', $today)
            ->select('uc_customers.company', DB::raw('MIN(uc_services.date) as date'))
            ->groupBy('uc_customers.company')
            ->pluck('date', 'company');

        // ── 6. Build per-company rows ─────────────────────────────────────────
        $companiesQuery = UcCompany::orderBy('name');
        if ($filterCompany) {
            $companiesQuery->where('name', $filterCompany);
        }
        $companies = $companiesQuery->get();

        $rows = $companies->map(function ($comp) use (
            $ledgerBalances, $invoiceStats, $lastInvoices,
            $lastPayments, $lastFollowups, $lastPickups,
            $nextPickups, $lastServices, $nextServices
        ) {
            $name    = $comp->name;
            $inv     = $invoiceStats[$name]   ?? null;
            $lastInv = $lastInvoices[$name]   ?? null;
            $lastPay = $lastPayments[$name]   ?? null;
            $lastFlp = $lastFollowups[$name]  ?? null;

            $ledgerBal  = (float) ($ledgerBalances[$name] ?? 0);
            $totalBiz   = (float) ($inv->total_business        ?? 0);
            $recVW      = (float) ($inv->total_receivable_vw   ?? 0);
            $recPW      = (float) ($inv->total_receivable_pw   ?? 0);
            $unpaid     = (int)   ($inv->unpaid_count          ?? 0);

            // Parse followup remarks from JSON if applicable
            $remarks = 'No remarks';
            if ($lastFlp && $lastFlp->followup_remarks) {
                $decoded = json_decode($lastFlp->followup_remarks, true);
                $remarks = is_array($decoded) ? ($decoded['remarks'] ?? 'No remarks') : $lastFlp->followup_remarks;
            }

            // Company status: CLEARED = no unpaid invoices / no receivable
            $status = ($recVW == 0 && $unpaid == 0) ? 'CLEARED' : 'UNPAID';

            return [
                'id'               => $comp->id,
                'vouchers_lock'    => (bool) $comp->vouchers,
                'company'          => $name,
                'status'           => $status,
                'last_inv_amt'     => (float) ($lastInv->last_inv_amt  ?? 0),
                'inv_period'       => $lastInv->inv_period             ?? 'N/A',
                'last_followup'    => $lastFlp->last_followup          ?? null,
                'followup_remarks' => $remarks,
                'total_business'   => $totalBiz,
                'last_pay_date'    => $lastPay->last_pay_date          ?? null,
                'last_pay_amt'     => (float) ($lastPay->last_pay_amt  ?? 0),
                'last_pickup'      => $lastPickups[$name]              ?? null,
                'next_pickup'      => $nextPickups[$name]              ?? null,
                'last_service'     => $lastServices[$name]             ?? null,
                'next_service'     => $nextServices[$name]             ?? null,
                'total_rec_vw'     => $recVW,
                'total_rec_pw'     => $recPW,
                'unpaid_count'     => $unpaid,
                'ledger_balance'   => $ledgerBal,
                'statement_status' => $comp->statement_status          ?? 'Pending',
                'company_remarks'  => $comp->remarks                   ?? '',
            ];
        });

        // ── 7. Tab filter ─────────────────────────────────────────────────────
        $today = now()->toDateString();
        $upcoming = now()->addDays(7)->toDateString();

        if ($filterTab === 'due_today') {
            $rows = $rows->filter(fn($r) => $r['last_inv_amt'] > 0 && $r['status'] === 'UNPAID');
        } elseif ($filterTab === 'overdue') {
            $rows = $rows->filter(fn($r) => $r['total_rec_vw'] > 0);
        } elseif ($filterTab === 'cleared') {
            $rows = $rows->filter(fn($r) => $r['status'] === 'CLEARED');
        } elseif ($filterTab === 'upcoming') {
            $rows = $rows->filter(fn($r) => $r['last_pay_date'] >= $today && $r['last_pay_date'] <= $upcoming);
        }

        // ── 8. Grand totals ───────────────────────────────────────────────────
        $totals = [
            'total_business'   => $rows->sum('total_business'),
            'total_rec_vw'     => $rows->sum('total_rec_vw'),
            'total_rec_pw'     => $rows->sum('total_rec_pw'),
        ];

        return response()->json([
            'rows'   => $rows->values(),
            'totals' => $totals,
        ]);
    }
}
