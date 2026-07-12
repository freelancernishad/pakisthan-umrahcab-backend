<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcChatMessage;
use App\Models\UmrahCab\UcCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UcChatController extends Controller
{
    /**
     * Helper to handle file uploads locally for robust development.
     */
    private function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                
                // Upload to S3 if configured, otherwise fall back to local disk
                if (config('filesystems.disks.s3.key') && config('filesystems.disks.s3.secret') && config('filesystems.disks.s3.bucket')) {
                    $path = \Illuminate\Support\Facades\Storage::disk('s3')->putFileAs('chat', $file, $filename);
                    return \Illuminate\Support\Facades\Storage::disk('s3')->url($path);
                } else {
                    $directory = public_path('uploads/chat');
                    if (!\Illuminate\Support\Facades\File::exists($directory)) {
                        \Illuminate\Support\Facades\File::makeDirectory($directory, 0755, true, true);
                    }
                    $file->move($directory, $filename);
                    return '/uploads/chat/' . $filename;
                }
            }
        }
        return null;
    }

    /**
     * B2B Agent: Fetch chat messages.
     */
    public function getCompanyMessages(Request $request)
    {
        $company = Auth::guard('company')->user();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized B2B Agent'], 401);
        }

        // Mark all admin messages in this room as read
        UcChatMessage::where('company_id', $company->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = UcChatMessage::where('company_id', $company->id)
            ->with('replyTo')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * B2B Agent: Send message/file.
     */
    public function sendCompanyMessage(Request $request)
    {
        $company = Auth::guard('company')->user();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized B2B Agent'], 401);
        }

        $validated = $request->validate([
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // Max 10MB
            'reply_to_id' => 'nullable|integer|exists:uc_chat_messages,id'
        ]);

        $attachmentPath = $this->uploadFile($request);
        $messageText = $validated['message'] ?? null;
        $replyToId = $validated['reply_to_id'] ?? null;

        if (!$messageText && !$attachmentPath) {
            return response()->json(['message' => 'Cannot send an empty message.'], 400);
        }

        $chatMessage = UcChatMessage::create([
            'company_id' => $company->id,
            'sender_type' => 'company',
            'sender_id' => $company->id,
            'message' => $messageText,
            'attachment' => $attachmentPath,
            'reply_to_id' => $replyToId,
            'is_read' => false
        ]);

        $chatMessage->load('replyTo');

        return response()->json([
            'success' => true,
            'data' => $chatMessage
        ], 201);
    }

    /**
     * Admin: Fetch all chat rooms/companies with last message and unread count.
     */
    public function getAdminRooms(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return response()->json(['message' => 'Unauthorized Admin'], 401);
        }

        // Fetch companies that have at least one message or fetch all companies to list them
        $companies = UcCompany::all();

        $rooms = [];
        foreach ($companies as $company) {
            $lastMessage = UcChatMessage::where('company_id', $company->id)
                ->orderBy('id', 'desc')
                ->first();

            $unreadCount = UcChatMessage::where('company_id', $company->id)
                ->where('sender_type', 'company')
                ->where('is_read', false)
                ->count();

            // We include rooms with existing messages first, or just list all companies so admin can initiate
            $rooms[] = [
                'company' => $company,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
                'updated_at' => $lastMessage ? $lastMessage->created_at : $company->created_at
            ];
        }

        // Sort: rooms with messages first, then sorted by latest message time
        usort($rooms, function ($a, $b) {
            if ($a['last_message'] && !$b['last_message']) return -1;
            if (!$a['last_message'] && $b['last_message']) return 1;
            
            $timeA = $a['last_message'] ? $a['last_message']->created_at : $a['company']->created_at;
            $timeB = $b['last_message'] ? $b['last_message']->created_at : $b['company']->created_at;
            
            return strcmp($timeB, $timeA);
        });

        return response()->json($rooms);
    }

    /**
     * Admin: Fetch messages for a specific company's chat.
     */
    public function getAdminMessages($company_id)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return response()->json(['message' => 'Unauthorized Admin'], 401);
        }

        $company = UcCompany::findOrFail($company_id);

        // Mark all company messages in this room as read by admin
        UcChatMessage::where('company_id', $company->id)
            ->where('sender_type', 'company')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = UcChatMessage::where('company_id', $company->id)
            ->with('replyTo')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Admin: Send reply to a company.
     */
    public function sendAdminMessage(Request $request, $company_id)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return response()->json(['message' => 'Unauthorized Admin'], 401);
        }

        $company = UcCompany::findOrFail($company_id);

        $validated = $request->validate([
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // Max 10MB
            'reply_to_id' => 'nullable|integer|exists:uc_chat_messages,id'
        ]);

        $attachmentPath = $this->uploadFile($request);
        $messageText = $validated['message'] ?? null;
        $replyToId = $validated['reply_to_id'] ?? null;

        if (!$messageText && !$attachmentPath) {
            return response()->json(['message' => 'Cannot send an empty message.'], 400);
        }

        $chatMessage = UcChatMessage::create([
            'company_id' => $company->id,
            'sender_type' => 'admin',
            'sender_id' => $admin->id,
            'message' => $messageText,
            'attachment' => $attachmentPath,
            'reply_to_id' => $replyToId,
            'is_read' => false
        ]);

        $chatMessage->load('replyTo');

        return response()->json([
            'success' => true,
            'data' => $chatMessage
        ], 201);
    }
}
