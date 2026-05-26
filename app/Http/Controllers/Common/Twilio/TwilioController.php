<?php

namespace App\Http\Controllers\Common\Twilio;
use App\Http\Controllers\Controller;

use App\Services\Twilio\TwilioService;
use App\Http\Requests\Common\Twilio\TwilioSendRequest;

class TwilioController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    /**
     * Send SMS globally
     */
    public function send(TwilioSendRequest $request)
    {

        $sent = $this->twilio->sendSMS($request->phone, $request->message);

        return response()->json([
            'success' => $sent,
            'message' => $sent ? 'SMS sent successfully!' : 'Failed to send SMS'
        ]);
    }
}
