<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UcSubAdminController extends Controller
{
    /**
     * Display a listing of all sub-admins.
     */
    public function index(Request $request)
    {
        // Return only SUB_ADMINs, or all admins if requested. Let's return all admins so they can see everyone.
        $admins = Admin::orderBy('id', 'desc')->get();
        return response()->json($admins);
    }

    /**
     * Store a newly created sub-admin.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'username' => 'required|string|max:255|unique:admins,username',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:SUPER_ADMIN,SUB_ADMIN',
            'permissions' => 'nullable|array',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        // Default permissions matrix if not provided
        if (empty($validated['permissions'])) {
            $validated['permissions'] = [
                'bookings' => 'none',
                'customers' => 'none',
                'companies' => 'none',
                'services' => 'none',
                'flights' => 'none',
                'trains' => 'none',
                'invoices' => 'none',
                'ledgers' => 'none',
                'payments' => 'none',
                'fleet' => 'none',
                'hotels' => 'none',
                'price_list' => 'none',
                'drivers' => 'none',
                'sub_admins' => 'none',
            ];
        }

        $subAdmin = Admin::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sub-admin created successfully!',
            'data' => $subAdmin
        ], 201);
    }

    /**
     * Display the specified sub-admin.
     */
    public function show($id)
    {
        $subAdmin = Admin::findOrFail($id);
        return response()->json($subAdmin);
    }

    /**
     * Update the specified sub-admin.
     */
    public function update(Request $request, $id)
    {
        $subAdmin = Admin::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('admins', 'email')->ignore($subAdmin->id),
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('admins', 'username')->ignore($subAdmin->id),
            ],
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|in:SUPER_ADMIN,SUB_ADMIN',
            'permissions' => 'nullable|array',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // If updated to SUPER_ADMIN, clear permissions or keep null
        if ($validated['role'] === 'SUPER_ADMIN') {
            $validated['permissions'] = null;
        }

        $subAdmin->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sub-admin updated successfully!',
            'data' => $subAdmin
        ]);
    }

    /**
     * Remove the specified sub-admin.
     */
    public function destroy($id)
    {
        // Prevent deleting the currently authenticated admin
        $currentAdmin = auth()->user();
        if ($currentAdmin && $currentAdmin->id == $id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own admin account.'
            ], 400);
        }

        $subAdmin = Admin::findOrFail($id);
        
        // Prevent deleting the last SUPER_ADMIN
        if ($subAdmin->role === 'SUPER_ADMIN') {
            $superAdminCount = Admin::where('role', 'SUPER_ADMIN')->count();
            if ($superAdminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete the only Super Admin account.'
                ], 400);
            }
        }

        $subAdmin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin account deleted successfully!'
        ]);
    }
}
