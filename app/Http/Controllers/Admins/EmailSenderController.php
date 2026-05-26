<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EmailTemplate;
use App\Models\User;
use App\Mail\DynamicEmail;
use App\Jobs\SendBulkEmail;
use Illuminate\Support\Facades\Mail;

class EmailSenderController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::latest()->get();
        $users = User::all();
        return view('admin.email-sender.index', compact('templates', 'users'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:email_templates,id',
            'recipients' => 'nullable|array',
            'manual_emails' => 'nullable|string',
        ]);

        $template = EmailTemplate::find($request->template_id);
        $recipientsData = [];

        // Add manual emails
        if ($request->manual_emails) {
            $emails = array_map('trim', explode(',', $request->manual_emails));
            foreach ($emails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $recipientsData[] = ['email' => $email, 'name' => 'User'];
                }
            }
        }

        // Add selected users
        if ($request->recipients) {
            $users = [];
            if (in_array('all', $request->recipients)) {
                $users = User::all(['email', 'name']);
            } else {
                $users = User::whereIn('id', $request->recipients)->get(['email', 'name']);
            }

            foreach ($users as $user) {
                $recipientsData[] = ['email' => $user->email, 'name' => $user->name];
            }
        }

        // Deduplicate by email
        $uniqueRecipients = [];
        $emailsSeen = [];
        foreach ($recipientsData as $data) {
            if (!in_array($data['email'], $emailsSeen)) {
                $uniqueRecipients[] = $data;
                $emailsSeen[] = $data['email'];
            }
        }

        if (empty($uniqueRecipients)) {
            return response()->json(['isError' => true, 'Message' => 'No valid recipients selected.']);
        }

        if (count($uniqueRecipients) > 1) {
            // Bulk sending via Queue
            SendBulkEmail::dispatch($uniqueRecipients, $template->subject, $template->content_html, $template->id);
            return response()->json(['isError' => false, 'Message' => 'Bulk emails have been queued for sending.']);
        } else {
            // Single sending
            $recipient = $uniqueRecipients[0];
            Mail::to($recipient['email'])->send(new DynamicEmail($template->subject, $template->content_html, ['name' => $recipient['name']]));

            // Log the single email
            \App\Models\EmailLog::create([
                'recipient' => $recipient['email'],
                'subject' => $template->subject,
                'status' => 'sent',
                'template_id' => $template->id,
            ]);

            return response()->json(['isError' => false, 'Message' => 'Email sent successfully.']);
        }
    }

    public function sendTest(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string',
            'content_html' => 'required|string',
        ]);

        Mail::to($request->email)->send(new DynamicEmail($request->subject, $request->content_html, ['name' => 'Test User']));

        return response()->json(['success' => true, 'message' => 'Test email sent successfully to ' . $request->email]);
    }

    public function validateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $isValidFormat = filter_var($email, FILTER_VALIDATE_EMAIL);

        if (!$isValidFormat) {
            return response()->json([
                'success' => true,
                'valid' => false,
                'message' => 'Invalid email format.'
            ]);
        }

        // Real Mailbox Verification using AbstractAPI (Free Tier)
        // This physically connects to the SMTP server (like Gmail) and asks if the mailbox exists
        try {
            // Free API Key for Email Validation (Abstract API)
            $apiKey = '5a6538af3e0b4b2fb4e0bfa9da1d2f63';
            $response = \Illuminate\Support\Facades\Http::timeout(5)->withoutVerifying()->get("https://emailvalidation.abstractapi.com/v1/?api_key={$apiKey}&email={$email}");

            if ($response->successful()) {
                $data = $response->json();

                // Abstract API returns "DELIVERABLE" if the mailbox exists
                $isDeliverable = isset($data['deliverability']) && $data['deliverability'] === 'DELIVERABLE';
                $isCatchall = isset($data['is_catchall_email']) && $data['is_catchall_email']['value'] === true;
                $isValidSMTP = isset($data['is_smtp_valid']) && $data['is_smtp_valid']['value'] === true;

                // Gmail is strict about is_smtp_valid
                if ($isDeliverable || ($isCatchall && $isValidSMTP)) {
                    return response()->json([
                        'success' => true,
                        'valid' => true,
                        'message' => 'Valid and deliverable email.'
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'valid' => false,
                        'message' => 'This email address does not exist.'
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Fallback if API is down or rate limited: Just check MX
            \Illuminate\Support\Facades\Log::error('Abstract API Error: ' . $e->getMessage());
        }

        // Fallback: Check MX Records
        $domain = substr(strrchr($email, "@"), 1);
        if (checkdnsrr($domain, "MX")) {
            return response()->json([
                'success' => true,
                'valid' => true, // Proceed anyway if API fails but domain is real
                'message' => 'Domain is valid (Mailbox unverified).'
            ]);
        }

        return response()->json([
            'success' => true,
            'valid' => false,
            'message' => 'Email domain does not exist or cannot receive mail.'
        ]);
    }
}
