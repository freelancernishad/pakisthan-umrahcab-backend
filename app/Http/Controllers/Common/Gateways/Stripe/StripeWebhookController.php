<?php

namespace App\Http\Controllers\Common\Gateways\Stripe;

use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Plan;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\PlanSubscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\StripeWebhookRouter;
use App\Http\Controllers\Controller;

class StripeWebhookController extends Controller
{
    protected $webhookService;

    public function __construct(\FreelancerNishad\Stripe\Services\StripeWebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        return $this->webhookService->handleWebhook($payload, $sigHeader);
    }
}
