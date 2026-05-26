<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Models\User;
use Illuminate\Support\Str;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\AdminUserResource;
use App\Http\Resources\Admin\AdminUserCollection;
use App\Http\Requests\Admin\UserManagement\AdminUserUpdateRequest;
use App\Http\Requests\Admin\UserManagement\AdminUserResetPasswordRequest;
use App\Http\Requests\Admin\UserManagement\AdminUserBulkActionRequest;
use App\Http\Requests\Admin\UserManagement\AdminUserImportRequest;

class AdminUserController extends Controller
{
    // List users with filters & pagination
public function index(Request $request)
{
    $query = User::query();

    // Global search across multiple fields
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
            // Add more fields if needed
        });
    }

    // Individual filters
    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    if ($request->filled('email')) {
        $query->where('email', 'like', '%' . $request->email . '%');
    }

    if ($request->filled('phone')) {
        $query->where('phone', 'like', '%' . $request->phone . '%');
    }

    if ($request->has('is_active')) {
        $query->where('is_active', $request->boolean('is_active'));
    }

    if ($request->has('is_blocked')) {
        $query->where('is_blocked', $request->boolean('is_blocked'));
    }

    // Sorting
    $sortBy = $request->input('sort_by', 'id'); // default sort by id
    $sortOrder = $request->input('sort_order', 'desc');
    $query->orderBy($sortBy, $sortOrder);

    // Pagination
    $perPage = $request->input('per_page', 20);
    $users = $query->paginate($perPage);


    return response()->json(['data'=>new AdminUserCollection($users)]);
   
}


    // Show single user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return new AdminUserResource($user);
    }


    public function update(AdminUserUpdateRequest $request, $id)
    {
        $user = User::find($id);

        // Update fields
        $user->fill($request->only([
            'name', 'email', 'role', 'is_active', 'is_blocked', 'notes', 'phone'
        ]));

        $user->save();

        return response()->json([
            'data' => new AdminUserResource($user),
            'Message' => 'User updated successfully',
            'isError' => false,
            'status_code' => 200
        ]);
    }



    // Activate / Deactivate
    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'message' => $user->is_active ? 'User activated' : 'User deactivated'
        ]);
    }

    // Block / Unblock
    public function toggleBlock($id)
    {
        $user = User::findOrFail($id);
        $user->is_blocked = !$user->is_blocked;
        $user->save();

        return response()->json([
            'message' => $user->is_blocked ? 'User blocked' : 'User unblocked'
        ]);
    }

    // Reset password
    public function resetPassword(AdminUserResetPasswordRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $newPassword = $request->input('password', Str::random(10));
        $user->password = Hash::make($newPassword);
        $user->save();

        // Optionally send email notification

        return response()->json([
            'message' => 'Password reset successfully',
            'new_password' => $newPassword
        ]);
    }

    // Verify email manually
    public function verifyEmail($id)
    {
        $user = User::findOrFail($id);
        $user->email_verified_at = now();
        $user->save();

        return response()->json(['message' => 'Email verified']);
    }

    // Add / update admin notes
    public function updateNotes(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->notes = $request->input('notes', $user->notes);
        $user->save();

        return response()->json(['message' => 'Notes updated']);
    }

    // Bulk action
    public function bulkAction(AdminUserBulkActionRequest $request)
    {
        $action = $request->input('action'); // activate, deactivate, block, unblock
        $userIds = $request->input('user_ids', []);

        if (empty($userIds)) {
            return response()->json(['message' => 'No users selected'], 400);
        }

        $updateData = match ($action) {
            'activate' => ['is_active' => true],
            'deactivate' => ['is_active' => false],
            'block' => ['is_blocked' => true],
            'unblock' => ['is_blocked' => false],
            default => null,
        };

        if ($updateData) {
            User::whereIn('id', $userIds)->update($updateData);
        }

        return response()->json(['message' => 'Bulk action completed successfully']);
    }

    // Delete user (soft delete recommended)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // Soft delete if `SoftDeletes` trait is used
        return response()->json(['message' => 'User deleted']);
    }


    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function import(AdminUserImportRequest $request)
    {
        Excel::import(new UsersImport, $request->file('file'));
        return response()->json(['message' => 'Users imported successfully']);
    }

    public function impersonate($id)
    {
        $user = User::findOrFail($id);
        
        // Generate a JWT token for the user targeting the 'user' guard
        // We use fromUser to get a token for a specific user model
        $token = JWTAuth::fromUser($user);
        
        $secure = config('session.secure') ?? request()->secure();
        $domain = config('session.domain');
        $sameSite = config('session.same_site', 'lax');

        $cookie = cookie('token', $token, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);
        $userCookie = cookie('user_token', $token, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);

        return response()->json([
            'message' => 'Impersonation token generated',
            'token' => $token,
            'user' => $user
        ])
        ->withCookie($cookie)
        ->withCookie($userCookie);
    }
}
