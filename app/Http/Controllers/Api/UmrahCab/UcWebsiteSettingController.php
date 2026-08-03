<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcWebsiteSetting;
use Illuminate\Http\Request;

class UcWebsiteSettingController extends Controller
{
    /**
     * Helper to format setting values (especially image URLs).
     */
    private function formatSettings(Request $request)
    {
        $settings = UcWebsiteSetting::all()->pluck('value', 'key');
        $formatted = [];
        $appUrl = rtrim($request->schemeAndHttpHost() ?: (config('app.url') ?: 'http://localhost:8000'), '/');

        foreach ($settings as $key => $value) {
            if (is_string($value)) {
                if (preg_match('#(?:https?://[^/]+)?(/uploads/.*)$#i', $value, $matches)) {
                    $relativePath = $matches[1];
                    $formatted[$key] = $appUrl . $relativePath;
                    $formatted[$key . '_relative'] = $relativePath;
                } else {
                    $formatted[$key] = $value;
                }
            } else {
                $formatted[$key] = $value;
            }
        }

        return $formatted;
    }

    /**
     * Get all website settings as key-value pairs.
     */
    public function index(Request $request)
    {
        return response()->json($this->formatSettings($request));
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
            'data' => $this->formatSettings($request)
        ], 200);
    }
}
