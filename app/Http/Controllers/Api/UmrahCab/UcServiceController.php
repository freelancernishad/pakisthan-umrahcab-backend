<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcService;
use Illuminate\Http\Request;

class UcServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $status = $request->query('status');
        $perPage = $request->query('per_page', 10);

        $query = UcService::with('customer')->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('custom_id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($type && $type !== 'All') {
            $query->where('type', $type);
        }

        if ($status && $status !== 'All') {
            $query->where('status', $status);
        }

        if ($request->query('catalog') === 'true') {
            $query->whereNull('customer_id');
        }

        // Allow fetching all if requested or if no pagination parameter is sent
        if ($request->query('all') === 'true' || !$request->has('page')) {
            return response()->json($query->get());
        }

        return response()->json($query->paginate($perPage));
    }

    public function show($id)
    {
        $service = UcService::with('customer')->where('id', $id)->orWhere('custom_id', $id)->firstOrFail();
        return response()->json($service);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|integer|exists:uc_customers,id',
            'name' => 'required|string',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric',
            'status' => 'nullable|string',
            'pickup' => 'nullable|string',
            'driver_cash' => 'nullable|numeric',
            'date' => 'nullable|date',
            'time' => 'nullable|string',
        ]);

        $count = UcService::count() + 1;
        $validated['custom_id'] = "#SRV-{$count}";
        if (!isset($validated['status'])) {
            $validated['status'] = 'Pending';
        }

        $service = UcService::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Service registered successfully!',
            'data' => $service
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $service = UcService::where('id', $id)->orWhere('custom_id', $id)->firstOrFail();

        $validated = $request->validate([
            'customer_id' => 'nullable|integer|exists:uc_customers,id',
            'name' => 'nullable|string',
            'type' => 'nullable|string',
            'description' => 'nullable|string',
            'base_price' => 'nullable|numeric',
            'status' => 'nullable|string',
            'pickup' => 'nullable|string',
            'driver_cash' => 'nullable|numeric',
            'date' => 'nullable|date',
            'time' => 'nullable|string',
        ]);

        $service->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully!',
            'data' => $service
        ]);
    }

    public function destroy($id)
    {
        $service = UcService::where('id', $id)->orWhere('custom_id', $id)->firstOrFail();
        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully!'
        ]);
    }
}
