<?php

namespace App\Http\Controllers\Common\SystemSettings;

use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Jobs\RefreshConfigCache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Common\SystemSettingStoreRequest;

class SystemSettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all()->pluck('value', 'key');
        return response()->json($settings);
    }

    public function storeOrUpdate(SystemSettingStoreRequest $request)
    {
        $settingsData = $request->all();

        foreach ($settingsData as $setting) {
            // Update the setting in the database and clear the cache automatically
            SystemSetting::setValue($setting['key'], $setting['value']);
        }

        // Return success response
        return response()->json([
            'message' => 'System settings saved successfully and applied immediately!',
        ], 200);
    }



    public function clearCache()
    {
        try {
            // Clear and cache config
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('config:cache');

            return response()->json([
                'message' => 'Cache cleared and configuration refreshed successfully!',
            ], 200);
        } catch (\Exception $e) {
            // Log the error and return a failure response
            \Log::error('Error clearing cache: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to clear cache.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
