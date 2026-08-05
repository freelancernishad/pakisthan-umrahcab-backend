<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcIndividualOrder;
use App\Models\UmrahCab\UcInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UcIndividualOrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $search = $request->query('search', '');

        $query = UcIndividualOrder::with('invoice')->orderBy('created_at', 'desc');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%")
                  ->orWhere('pickup', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate($perPage);
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pickup' => 'required|string',
            'destination' => 'required|string',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|string',
            'passengers' => 'required|string',
            'car_type' => 'required|string',
            'car_price' => 'required|numeric',
            'full_name' => 'required|string',
            'email' => 'required|email',
            'whatsapp' => 'required|string',
            'flight_no' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            // Generate order code
            $orderCode = 'UCO-' . strtoupper(Str::random(8));
            while (UcIndividualOrder::where('order_code', $orderCode)->exists()) {
                $orderCode = 'UCO-' . strtoupper(Str::random(8));
            }

            // Create Order
            $order = UcIndividualOrder::create(array_merge($validated, [
                'order_code' => $orderCode,
                'status' => 'Pending',
                'payment_status' => 'Pending'
            ]));

            // Generate invoice code
            $invoiceCode = 'UCI-' . strtoupper(Str::random(8));
            while (UcInvoice::where('invoice_code', $invoiceCode)->exists()) {
                $invoiceCode = 'UCI-' . strtoupper(Str::random(8));
            }

            // Create linked Invoice
            $invoice = UcInvoice::create([
                'individual_order_id' => $order->id,
                'invoice_code' => $invoiceCode,
                'customer' => $order->full_name,
                'date' => $order->date->format('Y-m-d'),
                'period' => 'One-time',
                'amount' => $order->car_price,
                'balance' => $order->car_price,
                'status' => 'Unpaid',
                'type' => 'Individual Order',
                'remarks' => "Invoice for Individual Order #{$order->order_code}",
                'entered_by' => 'Online Customer'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Individual order created successfully! Proceeding to invoice.',
                'order' => $order,
                'invoice' => $invoice
            ], 201);
        });
    }

    public function show($id)
    {
        $order = UcIndividualOrder::with('invoice')->findOrFail($id);
        return response()->json($order);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'nullable|string',
            'payment_status' => 'nullable|string'
        ]);

        $order = UcIndividualOrder::findOrFail($id);

        return DB::transaction(function () use ($validated, $order) {
            if (isset($validated['status'])) {
                $order->status = $validated['status'];
            }

            if (isset($validated['payment_status'])) {
                $order->payment_status = $validated['payment_status'];
                
                // If payment status becomes Paid, mark invoice as Paid and balance as 0
                if (strtolower($validated['payment_status']) === 'paid') {
                    $invoice = $order->invoice;
                    if ($invoice) {
                        $invoice->status = 'Paid';
                        $invoice->balance = 0.00;
                        $invoice->save();
                    }
                }
            }

            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully!',
                'data' => $order->load('invoice')
            ]);
        });
    }

    public function payInvoice($invoiceCode)
    {
        $invoice = UcInvoice::where('invoice_code', $invoiceCode)->firstOrFail();
        
        return DB::transaction(function () use ($invoice) {
            $invoice->status = 'Paid';
            $invoice->balance = 0.00;
            $invoice->save();

            if ($invoice->individual_order_id) {
                $order = UcIndividualOrder::find($invoice->individual_order_id);
                if ($order) {
                    $order->payment_status = 'Paid';
                    $order->status = 'Paid'; // Confirm order as paid
                    $order->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment simulated successfully! Invoice is now paid.',
                'invoice' => $invoice,
                'order' => isset($order) ? $order : null
            ]);
        });
    }

    public function getInvoiceDetails($invoiceCode)
    {
        $invoice = UcInvoice::where('invoice_code', $invoiceCode)->firstOrFail();
        $order = null;
        if ($invoice->individual_order_id) {
            $order = UcIndividualOrder::find($invoice->individual_order_id);
        }

        return response()->json([
            'invoice' => $invoice,
            'order' => $order
        ]);
    }
}
