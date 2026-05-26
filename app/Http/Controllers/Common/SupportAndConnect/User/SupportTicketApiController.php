<?php

namespace App\Http\Controllers\Common\SupportAndConnect\User;

use App\Http\Controllers\Controller;
use App\Models\SupportAndConnect\Ticket\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Common\SupportAndConnect\SupportTicketStoreRequest;

class SupportTicketApiController extends Controller
{
    // Get all support tickets for the authenticated user
    public function index()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        return response()->json($tickets, 200);
    }

    // Create a new support ticket
    public function store(SupportTicketStoreRequest $request)
    {

        // Create the ticket
        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
        ]);

        // Handle attachment if present
        if ($request->hasFile('attachment')) {
           $ticket->saveAttachment($request->file('attachment'));
        }

        // Send Global Notification & Email
        send_notification(
            auth()->user(),
            "We have received your ticket #{$ticket->id}. Subject: {$ticket->subject}",
            "Ticket Received: #{$ticket->id}",
            'emails.tickets.created',
            [
                'user_name' => auth()->user()->name,
                'ticket_id' => $ticket->id,
                'subject' => $ticket->subject,
                'priority' => $ticket->priority,
                'status' => 'Open',
            ],
            'SupportTicket',
            $ticket->id
        );

        // Send Global Notification to All Admins
        $admins = \App\Models\Admin::all();
        foreach ($admins as $admin) {
             send_notification(
                $admin,
                "New support ticket #{$ticket->id} from {$ticket->user->name}.",
                "New Ticket: #{$ticket->id}",
                'emails.admins.tickets.created',
                [
                    'ticket_id' => $ticket->id,
                    'user_name' => $ticket->user->name,
                    'subject' => $ticket->subject,
                    'priority' => $ticket->priority,
                ],
                'SupportTicket',
                $ticket->id
            );
        }

        return response()->json(['message' => 'Ticket created successfully.', 'ticket' => $ticket], 201);
    }

    // Show a specific support ticket
    public function show(SupportTicket $ticket)
    {
        //    return response()->json(["ticket_id" => $ticket->id, "user_id" => Auth::id()]);
        // Ensure the ticket belongs to the authenticated user
        if ($ticket->user_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        return response()->json($ticket, 200);
    }

    // Update a support ticket (if needed)
    public function update(SupportTicketStoreRequest $request, SupportTicket $ticket)
    {
        // Ensure the ticket belongs to the authenticated user
     
        if ($ticket->user_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        // Update the ticket details
        $ticket->update($request->only('subject', 'message', 'priority'));

        // Handle attachment if present
        if ($request->hasFile('attachment')) {
            $ticket->saveAttachment($request->file('attachment'));
        }

        return response()->json(['message' => 'Ticket updated successfully.', 'ticket' => $ticket], 200);
    }
}
