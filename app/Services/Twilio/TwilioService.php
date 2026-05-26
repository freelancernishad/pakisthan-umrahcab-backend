<?php

namespace App\Services\Twilio;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.auth_token') ?? config('services.twilio.token');
        $this->client = new Client($sid, $token);
    }

    /**
     * Send SMS globally
     *
     * @param string $to   Recipient number with country code, e.g. +8801XXXXXXXXX
     * @param string $message
     * @return bool
     */
    public function sendSMS(string $to, string $message): bool
    {
        Log::info("Client: " . json_encode($this->client));
        try {
            $fromNumber = config('services.twilio.phone_number') ?? config('services.twilio.from');
            $this->client->messages->create($to, [
                'from' => $fromNumber,
                'body' => $message
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Twilio SMS Error: ' . $e->getMessage());
            return false;
        }
    }
}
