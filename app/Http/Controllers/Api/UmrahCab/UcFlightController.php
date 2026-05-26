<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcFlight;
use App\Models\UmrahCab\UcAudit;
use Illuminate\Http\Request;

class UcFlightController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $leg = $request->query('leg');
        $status = $request->query('status');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $perPage = $request->query('per_page', 10);

        $query = UcFlight::with('customer')->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('flight_no', 'like', "%{$search}%")
                  ->orWhere('custom_id', 'like', "%{$search}%")
                  ->orWhere('route', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%")
                           ->orWhere('company', 'like', "%{$search}%")
                           ->orWhere('custom_id', 'like', "%{$search}%");
                  });
            });
        }

        if ($leg && $leg !== 'All') {
            $query->where('leg', $leg);
        }

        if ($status && $status !== 'All') {
            $query->where('status', $status);
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        if ($request->query('all') === 'true' || !$request->has('page')) {
            return response()->json($query->get());
        }

        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:uc_customers,id',
            'flight_no' => 'required|string',
            'leg' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'route' => 'required|string',
        ]);

        $count = UcFlight::count() + 101;
        $validated['custom_id'] = "#FLT-{$count}";
        $validated['status'] = 'On Time';

        $flight = UcFlight::create($validated);

        UcAudit::create([
            'custom_id' => $flight->custom_id,
            'user_session' => auth()->user() ? auth()->user()->username : 'umrahcab',
            'ip_location' => $request->ip(),
            'performed_action' => "Added " . strtolower($flight->leg) . " flight {$flight->custom_id} for customer #{$flight->customer_id}"
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Flight scheduled successfully!',
            'data' => $flight
        ], 201);
    }

    public function show($id)
    {
        $flight = UcFlight::with('customer')->find($id);

        if (!$flight) {
            return response()->json([
                'success' => false,
                'message' => 'Flight not found'
            ], 404);
        }

        $audits = UcAudit::where('custom_id', $flight->custom_id)->orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'flight' => $flight,
                'audits' => $audits
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $flight = UcFlight::find($id);

        if (!$flight) {
            return response()->json([
                'success' => false,
                'message' => 'Flight not found'
            ], 404);
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:uc_customers,id',
            'flight_no' => 'required|string',
            'leg' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'route' => 'required|string',
            'status' => 'required|string'
        ]);

        $changes = [];
        foreach ($validated as $key => $val) {
            if ($flight->getOriginal($key) != $val) {
                $changes[] = "changed $key from '" . $flight->getOriginal($key) . "' to '$val'";
            }
        }
        $remark = "Updated flight {$flight->custom_id}: " . (empty($changes) ? "no changes" : implode(", ", $changes));

        $flight->update($validated);

        UcAudit::create([
            'custom_id' => $flight->custom_id,
            'user_session' => auth()->user() ? auth()->user()->username : 'umrahcab',
            'ip_location' => $request->ip(),
            'performed_action' => $remark
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Flight updated successfully!',
            'data' => $flight
        ]);
    }

    public function destroy($id)
    {
        $flight = UcFlight::find($id);

        if (!$flight) {
            return response()->json([
                'success' => false,
                'message' => 'Flight not found'
            ], 404);
        }

        UcAudit::create([
            'custom_id' => $flight->custom_id,
            'user_session' => auth()->user() ? auth()->user()->username : 'umrahcab',
            'ip_location' => request()->ip(),
            'performed_action' => "Deleted flight record: {$flight->custom_id} (No: {$flight->flight_no}, Route: {$flight->route})"
        ]);

        $flight->delete();

        return response()->json([
            'success' => true,
            'message' => 'Flight deleted successfully!'
        ]);
    }
}
