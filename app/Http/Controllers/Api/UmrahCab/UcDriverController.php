<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UcDriverController extends Controller
{
    /**
     * Display a listing of drivers.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = UcDriver::with('vehicle')->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return response()->json($query->get());
    }

    /**
     * Store a newly created driver.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:uc_drivers,username',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:50',
            'license_no' => 'nullable|string|max:100',
            'vehicle_id' => 'nullable|exists:uc_fleet,id',
            'edit_rights' => 'nullable|boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['edit_rights'] = $request->input('edit_rights', false);

        $driver = UcDriver::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Driver created successfully!',
            'data' => $driver->load('vehicle')
        ], 201);
    }

    /**
     * Display the specified driver.
     */
    public function show($id)
    {
        $driver = UcDriver::with('vehicle')->findOrFail($id);
        return response()->json($driver);
    }

    /**
     * Update the specified driver.
     */
    public function update(Request $request, $id)
    {
        $driver = UcDriver::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('uc_drivers', 'username')->ignore($driver->id),
            ],
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:50',
            'license_no' => 'nullable|string|max:100',
            'vehicle_id' => 'nullable|exists:uc_fleet,id',
            'edit_rights' => 'nullable|boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->has('edit_rights')) {
            $validated['edit_rights'] = $request->input('edit_rights');
        }

        $driver->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Driver updated successfully!',
            'data' => $driver->load('vehicle')
        ]);
    }

    /**
     * Remove the specified driver.
     */
    public function destroy($id)
    {
        $driver = UcDriver::findOrFail($id);
        $driver->delete();

        return response()->json([
            'success' => true,
            'message' => 'Driver deleted successfully!'
        ]);
    }
}
