<?php

namespace App\Http\Controllers\Admin\Plans;


use App\Models\Plan\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Plans\AdminPlanStoreRequest;

class PlanController extends Controller
{
    // Fetch all plans (list of plans)
    public function index()
    {
        $plans = Plan::orderBy('created_at', 'desc')->get(); 
        
        $user = auth('user')->user();
        
        // If no user found via standard auth, try to parse token explicitly (since route is public)
        // If no user found via standard auth, try to parse token explicitly (since route is public)
        $token = request()->bearerToken();
        \Illuminate\Support\Facades\Log::info("Plan list check - Bearer Token: " . ($token ? substr($token, 0, 10) . '...' : 'None'));

        if (!$user && $token) {
            try {
                // Explicitly set the token
                \Tymon\JWTAuth\Facades\JWTAuth::setToken($token);
                if ($payload = \Tymon\JWTAuth\Facades\JWTAuth::getPayload()) {
                    $user = \App\Models\User::find($payload->get('sub'));
                    \Illuminate\Support\Facades\Log::info("Plan list check - User found via token: " . ($user ? $user->id : 'Null'));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Plan list check - Token parsing failed: " . $e->getMessage());
            }
        }
        
        $activePlanId = null;
        if ($user) {
            $activeSub = $user->planSubscriptions()
                ->where('status', 'active')
                ->latest('start_date')
                ->first();
            $activePlanId = $activeSub ? $activeSub->plan_id : null;
            
            \Illuminate\Support\Facades\Log::info("Plan list check - User: {$user->id}, Active Plan ID: " . ($activePlanId ?? 'None'));
        } else {
            \Illuminate\Support\Facades\Log::info("Plan list check - No user found");
        }

        // Transform to array to ensure is_active is included in JSON
        $plansData = $plans->map(function ($plan) use ($activePlanId, $user) {
            $data = $plan->toArray();
            $data['is_active'] = $activePlanId && $activePlanId == $plan->id;
            
            // Calculate Proration Data for Frontend
            $data['proration_credit'] = 0;
            $data['pay_today'] = $plan->discounted_price; // Default
            $data['is_downgrade_blocked'] = false;

            if ($user) {
                $activeSub = $user->planSubscriptions()
                    ->where('status', 'active')
                    ->latest('start_date')
                    ->first();
                
                if ($activeSub && $activeSub->plan_id != $plan->id) {
                     $startDate = \Carbon\Carbon::parse($activeSub->start_date);
                     $endDate = \Carbon\Carbon::parse($activeSub->end_date);
                     $totalDays = $startDate->diffInDays($endDate);
                     if ($totalDays == 0) $totalDays = 1;
                     
                     $remainingDays = now()->diffInDays($endDate, false);
                     
                     if ($remainingDays > 0) {
                         $amountPaid = $activeSub->final_amount;
                         $dailyRate = $amountPaid / $totalDays;
                         $unusedValue = round($dailyRate * $remainingDays, 2);
                         
                         $data['proration_credit'] = $unusedValue;
                         $data['pay_today'] = max(0, $plan->discounted_price - $unusedValue);
                         
                         // Block downgrade if unused value exceeds new plan price
                         if ($unusedValue > $plan->discounted_price) {
                             $data['is_downgrade_blocked'] = true;
                         }
                     }
                }
            }

            return $data;
        });

        return response()->json([
            'plans' => $plansData
        ]);
    }

    // Fetch a single plan by ID
    public function show($id)
    {
        $plan = Plan::find($id); // Find plan by ID

        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        return response()->json($plan->makeVisible('features'));
    }

    // Create a new plan
    public function store(AdminPlanStoreRequest $request)
    {

    $plan = Plan::create([
        'name' => $request->name,
        'duration' => $request->duration,
        'original_price' => $request->original_price,
        'monthly_price' => $request->monthly_price,
        'discount_percentage' => $request->discount_percentage,
        'features' => $request->features, // stored as JSON array
    ]);

    return response()->json([
        'message' => 'Plan created successfully',
        'plan' => $plan->makeVisible('features'),
    ], 201);
}


    public function update(AdminPlanStoreRequest $request, $id)
{
    $plan = Plan::find($id);

    // Update the plan
    $plan->update([
        'name' => $request->name,
        'duration' => $request->duration,
        'original_price' => $request->original_price,
        'monthly_price' => $request->monthly_price,
        'discount_percentage' => $request->discount_percentage,
        'features' => $request->features,
    ]);

    return response()->json([
        'message' => 'Plan updated successfully',
        'plan' => $plan->makeVisible('features'),
    ]);
}


    // Delete a plan
    public function destroy($id)
    {
        $plan = Plan::find($id); // Find plan by ID

        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        $plan->delete(); // Delete the plan
        return response()->json(['message' => 'Plan deleted successfully']);
    }
}
