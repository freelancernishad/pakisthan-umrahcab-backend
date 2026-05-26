<?php

namespace App\Http\Controllers\Admin\Subscriptions;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Plan\PlanSubscription;
use Illuminate\Support\Facades\Validator;


class PlanSubscriptionsController extends Controller
{
    public function getAllSubscriptions()
    {
        $subscriptions = PlanSubscription::with('user', 'plan')
            ->latest('created_at')
            ->paginate(20);

        return response()->json($subscriptions);
    }
    public function getAllPayments()
    {
        $payments = Payment::with('user', 'payable')
            ->latest('created_at')
            ->paginate(20);

        return response()->json($payments);
    }

    public function cancelSubscription($id)
    {
        $subscription = PlanSubscription::findOrFail($id);

        if ($subscription->ends_at && $subscription->ends_at->isPast()) {
             return response()->json(['message' => 'Subscription is already expired or cancelled.'], 400);
        }
        
        $subscription->ends_at = now();
        $subscription->save();

        return response()->json(['message' => 'Subscription cancelled successfully.']);
    }



}
