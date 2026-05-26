<?php

namespace App\Http\Controllers\Common\SupportAndConnect;

use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\SupportAndConnect\Contacts\ContactMessage;
use App\Http\Requests\Common\SupportAndConnect\ContactMessageSendRequest;

class ContactMessageController extends Controller
{
    public function send(ContactMessageSendRequest $request)
    {

        $validated = $request->validated();
        $message = ContactMessage::create($validated);

    try {
       $user = (object) [
    'name' => $message->full_name,
    'email' => $message->email,
    ];

    $admin = (object) [
        'name' => 'Admin',
        'email' => config('ADMIN_EMAIL'),
    ];

    // Send to Admin
    NotificationHelper::sendUserNotification(
        $admin,
        "New contact message from {$user->name}.", // message (string)
        'New Contact Message',                     // subject
        'ContactMessage',                          // related model
        $message->id,                              // related model id
        'emails.supportAndConnect.admin_received', // Blade view
        [
            'name' => $user->name,                // string
            'email' => $user->email,              // string
            'subject' => $message->subject,       // string
            'user_message' => $message->message,       // string (IMPORTANT: must be string)
        ]
    );

    // Send to User
    NotificationHelper::sendUserNotification(
        $user,
        "Dear {$user->name}, we have received your message and will contact you soon.",
        'Contact Form Received',
        'ContactMessage',
        $message->id,
        'emails.supportAndConnect.user_received',
        [
            'name' => $user->name,
            'email' => $user->email,
            'subject' => $message->subject,
            'user_message' => $message->message, // must be string
        ]
    );


        $message->update(['email_sent' => true]);
    } catch (\Exception $e) {
        Log::error("Email failed: " . $e->getMessage());
    }

    return response()->json([
        'success' => true,
        'message' => 'Your message has been received.',
    ]);
}



public function successfulAdminEmails()
{
    $messages = ContactMessage::where('email_sent', true)
        ->orderBy('created_at', 'desc')
        ->get(['id', 'full_name', 'email', 'subject', 'created_at']);

    return response()->json([
        'success' => true,
        'data' => $messages,
    ]);
}

public function show($id)
{
    $message = ContactMessage::where('email_sent', true)->findOrFail($id);

        return response()->json([
        'success' => true,
        'data' => $message,
    ]);
}

}
