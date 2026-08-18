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
        $filterCompany = $request->get('company', '');
        $filterTab     = $request->get('tab', 'all'); // all|due_today|overdue|cleared|upcoming

        // ── 1. Latest ledger balance per company ─────────────────────────────
        $latestLedgers = UcLedger::select('company', DB::raw('MAX(id) as max_id'))
            ->groupBy('company');

        $ledgerBalances = UcLedger::joinSub($latestLedgers, 'latest', fn($j) =>
                $j->on('uc_ledgers.id', '=', 'latest.max_id'))
            ->select('uc_ledgers.company', 'uc_ledgers.balance')
            ->pluck('balance', 'company');

        // ── 2. All Invoices (leftJoin uc_customers so company-level invoices are included) ──
        $allInvoices = DB::table('uc_invoices')
            ->leftJoin('uc_customers', 'uc_invoices.customer_id', '=', 'uc_customers.id')
            ->select(
                'uc_invoices.id',
                'uc_invoices.customer as inv_customer',
                'uc_customers.company as cust_company',
                'uc_invoices.amount',
                'uc_invoices.balance',
                'uc_invoices.status',
                'uc_invoices.date',
                'uc_invoices.invoice_code'
            )
            ->get();

        // ── 3. Last payment info per company ─────────────────────────────────
        $lastPaymentIds = UcPayment::select('company', DB::raw('MAX(id) as max_id'))
            ->groupBy('company');

        $lastPayments = UcPayment::joinSub($lastPaymentIds, 'lp', fn($j) =>
                $j->on('uc_payments.id', '=', 'lp.max_id'))
            ->select('uc_payments.company', 'uc_payments.amount as last_pay_amt', 'uc_payments.date as last_pay_date')
            ->get()
            ->keyBy('company');

        // ── 4. Last followup info per company ─────────────────────────────────
        $lastFollowupIds = DB::table('uc_followups')
            ->select('agent', DB::raw('MAX(id) as max_id'))
            ->groupBy('agent');

        $lastFollowups = DB::table('uc_followups')
            ->joinSub($lastFollowupIds, 'lf', fn($j) => $j->on('uc_followups.id', '=', 'lf.max_id'))
            ->select('lf.agent as company', 'uc_followups.date as last_followup', 'uc_followups.notes as followup_remarks', 'uc_followups.status as followup_status')
            ->get()
            ->keyBy('company');

        // ── 5. Last/Next Pickup and Service info per company ────────────────
        $today = now()->toDateString();

        $lastPickups = DB::table('uc_bookings')
            ->leftJoin('uc_customers', 'uc_bookings.customer_id', '=', 'uc_customers.id')
            ->where('uc_bookings.date', '<=', $today)
            ->select('uc_customers.company', 'uc_bookings.full_name', 'uc_bookings.date')
            ->get()
            ->groupBy(function($item) {
                $comp = trim($item->company ?? '');
                return !empty($comp) ? $comp : trim($item->full_name ?? '');
            })
            ->map(fn($group) => $group->max('date'));

        $nextPickups = DB::table('uc_bookings')
            ->leftJoin('uc_customers', 'uc_bookings.customer_id', '=', 'uc_customers.id')
            ->where('uc_bookings.date', '>=', $today)
            ->select('uc_customers.company', 'uc_bookings.full_name', 'uc_bookings.date')
            ->get()
            ->groupBy(function($item) {
                $comp = trim($item->company ?? '');
                return !empty($comp) ? $comp : trim($item->full_name ?? '');
            })
            ->map(fn($group) => $group->min('date'));

        $lastServices = DB::table('uc_services')
            ->leftJoin('uc_customers', 'uc_services.customer_id', '=', 'uc_customers.id')
            ->where('uc_services.date', '<=', $today)
            ->select('uc_customers.company', 'uc_services.name', 'uc_services.date')
            ->get()
            ->groupBy(function($item) {
                $comp = trim($item->company ?? '');
                return !empty($comp) ? $comp : trim($item->name ?? '');
            })
            ->map(fn($group) => $group->max('date'));

        $nextServices = DB::table('uc_services')
            ->leftJoin('uc_customers', 'uc_services.customer_id', '=', 'uc_customers.id')
            ->where('uc_services.date', '>=', $today)
            ->select('uc_customers.company', 'uc_services.name', 'uc_services.date')
            ->get()
            ->groupBy(function($item) {
                $comp = trim($item->company ?? '');
                return !empty($comp) ? $comp : trim($item->name ?? '');
            })
            ->map(fn($group) => $group->min('date'));

        // ── 6. Build per-company rows ─────────────────────────────────────────
        $dbCompanies = UcCompany::orderBy('name')->get();
        $registeredNames = $dbCompanies->pluck('name')->map(fn($n) => trim($n))->toArray();

        // Also gather dynamic company names from payments, ledgers, and customers
        $extraCompanyNames = collect([])
            ->concat(UcPayment::pluck('company'))
            ->concat(UcLedger::pluck('company'))
            ->concat(DB::table('uc_customers')->pluck('company'))
            ->filter()
            ->map(fn($n) => trim($n))
            ->reject(fn($n) => empty($n))
            ->unique(fn($n) => strtolower($n));

        $allCompaniesList = collect($dbCompanies->map(function($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'vouchers' => $c->vouchers,
                'statement_status' => $c->statement_status,
                'remarks' => $c->remarks,
            ];
        })->toArray());

        $syntheticId = 900000;
        foreach ($extraCompanyNames as $extName) {
            $alreadyPresent = false;
            foreach ($registeredNames as $reg) {
                if (strcasecmp($reg, $extName) === 0) {
                    $alreadyPresent = true;
                    break;
                }
            }
            if (!$alreadyPresent) {
                $syntheticId++;
                $allCompaniesList->push([
                    'id' => $syntheticId,
                    'name' => $extName,
                    'vouchers' => 0,
                    'statement_status' => 'Pending',
                    'remarks' => '',
                ]);
            }
        }

        if ($filterCompany) {
            $trimmedFilter = strtolower(trim($filterCompany));
            $allCompaniesList = $allCompaniesList->filter(function($c) use ($trimmedFilter) {
                return strcasecmp(trim($c['name'] ?? ''), $trimmedFilter) === 0;
            });
        }

        $rows = $allCompaniesList->map(function ($compArr) use (
            $allInvoices, $ledgerBalances, $lastPayments,
            $lastFollowups, $lastPickups, $nextPickups,
            $lastServices, $nextServices
        ) {
            $comp = (object) $compArr;
            $name    = $comp->name;
            $trimmed = trim($name);

            // Filter invoices matching this company
            $compInvoices = $allInvoices->filter(function($inv) use ($trimmed) {
                $c1 = trim($inv->cust_company ?? '');
                $c2 = trim($inv->inv_customer ?? '');
                return ($c1 !== '' && strcasecmp($c1, $trimmed) === 0) || ($c2 !== '' && strcasecmp($c2, $trimmed) === 0);
            });

            $totalBiz = (float) $compInvoices->sum('amount');

            $unpaidInvoices = $compInvoices->filter(function($inv) {
                $st = strtolower(trim($inv->status ?? ''));
                return !in_array($st, ['paid', 'completed', 'cleared']);
            });

            $recVW       = (float) $unpaidInvoices->sum('amount');
            $recPW       = (float) $unpaidInvoices->sum('balance');
            $unpaidCount = $unpaidInvoices->count();

            // Match ledger balance
            $ledgerBal = 0;
            foreach ($ledgerBalances as $cName => $bal) {
                if (strcasecmp(trim($cName), $trimmed) === 0) {
                    $ledgerBal = (float) $bal;
                    break;
                }
            }

            // Calculate outstanding loan due for this company
            $outstandingLoan = (float) \App\Models\UmrahCab\UcPayment::where(function($q) use ($trimmed) {
                $q->where('company', $trimmed)
                  ->orWhere('company', 'like', "%{$trimmed}%");
            })
            ->where('method', 'Loan Due')
            ->whereIn('status', ['Pending', 'pending'])
            ->sum('amount');

            // Fallback: If no unpaid invoices exist, calculate booking debt (negative ledger) and add outstanding loan
            if ($recVW == 0) {
                $bookingDebt = $ledgerBal < 0 ? abs($ledgerBal) : 0;
                $recVW = $bookingDebt + $outstandingLoan;
                $recPW = $bookingDebt + $outstandingLoan;
            } else {
                // If unpaid invoices exist, also append the outstanding loan to the total receivable
                $recVW += $outstandingLoan;
                $recPW += $outstandingLoan;
            }

            // Match last payment
            $lastPay = null;
            foreach ($lastPayments as $cName => $lp) {
                if (strcasecmp(trim($cName), $trimmed) === 0) {
                    $lastPay = $lp;
                    break;
                }
            }

            // Match last followup
            $lastFlp = null;
            foreach ($lastFollowups as $cName => $lf) {
                if (strcasecmp(trim($cName), $trimmed) === 0) {
                    $lastFlp = $lf;
                    break;
                }
            }

            // Parse followup remarks from JSON if applicable
            $remarks = 'No remarks';
            if ($lastFlp && $lastFlp->followup_remarks) {
                $decoded = json_decode($lastFlp->followup_remarks, true);
                $remarks = is_array($decoded) ? ($decoded['remarks'] ?? 'No remarks') : $lastFlp->followup_remarks;
            }

            // Latest invoice details
            $lastInv = $compInvoices->sortByDesc('id')->first();

            // Status: CLEARED if statement_status is 'Done' or if no unpaid receivables and ledger balance <= 0
            $isStatementDone = (isset($comp->statement_status) && strcasecmp(trim($comp->statement_status), 'Done') === 0);
            $status = ($isStatementDone || ($recVW <= 0 && $recPW <= 0 && $unpaidCount == 0 && $ledgerBal <= 0)) ? 'CLEARED' : 'UNPAID';

            return [
                'id'               => $comp->id,
                'vouchers_lock'    => (bool) $comp->vouchers,
                'company'          => $name,
                'status'           => $status,
                'last_inv_amt'     => (float) ($lastInv->amount        ?? 0),
                'inv_period'       => $lastInv->invoice_code           ?? 'N/A',
                'last_followup'    => $lastFlp->last_followup          ?? null,
                'followup_remarks' => $remarks,
                'total_business'   => $totalBiz,
                'last_pay_date'    => $lastPay->last_pay_date          ?? null,
                'last_pay_amt'     => (float) ($lastPay->last_pay_amt  ?? 0),
                'last_pickup'      => $lastPickups[$trimmed]           ?? $lastPickups[$name]  ?? null,
                'next_pickup'      => $nextPickups[$trimmed]           ?? $nextPickups[$name]  ?? null,
                'last_service'     => $lastServices[$trimmed]          ?? $lastServices[$name] ?? null,
                'next_service'     => $nextServices[$trimmed]          ?? $nextServices[$name] ?? null,
                'total_rec_vw'     => $recVW,
                'total_rec_pw'     => $recPW,
                'unpaid_count'     => $unpaidCount,
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
            $rows = $rows->filter(fn($r) => $r['total_rec_vw'] > 0 || $r['ledger_balance'] > 0 || $r['status'] === 'UNPAID');
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

