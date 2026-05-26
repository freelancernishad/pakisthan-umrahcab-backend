<?php


use Illuminate\Http\Request;
use App\Models\UserActivityLog;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

if (!function_exists('logUserActivity')) {
    function logUserActivity(
        string $activity, 
        string $category = null, 
        ?int $userId = null, 
        ?Request $request = null, 
        bool $isSuccess = true, 
        array $extraDetails = []
    ) {
        $agent = new Agent();
        $request = $request ?? request();

        UserActivityLog::create([
            'user_id' => $userId ?? (Auth::check() ? Auth::user()->id : null),
            'activity' => $activity,
            'category' => $category,
            'ip' => $request->ip(),
            'device' => $agent->device(),
            'platform' => $agent->platform(),
            'browser' => $agent->browser(),
            'details' => array_merge([
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
            ], $extraDetails),
            'is_success' => $isSuccess,
        ]);
    }
}
