<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Stripe\StripeObject;
use App\Helpers\Handlers\PlanSubscriptionHandler;

class StripeWebhookRouter
{
    /**
     * Dispatch Stripe webhook events to appropriate handlers
     *
     * @param string $eventType
     * @param StripeObject $object
     */
    public static function dispatch(string $eventType, StripeObject $object): void
    {
        match ($eventType) {
            // Checkout Session Events
            'checkout.session.completed' => self::handleCheckoutSessionCompleted($object),
            'checkout.session.expired' => self::handleCheckoutSessionExpired($object),

            // Invoice Events (Recurring Payments)
            'invoice.payment_succeeded' => self::handleInvoicePaid($object),
            'invoice.payment_failed' => self::handleInvoiceFailed($object),
            'invoice.upcoming' => self::handleInvoiceUpcoming($object),

            // Subscription Events
            'customer.subscription.created' => self::handleSubscriptionCreated($object),
            'customer.subscription.updated' => self::handleSubscriptionUpdated($object),
            'customer.subscription.deleted' => self::handleSubscriptionDeleted($object),

            // Payment Intent Events (One-time payments)
            'payment_intent.succeeded' => self::handlePaymentIntentSucceeded($object),
            'payment_intent.payment_failed' => self::handlePaymentIntentFailed($object),

            // Default: unhandled event
            default => Log::info("Unhandled Stripe event type: $eventType"),
        };
    }

    // -------------------------------
    // Checkout Session Handlers
    // -------------------------------
    protected static function handleCheckoutSessionCompleted(StripeObject $session): void
    {
        $paymentType = $session->metadata->payment_type ?? null;

        if (!$paymentType) {
            Log::warning("Missing payment_type in metadata for session: {$session->id}");
            return;
        }

        match ($paymentType) {
            'plan_subscription' => PlanSubscriptionHandler::handle($session),
            // 'product_purchase' => ProductPurchaseHandler::handle($session),
            default => Log::warning("Unhandled payment_type: $paymentType"),
        };
    }

    protected static function handleCheckoutSessionExpired(StripeObject $session): void
    {
        Log::info("Checkout session expired: {$session->id}");
    }

    // -------------------------------
    // Invoice Handlers (Recurring Payments)
    // -------------------------------
    protected static function handleInvoicePaid(StripeObject $invoice): void
    {
        $paymentType = $invoice->metadata->payment_type ?? null;

        if ($paymentType === 'plan_subscription') {
            PlanSubscriptionHandler::handle($invoice);
        } else {
            Log::info("Invoice payment succeeded: {$invoice->id}, no handler for payment_type: {$paymentType}");
        }
    }

    protected static function handleInvoiceFailed(StripeObject $invoice): void
    {
        $paymentType = $invoice->metadata->payment_type ?? null;

        if ($paymentType === 'plan_subscription') {
            PlanSubscriptionHandler::handle($invoice);
        } else {
            Log::error("Invoice payment failed: {$invoice->id}, no handler for payment_type: {$paymentType}");
        }
    }

    protected static function handleInvoiceUpcoming(StripeObject $invoice): void
    {
        Log::info("Upcoming invoice: {$invoice->id}");
    }

    // -------------------------------
    // Subscription Handlers
    // -------------------------------
    protected static function handleSubscriptionCreated(StripeObject $subscription): void
    {
        $paymentType = $subscription->metadata->payment_type ?? null;

        if ($paymentType === 'plan_subscription') {
            PlanSubscriptionHandler::handle($subscription);
        } else {
            Log::info("Subscription created: {$subscription->id}, no handler for payment_type: {$paymentType}");
        }
    }

    protected static function handleSubscriptionUpdated(StripeObject $subscription): void
    {
        $paymentType = $subscription->metadata->payment_type ?? null;

        if ($paymentType === 'plan_subscription') {
            PlanSubscriptionHandler::handle($subscription);
        } else {
            Log::info("Subscription updated: {$subscription->id}, no handler for payment_type: {$paymentType}");
        }
    }

    protected static function handleSubscriptionDeleted(StripeObject $subscription): void
    {
        $paymentType = $subscription->metadata->payment_type ?? null;

        Log::info("Subscription deleted: {$subscription->id}, payment_type: {$paymentType}");
        if ($paymentType === 'plan_subscription') {
            PlanSubscriptionHandler::handle($subscription);
        } else {
            Log::info("Subscription deleted: {$subscription->id}, no handler for payment_type: {$paymentType}");
        }
    }

    // -------------------------------
    // Payment Intent Handlers (One-time payments)
    // -------------------------------
    protected static function handlePaymentIntentSucceeded(StripeObject $paymentIntent): void
    {
        $paymentType = $paymentIntent->metadata->payment_type ?? null;

        if ($paymentType === 'plan_subscription') {
            PlanSubscriptionHandler::handle($paymentIntent);
        } else {
            Log::info("PaymentIntent succeeded: {$paymentIntent->id}, no handler for payment_type: {$paymentType}");
        }
    }

    protected static function handlePaymentIntentFailed(StripeObject $paymentIntent): void
    {
        $paymentType = $paymentIntent->metadata->payment_type ?? null;

        if ($paymentType === 'plan_subscription') {
            PlanSubscriptionHandler::handle($paymentIntent);
        } else {
            Log::error("PaymentIntent failed: {$paymentIntent->id}, no handler for payment_type: {$paymentType}");
        }
    }
}
