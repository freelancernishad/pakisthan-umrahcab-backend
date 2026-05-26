<?php

namespace App\Http\Controllers\Common\Notifications;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin; // Assuming you have an Admin model
use App\Http\Requests\Common\Notifications\NotificationStoreRequest;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user or admin.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Check if the request is from a user or admin
        if (Auth::guard('user')->check()) {
            $user = Auth::guard('user')->user();
            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));
        } elseif (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            $notifications = Notification::where('admin_id', $admin->id)
                ->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return response()->json($notifications);
    }

    /**
     * Mark a notification as read.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, $id)
    {
        // Check if the request is from a user or admin
        if (Auth::guard('user')->check()) {
            $user = Auth::guard('user')->user();
            $notification = Notification::where('id', $id)
                ->where('user_id', $user->id)
                ->first();
        } elseif (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            $notification = Notification::where('id', $id)
                ->where('admin_id', $admin->id)
                ->first();
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!$notification) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found or you do not have permission to mark it as read.',
            ], 404);
        }

        // Mark the notification as read
        $notification->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as read.',
        ]);
    }

    /**
     * Create a notification for a user (admin only).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     **/
    public function createForUser(NotificationStoreRequest $request)
    {
        // Check if the authenticated user is an admin
        $admin = Auth::guard('admin')->user();
 
        if (!$admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only admins can create notifications for users.',
            ], 403);
        }

        // Create the notification (do not set admin_id)
        $notification = Notification::create([
            'user_id' => $request->user_id, // Only set user_id
            'message' => $request->message,
            'type' => $request->type ?? 'info',
            'related_model' => $request->related_model,
            'related_model_id' => $request->related_model_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notification created successfully.',
            'notification' => $notification,
        ]);
    }
}
