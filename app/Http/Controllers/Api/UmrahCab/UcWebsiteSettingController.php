<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcWebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UcWebsiteSettingController extends Controller
{
    /**
     * Helper to format setting values (especially image URLs).
     */
    private function formatSettings(Request $request)
    {
        $settings = Cache::remember('uc_raw_website_settings', 86400, function () {
            return UcWebsiteSetting::all()->pluck('value', 'key')->toArray();
        });

        $formatted = [];
        $appUrl = rtrim($request->schemeAndHttpHost() ?: (config('app.url') ?: 'http://localhost:8000'), '/');
        $host = strtolower($request->getHost());

        // Check if current web environment serves uploads under /public/uploads/ or /uploads/
        $isProjectRootWeb = !str_contains($host, 'localhost') && !str_contains($host, '127.0.0.1');

        foreach ($settings as $key => $value) {
            if (is_string($value)) {
                if (preg_match('#(?:https?://[^/]+)?((?:/public)?/uploads/.*)$#i', $value, $matches)) {
                    $uploadPath = $matches[1];
                    $cleanUploadPath = preg_replace('#^/public/#i', '/', $uploadPath);

                    $relativePath = ($isProjectRootWeb ? '/public' : '') . $cleanUploadPath;
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
     * Highly optimized for frontend with fast caching & 304 Not Modified support.
     */
    public function index(Request $request)
    {
        $data = $this->formatSettings($request);
        $json = json_encode($data);
        $etag = md5($json);

        // Check client ETag for instant 304 response
        if ($request->header('If-None-Match') === $etag) {
            return response('', 304)->header('ETag', $etag);
        }

        return response($json, 200, [
            'Content-Type' => 'application/json; charset=utf-8',
            'ETag' => $etag,
            'Cache-Control' => 'public, max-age=60, stale-while-revalidate=300'
        ]);
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
                    $uploadDir = public_path('uploads/settings');
                    if (!file_exists($uploadDir)) {
                        @mkdir($uploadDir, 0755, true);
                    }
                    
                    $file->move($uploadDir, $filename);
                    @chmod($uploadDir . '/' . $filename, 0644);

                    // cPanel / Shared hosting sync: if DOCUMENT_ROOT or public_html is different from public_path
                    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') : null;
                    if ($docRoot && realpath($docRoot) !== realpath(public_path())) {
                        $docRootTarget = $docRoot . '/uploads/settings';
                        if (!file_exists($docRootTarget)) {
                            @mkdir($docRootTarget, 0755, true);
                        }
                        @copy($uploadDir . '/' . $filename, $docRootTarget . '/' . $filename);
                        @chmod($docRootTarget . '/' . $filename, 0644);
                    }

                    $publicHtmlDir = base_path('public_html/uploads/settings');
                    if (file_exists(base_path('public_html')) && realpath(base_path('public_html')) !== realpath(public_path())) {
                        if (!file_exists($publicHtmlDir)) {
                            @mkdir($publicHtmlDir, 0755, true);
                        }
                        @copy($uploadDir . '/' . $filename, $publicHtmlDir . '/' . $filename);
                        @chmod($publicHtmlDir . '/' . $filename, 0644);
                    }

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

        // Invalidate backend cache
        Cache::forget('uc_raw_website_settings');

        return response()->json([
            'success' => true,
            'message' => 'Website settings saved successfully!',
            'data' => $this->formatSettings($request)
        ], 200);
    }
}
