<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendBulkEmail implements ShouldQueue
{
    use Queueable;

    public $recipients;
    public $subject;
    public $content;
    public $templateId;

    /**
     * Create a new job instance.
     */
    public function __construct($recipients, $subject, $content, $templateId = null)
    {
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->content = $content;
        $this->templateId = $templateId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->recipients as $recipient) {
            \Mail::to($recipient['email'])->send(new \App\Mail\DynamicEmail($this->subject, $this->content, ['name' => $recipient['name']]));
            
            // Log the email
            \App\Models\EmailLog::create([
                'recipient' => $recipient['email'],
                'subject' => $this->subject,
                'status' => 'sent',
                'template_id' => $this->templateId,
            ]);
        }
    }
}
