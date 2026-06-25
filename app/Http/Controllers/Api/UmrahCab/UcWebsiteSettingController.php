<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcWebsiteSetting;
use Illuminate\Http\Request;

class UcWebsiteSettingController extends Controller
{
    /**
     * Get all website settings as key-value pairs.
     */
    public function index()
    {
        $settings = UcWebsiteSetting::all()->pluck('value', 'key');
        
        $formattedSettings = [];
        $appUrl = config('app.url') ?: 'http://localhost:8000';
        
        foreach ($settings as $key => $value) {
            if (is_string($value) && str_starts_with($value, '/uploads/')) {
                $formattedSettings[$key] = rtrim($appUrl, '/') . $value;
                // Also save the relative path so the frontend can check or reuse it if needed
                $formattedSettings[$key . '_relative'] = $value;
            } else {
                $formattedSettings[$key] = $value;
            }
        }
        
        return response()->json($formattedSettings);
    }

    /**
     * Store or update website settings.
     */
    public function storeOrUpdate(Request $request)
    {
        $allData = $request->all();

        foreach ($allData as $key => $value) {
            if (empty($key)) continue;

            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $filename = $key . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Upload to S3 if configured, otherwise fall back to local disk
                if (config('filesystems.disks.s3.key') && config('filesystems.disks.s3.secret') && config('filesystems.disks.s3.bucket')) {
                    $path = \Illuminate\Support\Facades\Storage::disk('s3')->putFileAs('settings', $file, $filename);
                    $url = \Illuminate\Support\Facades\Storage::disk('s3')->url($path);
                } else {
                    $file->move(public_path('uploads/settings'), $filename);
                    $url = '/uploads/settings/' . $filename;
                }
                
                UcWebsiteSetting::setValue($key, $url);
            } else {
                if ($value === 'null' || $value === 'undefined') {
                    $value = null;
                }
                UcWebsiteSetting::setValue($key, $value);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Website settings saved successfully!',
            'data' => UcWebsiteSetting::all()->pluck('value', 'key')
        ], 200);
    }
}
