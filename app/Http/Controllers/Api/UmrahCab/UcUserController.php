<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UcUserController extends Controller
{
    public function index()
    {
        return response()->json(
            UcUser::with('company')->orderBy('id', 'desc')->get()
        );
    }

    public function show($id)
    {
        $user = UcUser::with('company')->findOrFail($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:uc_users,username',
            'password' => 'required|string|min:6',
            'user_type' => 'required|string|in:ADMIN,COMPANIES',
            'company_id' => 'nullable|integer|exists:uc_companies,id'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        if ($validated['user_type'] === 'ADMIN') {
            $validated['company_id'] = null;
        }

        $user = UcUser::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully!',
            'data' => $user
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = UcUser::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|unique:uc_users,username,' . $id,
            'password' => 'nullable|string|min:6',
            'user_type' => 'required|string|in:ADMIN,COMPANIES',
            'company_id' => 'nullable|integer|exists:uc_companies,id'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($validated['user_type'] === 'ADMIN') {
            $validated['company_id'] = null;
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully!',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = UcUser::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!'
        ]);
    }
}
