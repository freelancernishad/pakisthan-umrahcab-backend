<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcDriverEntry;
use App\Models\UmrahCab\UcDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UcDriverEntryController extends Controller
{
    /**
     * For Admin: Display a listing of all driver entries.
     */
    public function index(Request $request)
    {
        $query = UcDriverEntry::with(['driver', 'vehicle'])->orderBy('date', 'desc')->orderBy('id', 'desc');

        if ($request->has('driver_id') && !empty($request->driver_id)) {
            $query->where('driver_id', $request->driver_id);
        }

        if ($request->has('vehicle_id') && !empty($request->vehicle_id)) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('date', $request->date);
        }

        if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date) && !empty($request->end_date)) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        return response()->json($query->get());
    }

    /**
     * For Driver: Display a listing of entries for the authenticated driver.
     */
    public function myEntries(Request $request)
    {
        $driver = Auth::guard('driver')->user();
        if (!$driver) {
            return response()->json(['message' => 'Unauthorized driver.'], 401);
        }

        $entries = UcDriverEntry::with('vehicle')
            ->where('driver_id', $driver->id)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($entries);
    }

    /**
     * For Driver: Submit a new entry.
     */
    public function submitEntry(Request $request)
    {
        $driver = Auth::guard('driver')->user();
        if (!$driver) {
            return response()->json(['message' => 'Unauthorized driver.'], 401);
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'trip' => 'nullable|string|max:255',
            'hotel_drop_off' => 'nullable|string|max:255',
            'agent' => 'nullable|string|max:255',
            'rate' => 'nullable|numeric|min:0',
            'voucher' => 'nullable|numeric|min:0',
            'cash' => 'nullable|numeric|min:0',
            'fuel' => 'nullable|numeric|min:0',
            'parking' => 'nullable|numeric|min:0',
            'wash' => 'nullable|numeric|min:0',
            'oil_change' => 'nullable|numeric|min:0',
            'car_maintenance' => 'nullable|numeric|min:0',
            'waqas_received' => 'nullable|numeric|min:0',
            'mic' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric',
        ]);

        $validated['driver_id'] = $driver->id;
        $validated['vehicle_id'] = $driver->vehicle_id; // Automatically assign driver's current vehicle
        
        // Calculate total if not explicitly provided
        if (!isset($validated['total']) || $validated['total'] === null) {
            $rate = $validated['rate'] ?? 0;
            $voucher = $validated['voucher'] ?? 0;
            $cash = $validated['cash'] ?? 0;
            $fuel = $validated['fuel'] ?? 0;
            $parking = $validated['parking'] ?? 0;
            $wash = $validated['wash'] ?? 0;
            $oil_change = $validated['oil_change'] ?? 0;
            $car_maintenance = $validated['car_maintenance'] ?? 0;
            $mic = $validated['mic'] ?? 0;

            // Excel row showed Rate + Voucher + Cash = Total, but let's do the full net balance formula or simple sum
            // Let's store net cash flow or earnings:
            $validated['total'] = ($rate + $voucher + $cash) - ($fuel + $parking + $wash + $oil_change + $car_maintenance + $mic);
        }

        $validated['is_locked'] = true; // Locked by default on submission

        $entry = UcDriverEntry::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Entry submitted successfully and is now locked!',
            'data' => $entry->load('vehicle')
        ], 201);
    }

    /**
     * For Driver: Update an entry (only if unlocked or driver has edit_rights).
     */
    public function updateEntry(Request $request, $id)
    {
        $driver = Auth::guard('driver')->user();
        if (!$driver) {
            return response()->json(['message' => 'Unauthorized driver.'], 401);
        }

        $entry = UcDriverEntry::where('id', $id)->where('driver_id', $driver->id)->firstOrFail();

        // Check if locked and driver has no edit rights
        if ($entry->is_locked && !$driver->edit_rights) {
            return response()->json([
                'success' => false,
                'message' => 'This entry is locked. Please contact your administrator to make any changes.'
            ], 403);
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'trip' => 'nullable|string|max:255',
            'hotel_drop_off' => 'nullable|string|max:255',
            'agent' => 'nullable|string|max:255',
            'rate' => 'nullable|numeric|min:0',
            'voucher' => 'nullable|numeric|min:0',
            'cash' => 'nullable|numeric|min:0',
            'fuel' => 'nullable|numeric|min:0',
            'parking' => 'nullable|numeric|min:0',
            'wash' => 'nullable|numeric|min:0',
            'oil_change' => 'nullable|numeric|min:0',
            'car_maintenance' => 'nullable|numeric|min:0',
            'waqas_received' => 'nullable|numeric|min:0',
            'mic' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric',
        ]);

        if (!isset($validated['total']) || $validated['total'] === null) {
            $rate = $validated['rate'] ?? 0;
            $voucher = $validated['voucher'] ?? 0;
            $cash = $validated['cash'] ?? 0;
            $fuel = $validated['fuel'] ?? 0;
            $parking = $validated['parking'] ?? 0;
            $wash = $validated['wash'] ?? 0;
            $oil_change = $validated['oil_change'] ?? 0;
            $car_maintenance = $validated['car_maintenance'] ?? 0;
            $mic = $validated['mic'] ?? 0;

            $validated['total'] = ($rate + $voucher + $cash) - ($fuel + $parking + $wash + $oil_change + $car_maintenance + $mic);
        }

        // If it was edited, lock it again
        $validated['is_locked'] = true;

        $entry->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Entry updated successfully!',
            'data' => $entry->load('vehicle')
        ]);
    }

    /**
     * For Admin: Show specific entry.
     */
    public function show($id)
    {
        $entry = UcDriverEntry::with(['driver', 'vehicle'])->findOrFail($id);
        return response()->json($entry);
    }

    /**
     * For Admin: Create an entry on behalf of a driver.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:uc_drivers,id',
            'date' => 'required|date',
            'trip' => 'nullable|string|max:255',
            'hotel_drop_off' => 'nullable|string|max:255',
            'agent' => 'nullable|string|max:255',
            'rate' => 'nullable|numeric|min:0',
            'voucher' => 'nullable|numeric|min:0',
            'cash' => 'nullable|numeric|min:0',
            'fuel' => 'nullable|numeric|min:0',
            'parking' => 'nullable|numeric|min:0',
            'wash' => 'nullable|numeric|min:0',
            'oil_change' => 'nullable|numeric|min:0',
            'car_maintenance' => 'nullable|numeric|min:0',
            'waqas_received' => 'nullable|numeric|min:0',
            'mic' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric',
            'is_locked' => 'nullable|boolean'
        ]);

        $driver = UcDriver::findOrFail($validated['driver_id']);
        $validated['vehicle_id'] = $driver->vehicle_id;

        if (!isset($validated['total']) || $validated['total'] === null) {
            $rate = $validated['rate'] ?? 0;
            $voucher = $validated['voucher'] ?? 0;
            $cash = $validated['cash'] ?? 0;
            $fuel = $validated['fuel'] ?? 0;
            $parking = $validated['parking'] ?? 0;
            $wash = $validated['wash'] ?? 0;
            $oil_change = $validated['oil_change'] ?? 0;
            $car_maintenance = $validated['car_maintenance'] ?? 0;
            $mic = $validated['mic'] ?? 0;

            $validated['total'] = ($rate + $voucher + $cash) - ($fuel + $parking + $wash + $oil_change + $car_maintenance + $mic);
        }

        $validated['is_locked'] = $request->input('is_locked', true);

        $entry = UcDriverEntry::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Driver entry created successfully by admin!',
            'data' => $entry->load(['driver', 'vehicle'])
        ], 201);
    }

    /**
     * For Admin: Update any driver entry.
     */
    public function update(Request $request, $id)
    {
        $entry = UcDriverEntry::findOrFail($id);

        $validated = $request->validate([
            'driver_id' => 'required|exists:uc_drivers,id',
            'date' => 'required|date',
            'trip' => 'nullable|string|max:255',
            'hotel_drop_off' => 'nullable|string|max:255',
            'agent' => 'nullable|string|max:255',
            'rate' => 'nullable|numeric|min:0',
            'voucher' => 'nullable|numeric|min:0',
            'cash' => 'nullable|numeric|min:0',
            'fuel' => 'nullable|numeric|min:0',
            'parking' => 'nullable|numeric|min:0',
            'wash' => 'nullable|numeric|min:0',
            'oil_change' => 'nullable|numeric|min:0',
            'car_maintenance' => 'nullable|numeric|min:0',
            'waqas_received' => 'nullable|numeric|min:0',
            'mic' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric',
            'is_locked' => 'nullable|boolean'
        ]);

        $driver = UcDriver::findOrFail($validated['driver_id']);
        $validated['vehicle_id'] = $driver->vehicle_id;

        if (!isset($validated['total']) || $validated['total'] === null) {
            $rate = $validated['rate'] ?? 0;
            $voucher = $validated['voucher'] ?? 0;
            $cash = $validated['cash'] ?? 0;
            $fuel = $validated['fuel'] ?? 0;
            $parking = $validated['parking'] ?? 0;
            $wash = $validated['wash'] ?? 0;
            $oil_change = $validated['oil_change'] ?? 0;
            $car_maintenance = $validated['car_maintenance'] ?? 0;
            $mic = $validated['mic'] ?? 0;

            $validated['total'] = ($rate + $voucher + $cash) - ($fuel + $parking + $wash + $oil_change + $car_maintenance + $mic);
        }

        if ($request->has('is_locked')) {
            $validated['is_locked'] = $request->input('is_locked');
        }

        $entry->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Driver entry updated successfully by admin!',
            'data' => $entry->load(['driver', 'vehicle'])
        ]);
    }

    /**
     * For Admin: Delete any driver entry.
     */
    public function destroy($id)
    {
        $entry = UcDriverEntry::findOrFail($id);
        $entry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Driver entry deleted successfully!'
        ]);
    }

    /**
     * For Admin: Toggle the locked status of an entry.
     */
    public function toggleLock(Request $request, $id)
    {
        $entry = UcDriverEntry::findOrFail($id);
        $entry->is_locked = !$entry->is_locked;
        $entry->save();

        return response()->json([
            'success' => true,
            'message' => $entry->is_locked ? 'Entry has been locked.' : 'Entry has been unlocked.',
            'data' => $entry->load(['driver', 'vehicle'])
        ]);
    }
}
