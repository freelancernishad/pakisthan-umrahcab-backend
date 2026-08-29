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
        $query = UcBooking::whereIn('customer_id', $customerIds)->with('driver')->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
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
        $query = UcCustomer::with(['bookings', 'flights', 'trains'])->where('company', $company->name)->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('custom_id', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%");
            });
        }

        return response()->json($query->get());
    }

    public function customerDetails($id)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customer = UcCustomer::where('company', $company->name)
            ->where(function($q) use ($id) {
                $q->where('id', $id)
                  ->orWhere('custom_id', $id);
            })
            ->firstOrFail();

        $bookings = \App\Models\UmrahCab\UcBooking::where('customer_id', $customer->id)->get();
        $services = \App\Models\UmrahCab\UcService::where('customer_id', $customer->id)->get();
        $flights = \App\Models\UmrahCab\UcFlight::where('customer_id', $customer->id)->get();
        $trains = \App\Models\UmrahCab\UcTrain::where('customer_id', $customer->id)->get();
        $hotels = \App\Models\UmrahCab\UcHotel::where('customer_id', $customer->id)->get();

        return response()->json([
            'customer' => $customer,
            'bookings' => $bookings,
            'services' => $services,
            'flights' => $flights,
            'trains' => $trains,
            'hotels' => $hotels
        ]);
    }



    public function invoices(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customerIds = UcCustomer::where('company', $company->name)->pluck('id');
        $query = UcInvoice::where(function ($q) use ($customerIds, $company) {
            $q->whereIn('customer_id', $customerIds)
              ->orWhere('customer', $company->name);
        })->orderBy('id', 'desc');

        return response()->json([
            'invoices' => $query->get(),
            'company_info' => [
                'id' => $company->id,
                'name' => $company->name,
                'invoice_allowed' => is_null($company->invoice) ? true : (bool)$company->invoice,
            ]
        ]);
    }

    public function calculateInvoice(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => 'nullable|string',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $customerIds = UcCustomer::where('company', $company->name)->pluck('id');

        $bookings = \DB::table('uc_bookings')
            ->whereIn('customer_id', $customerIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', '!=', 'Cancelled')
            ->get();

        $services = \DB::table('uc_services')
            ->whereIn('customer_id', $customerIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', '!=', 'Cancelled')
            ->get();

        $payments = \DB::table('uc_payments')
            ->where('company', $company->name)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $cycleBookingsSum = (float)$bookings->sum('car_price');
        $cycleServicesSum = (float)$services->sum('base_price');
        $cyclePaymentsSum = (float)$payments->sum('amount');
        $cycleSubtotal = $cycleBookingsSum + $cycleServicesSum;
        $totalBalanceDue = max(0, $cycleSubtotal - $cyclePaymentsSum);

        return response()->json([
            'success' => true,
            'data' => [
                'company' => $company->name,
                'invoice_allowed' => is_null($company->invoice) ? true : (bool)$company->invoice,
                'period' => "{$startDate} to {$endDate}",
                'bookings_count' => $bookings->count(),
                'bookings_sum' => $cycleBookingsSum,
                'services_count' => $services->count(),
                'services_sum' => $cycleServicesSum,
                'payments_sum' => $cyclePaymentsSum,
                'subtotal' => $cycleSubtotal,
                'balance_due' => $totalBalanceDue,
            ]
        ]);
    }

    public function createInvoice(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $isAllowed = is_null($company->invoice) ? true : (bool)$company->invoice;
        if (!$isAllowed) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice creation is not allowed for your account. Please contact admin to enable invoice creation permission.'
            ], 403);
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];
        $customerIds = UcCustomer::where('company', $company->name)->pluck('id');
        $primaryCustomerId = $customerIds->first() ?: null;

        $bookingsSum = (float)\DB::table('uc_bookings')
            ->whereIn('customer_id', $customerIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', '!=', 'Cancelled')
            ->sum('car_price');

        $servicesSum = (float)\DB::table('uc_services')
            ->whereIn('customer_id', $customerIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', '!=', 'Cancelled')
            ->sum('base_price');

        $paymentsSum = (float)\DB::table('uc_payments')
            ->where('company', $company->name)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $subtotal = $bookingsSum + $servicesSum;
        $balance = max(0, $subtotal - $paymentsSum);

        $invoiceCode = 'INV-2026-' . sprintf('%03d', UcInvoice::count() + 3);

        $invoice = UcInvoice::create([
            'customer_id' => $primaryCustomerId,
            'invoice_code' => $invoiceCode,
            'customer' => $company->name,
            'date' => date('Y-m-d'),
            'period' => "{$startDate} to {$endDate}",
            'amount' => $subtotal,
            'balance' => $balance,
            'status' => $balance <= 0 ? 'Paid' : 'Unpaid',
            'type' => $validated['type'] ?? 'VW',
            'remarks' => $validated['remarks'] ?? 'Generated from Agent Panel',
            'entered_by' => 'Agent: ' . $company->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invoice generated successfully!',
            'data' => $invoice
        ], 201);
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

    public function clientLedger(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $companyName = trim($company->name);

        $customerIds = UcCustomer::where('company', 'LIKE', "%{$companyName}%")
            ->orWhereRaw('LOWER(company) = ?', [strtolower($companyName)])
            ->pluck('id');

        $query = UcBooking::where(function ($q) use ($customerIds, $companyName) {
            $q->whereIn('customer_id', $customerIds)
              ->orWhereHas('customer', function ($cq) use ($companyName) {
                  $cq->where('company', 'LIKE', "%{$companyName}%");
              });
        })->with('customer');


        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
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

        $totalBilled = $bookings->sum(function ($b) { return (float) ($b->car_price ?? 0); });
        $totalReceived = $bookings->sum(function ($b) { return (float) ($b->received_amount ?? 0); });
        $totalPending = $bookings->sum(function ($b) { return (float) ($b->pending_amount ?? 0); });

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

    public function updateBookingPayment(Request $request, $id)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $booking = UcBooking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $validated = $request->validate([
            'received_amount' => 'required|numeric|min:0',
        ]);

        $received = (float) $validated['received_amount'];
        $carPrice = (float) $booking->car_price;
        $pending = max(0, $carPrice - $received);

        $booking->received_amount = $received;
        $booking->pending_amount = $pending;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Client payment updated successfully!',
            'data' => $booking
        ]);
    }




    public function payments(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $status = $request->query('status');
        $query = UcPayment::where('company', $company->name)->orderBy('id', 'desc');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

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
            'phone' => 'nullable|string',
            'secondary_phone' => 'nullable|string',
            'alternative_phone' => 'nullable|string',
            'email' => 'nullable|email',
            'passport_no' => 'nullable|string',
            'hotel_info' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['company'] = $company->name;

        // Fallback: fill client's last message if notes/remarks are empty
        if (empty($validated['notes'])) {
            $lastChatMessage = \App\Models\UmrahCab\UcChatMessage::where('company_id', $company->id)
                ->where('sender_type', 'company')
                ->orderBy('id', 'desc')
                ->first();
            if ($lastChatMessage && !empty($lastChatMessage->message)) {
                $validated['notes'] = $lastChatMessage->message;
            }
        }

        if (empty($validated['contact'])) {
            $phones = collect([$request->phone, $request->secondary_phone, $request->alternative_phone])->filter()->implode(' / ');
            $emailInfo = $request->email ? " | Email: {$request->email}" : "";
            $passportInfo = $request->passport_no ? " | Passport: {$request->passport_no}" : "";
            $hotelInfo = $request->hotel_info ? " | Hotel: {$request->hotel_info}" : "";
            $notesInfo = (isset($validated['notes']) && $validated['notes']) ? " | Notes: {$validated['notes']}" : "";
            $validated['contact'] = trim("{$phones}{$emailInfo}{$passportInfo}{$hotelInfo}{$notesInfo}") ?: 'N/A';
        }

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



    public function uploadDocument(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx|max:10240', // 10MB limit
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '_' . time() . '.' . $extension;
            
            // Store locally in public/uploads/documents/
            $file->move(public_path('uploads/documents'), $filename);
            $url = asset('uploads/documents/' . $filename);

            return response()->json([
                'success' => true,
                'url' => $url,
                'name' => $originalName,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
    }

    public function createPayment(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'method' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'transaction_ref' => 'nullable|string',
            'proof_details' => 'nullable|string',
            'proof_file' => 'nullable',
        ]);

        $validated['company'] = $company->name;
        $validated['custom_id'] = 'PAY-' . rand(9000, 9999);
        $validated['date'] = date('Y-m-d');
        $validated['status'] = 'Pending';

        if ($request->hasFile('proof_file')) {
            $file = $request->file('proof_file');
            $filename = 'proof_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Upload to S3 if configured, otherwise fall back to local disk
            if (config('filesystems.disks.s3.key') && config('filesystems.disks.s3.secret') && config('filesystems.disks.s3.bucket')) {
                $path = \Illuminate\Support\Facades\Storage::disk('s3')->putFileAs('proofs', $file, $filename);
                $proofPath = \Illuminate\Support\Facades\Storage::disk('s3')->url($path);
            } else {
                $file->move(public_path('uploads/proofs'), $filename);
                $proofPath = '/uploads/proofs/' . $filename;
            }
            
            $validated['proof_file'] = $proofPath;
        }

        $payment = UcPayment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'General payment logged successfully!',
            'data' => $payment
        ], 201);
    }

    public function getDocuments(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customerId = $request->query('customer_id');
        if (!$customerId) {
            return response()->json(['message' => 'Customer ID is required'], 400);
        }

        // Verify customer belongs to the company
        $customer = UcCustomer::where('company', $company->name)->where('id', $customerId)->firstOrFail();

        $documents = \App\Models\UmrahCab\UcDocument::where('customer_id', $customer->id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($documents);
    }

    public function storeDocument(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:uc_customers,id',
            'title' => 'nullable|string',
            'document_file' => 'required|file|mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx|max:10240', // 10MB max
        ]);

        // Verify customer belongs to the company
        $customer = UcCustomer::where('company', $company->name)->where('id', $validated['customer_id'])->firstOrFail();

        $file = $request->file('document_file');
        $originalName = $file->getClientOriginalName();
        $title = $validated['title'] ?: pathinfo($originalName, PATHINFO_FILENAME);
        $fileType = $file->getClientOriginalExtension();
        $filename = 'doc_' . time() . '_' . uniqid() . '.' . $fileType;

        $file->move(public_path('uploads/documents'), $filename);
        $filePath = '/uploads/documents/' . $filename;

        $document = \App\Models\UmrahCab\UcDocument::create([
            'customer_id' => $customer->id,
            'title' => $title,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'uploaded_by' => $company->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully!',
            'data' => $document
        ], 201);
    }

    public function deleteDocument($id)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $document = \App\Models\UmrahCab\UcDocument::findOrFail($id);

        // Verify customer of the document belongs to the company
        $customer = UcCustomer::where('company', $company->name)->where('id', $document->customer_id)->firstOrFail();

        // Delete from public folder
        $filePath = public_path($document->file_path);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully!'
        ]);
    }

    public function uploadLogo(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'logo_path' => 'required|string',
        ]);

        $logoPath = $request->input('logo_path');
        if (str_starts_with($logoPath, 'data:')) {
            $ext = 'png';
            if (preg_match('/^data:image\/([^;]+);base64,/i', $logoPath, $matches)) {
                $rawMime = strtolower($matches[1]);
                if (str_contains($rawMime, 'jpeg') || str_contains($rawMime, 'jpg')) {
                    $ext = 'jpg';
                } elseif (str_contains($rawMime, 'webp')) {
                    $ext = 'webp';
                } elseif (str_contains($rawMime, 'gif')) {
                    $ext = 'gif';
                } elseif (str_contains($rawMime, 'svg')) {
                    $ext = 'svg';
                }
            }

            $base64Data = substr($logoPath, strpos($logoPath, ',') + 1);
            $data = base64_decode($base64Data);
            if ($data !== false && !empty($data)) {
                $fileName = 'logo_' . time() . '_' . rand(1000, 9999) . '.' . $ext;

                // Check if AWS S3 is configured, upload to S3; otherwise save to local uploads with cPanel/public_html sync
                if (config('filesystems.disks.s3.key') && config('filesystems.disks.s3.secret') && config('filesystems.disks.s3.bucket')) {
                    $s3Path = 'logos/' . $fileName;
                    \Illuminate\Support\Facades\Storage::disk('s3')->put($s3Path, $data, 'public');
                    $logoPath = \Illuminate\Support\Facades\Storage::disk('s3')->url($s3Path);
                } else {
                    $uploadDir = public_path('uploads');
                    if (!file_exists($uploadDir)) {
                        @mkdir($uploadDir, 0755, true);
                    }
                    
                    $fullPath = $uploadDir . '/' . $fileName;
                    file_put_contents($fullPath, $data);
                    @chmod($fullPath, 0644);

                    // cPanel / Shared hosting sync: if DOCUMENT_ROOT or public_html is different from public_path
                    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') : null;
                    if ($docRoot && realpath($docRoot) !== realpath(public_path())) {
                        $docRootTarget = $docRoot . '/uploads';
                        if (!file_exists($docRootTarget)) {
                            @mkdir($docRootTarget, 0755, true);
                        }
                        @copy($fullPath, $docRootTarget . '/' . $fileName);
                        @chmod($docRootTarget . '/' . $fileName, 0644);
                    }

                    $publicHtmlDir = base_path('public_html/uploads');
                    if (file_exists(base_path('public_html')) && realpath(base_path('public_html')) !== realpath(public_path())) {
                        if (!file_exists($publicHtmlDir)) {
                            @mkdir($publicHtmlDir, 0755, true);
                        }
                        @copy($fullPath, $publicHtmlDir . '/' . $fileName);
                        @chmod($publicHtmlDir . '/' . $fileName, 0644);
                    }

                    $logoPath = 'uploads/' . $fileName;
                }
            }
        }

        $company->logo_path = $logoPath;
        $company->save();

        return response()->json([
            'success' => true,
            'message' => 'Company logo updated successfully!',
            'company' => $company
        ]);
    }
}
