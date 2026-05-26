<?php

namespace App\Http\Controllers\Common\SupportAndConnect\Admin;

use Illuminate\Http\Request;
use App\Models\SupportAndConnect\Ticket\SupportTicket;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Common\SupportAndConnect\Admin\SupportTicketReplyRequest;
use App\Http\Requests\Common\SupportAndConnect\Admin\SupportTicketStatusRequest;

class AdminSupportTicketApiController extends Controller
{
    // Get all support tickets for admin
    public function index()
    {
        $tickets = SupportTicket::with('user')->latest()->get();
        return response()->json($tickets);
    }

    // View a specific support ticket
    public function show($id)
    {
        $ticket = SupportTicket::with(['user', 'replies'])->findOrFail($id);
        return response()->json($ticket);
    }

    // Reply to a support ticket
    public function reply(SupportTicketReplyRequest $request, $id)
    {

        // Find the support ticket by ID
        $ticket = SupportTicket::findOrFail($id);

        // Update ticket status
        $ticket->status = $request->status;

        // Prepare reply data
        $replyData = [
            'reply' => $request->reply,
            'reply_id' => $request->reply_id, // Set the parent reply ID if provided
        ];

        // Check if the logged-in user is an admin
        if (auth()->guard('admin')->check()) {
            $replyData['admin_id'] = auth()->guard('admin')->id();
        } else {
            $replyData['user_id'] = auth()->guard('user')->id();
        }

        // Create a new reply associated with the support ticket
        $reply = $ticket->replies()->create($replyData);

        // Handle attachment if present
        if ($request->hasFile('attachment')) {
            $reply->saveAttachment($request->file('attachment'));
        }

        // Save the ticket with the updated status
        $ticket->save();

        // Send Global Notification to User if Admin replied
        if (auth()->guard('admin')->check()) {
            $user = $ticket->user;
            if ($user) {
                send_notification(
                    $user,
                    "New reply on ticket #{$ticket->id}.",
                    "New Reply on Ticket #{$ticket->id}",
                    'emails.tickets.reply',
                    [
                        'user_name' => $user->name,
                        'ticket_id' => $ticket->id,
                        'reply_content' => $reply->reply,
                        'ticket_subject' => $ticket->subject,
                        'ticket_status' => $ticket->status ?? 'Active',
                    ],
                     'SupportTicket',
                     $ticket->id
                );
            }
        } 
        // Send Global Notification to Admins if User replied
        elseif (auth()->guard('user')->check()) {
             $admins = \App\Models\Admin::all();
             foreach ($admins as $admin) {
                 send_notification(
                    $admin,
                    "User {$ticket->user->name} replied to ticket #{$ticket->id}.",
                    "New Reply on Ticket #{$ticket->id}",
                    'emails.admins.tickets.reply',
                    [
                        'ticket_id' => $ticket->id,
                        'user_name' => $ticket->user->name,
                        'reply_content' => $reply->reply,
                        'ticket_subject' => $ticket->subject,
                        'ticket_status' => $ticket->status ?? 'Active',
                    ],
                    'SupportTicket',
                    $ticket->id
                );
             }
        }

        return response()->json([
            'message' => 'Reply sent successfully and ticket status updated.',
            'reply' => $reply
        ], 200);
    }

    // Update ticket status
    public function updateStatus(SupportTicketStatusRequest $request, $id)
    {

        $ticket = SupportTicket::findOrFail($id);
        $ticket->status = $request->status;

        // Handle attachment if present
        if ($request->hasFile('attachment')) {
            $ticket->saveAttachment($request->file('attachment'));
        }

        $ticket->save();

        return response()->json(['message' => 'Ticket status updated successfully.'], 200);
    }
}
