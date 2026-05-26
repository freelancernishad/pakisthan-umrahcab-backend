<?php

namespace App\Http\Controllers\Common\Media;


use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Media\MediaFile;
use App\Helpers\HelpersFunctions;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Media\MediaFileVersion;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\MediaFileResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\MediaFileCollection;
use App\Services\FileSystem\FileUploadService;
use App\Http\Resources\MediaFileExportCollection;
use App\Http\Requests\Common\Media\MediaUploadRequest;


class MediaController extends Controller
{

public function index(Request $request)
{
    $query = MediaFile::with(['versions' => function ($q) use ($request) {
        if ($request->filled('versions')) {
            $labels = is_array($request->versions) ? $request->versions : explode(',', $request->versions);
            $q->whereIn("label", $labels);
        }
    }])->latest();




    // Role-based filtering
    if (Auth::guard('api')->check()) {
        // Users see only their own uploads
        $query->where('uploaded_by_user_id', Auth::guard('api')->id());

    } elseif (Auth::guard('admin')->check()) {
        // Admins
        if ($request->filled('uploaded_by_user_id')) {
            $query->where('uploaded_by_user_id', $request->uploaded_by_user_id);
        } elseif (!$request->boolean('view_all')) {
            $query->where('uploaded_by_admin_id', Auth::guard('admin')->id());
        }
        // else: view_all=true, show everything

    } elseif (Auth::guard('hotel')->check()) {
        // Hotels see only their own uploads
        $query->where('uploader_type', 'App\Models\HotelManagement\Hotel')
              ->where('uploader_id', Auth::guard('hotel')->id());

    } else {
        // Unauthenticated or unknown
        $query->whereNull('uploaded_by_user_id')
              ->whereNull('uploaded_by_admin_id')
              ->whereNull('uploader_id');
    }

    // Filter by MediaFile name
    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    // Filter by original version properties
    $query->whereHas('versions', function ($q) use ($request) {
        if ($request->filled('size')) {
            $q->where('size', $request->size);
        }

        if ($request->filled('size_type')) {
            $q->where('size_type', 'like', '%' . $request->size_type . '%');
        }

        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }

        if ($request->filled('dimensions')) {
            $q->where('dimensions', $request->dimensions);
        }
    });

    // Sorting
    if ($request->filled('sort_by') && in_array($request->sort_by, ['name', 'size', 'dimensions'])) {
        $sortBy = $request->sort_by;

        if ($sortBy === 'name') {
            $query->orderBy('name', $request->input('sort_order', 'asc'));
        } else {
            // Sorting by size or dimensions from original version
            $query->join('media_file_versions as v', function ($join) {
                $join->on('media_files.id', '=', 'v.media_file_id');
            });

            $query->orderBy('v.' . $sortBy, $request->input('sort_order', 'asc'))
                  ->select('media_files.*');
        }
    }

    // Dynamic per_page, default 20
    $perPage = $request->input('per_page', 20);
    $mediaFiles = $query->paginate($perPage);

    if($request->res=='v2'){
        return response()->json([
            'data' => new MediaFileExportCollection($mediaFiles),
        ]);
    }
    return response()->json([
        'data' => new MediaFileCollection($mediaFiles),
    ]);
}





public function show($id)
{
    $media = MediaFile::with('versions')->find($id);

    if (!$media) {
        return response()->json([
            'Message' => 'Media file not found',
        ], 404);
    }

    return response()->json([
        'data' => new MediaFileResource($media),
    ]);
}


    public function upload(MediaUploadRequest $request)
    {

    if (!extension_loaded('gd')) {
        Log::error("GD library is NOT available!");
        return response()->json(['Message' => 'GD library not available'], 500);
    }

    // Determine file source
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    } else {
        // Handle remote file
        $response = Http::get($request->file_url);

        if (!$response->successful()) {
            return response()->json(['message' => 'Failed to fetch image from URL'], 400);
        }

        // Guess extension from content-type
        $contentType = $response->header('Content-Type');
        $extension = match ($contentType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg',
        };

        $originalName = pathinfo(parse_url($request->file_url, PHP_URL_PATH), PATHINFO_FILENAME);
        $tempPath = storage_path('app/temp_' . Str::random(10) . '.' . $extension);
        file_put_contents($tempPath, $response->body());

        // Create UploadedFile instance from temp file
        $file = new \Illuminate\Http\UploadedFile(
            $tempPath,
            basename($tempPath),
            $contentType,
            null,
            true
        );
    }

    $filename = uniqid() . '.' . $extension;

    // Track old uploader fields
    $uploaded_by_user_id = Auth::guard('user')->id() ?? null;
    $uploaded_by_admin_id = Auth::guard('admin')->id() ?? null;



    // Track polymorphic uploader
    $uploader = HelpersFunctions::jwtDecode('model');
    $uploader_id = HelpersFunctions::jwtDecode('id');

    // Upload original to S3
    $originalUrl = (new FileUploadService())->uploadFileToS3($file, 'uploads/images');

    // Save media metadata
    $media = new MediaFile([
        'name' => $originalName,
        'original_url' => $originalUrl,
        'uploaded_by_user_id' => $uploaded_by_user_id,
        'uploaded_by_admin_id' => $uploaded_by_admin_id,
    ]);

    if ($uploader) {
        $media->uploader()->associate($uploader::find($uploader_id));
    }

    $media->save();

    // Image sizes
    $sizes = [
        'original' => null,
        'thumbnail' => [150, 150],
        'medium' => [300, 300],
        'large' => [600, 600],
        // 'extra_large' => [1200, 1200],
        // 'xlarge' => [1800, 1800],
        'square_50' => [50, 50],
        'square_100' => [100, 100],
        'square_200' => [200, 200],
        // 'square_400' => [400, 400],
        // 'square_600' => [600, 600],
        // 'square_800' => [800, 800],
        // 'square_1000' => [1000, 1000],
    ];

    foreach ($sizes as $label => $dimensions) {
        if ($label === 'original') {
            $sizeInBytes = $file->getSize();
            $sizeType = $this->formatSize($sizeInBytes);
            [$width, $height] = getimagesize($file->getPathname());
            $url = $originalUrl;
        } else {
            if (str_starts_with($label, 'square_')) {
                [$resizedContent, $width, $height] = $this->resizeImageGDSquare(
                    $file->getPathname(),
                    $dimensions[0],
                    $extension
                );
            } else {
                [$resizedContent, $width, $height] = $this->resizeImageGDKeepRatio(
                    $file->getPathname(),
                    $dimensions[0],
                    $dimensions[1],
                    $extension
                );
            }

            $url = (new FileUploadService())->uploadContentToS3(
                $resizedContent,
                'uploads/images/' . $label . '_' . $filename
            );

            $sizeInBytes = strlen($resizedContent);
            $sizeType = $this->formatSize($sizeInBytes);
        }

        MediaFileVersion::create([
            'media_file_id' => $media->id,
            'label' => $label,
            'url' => $url,
            'size' => $sizeInBytes,
            'size_type' => $sizeType,
            'type' => $file->getMimeType(),
            'dimensions' => $width . 'x' . $height,
        ]);
    }

    // Clean up temp file if URL used
    if (isset($tempPath)) {
        @unlink($tempPath);
    }

    $media->load('versions');

    return response()->json([
        'data' => new MediaFileResource($media),
    ]);
}





    private function formatSize($bytes)
    {
        $kb = 1024;
        $mb = 1024 * 1024;
        if ($bytes >= $mb) return round($bytes / $mb, 2) . ' MB';
        if ($bytes >= $kb) return round($bytes / $kb, 2) . ' KB';
        return $bytes . ' bytes';
    }

    // Keep aspect ratio
    private function resizeImageGDKeepRatio($filePath, $maxWidth, $maxHeight, $extension)
    {
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $src = imagecreatefromjpeg($filePath);
                break;
            case 'png':
                $src = imagecreatefrompng($filePath);
                break;
            case 'gif':
                $src = imagecreatefromgif($filePath);
                break;
            default:
                throw new \Exception("Unsupported image type: $extension");
        }

        $origWidth = imagesx($src);
        $origHeight = imagesy($src);
        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
        $newWidth = (int)($origWidth * $ratio);
        $newHeight = (int)($origHeight * $ratio);

        $tmp = imagecreatetruecolor($newWidth, $newHeight);

        if ($extension === 'png' || $extension === 'gif') {
            imagecolortransparent($tmp, imagecolorallocatealpha($tmp, 0, 0, 0, 127));
            imagealphablending($tmp, false);
            imagesavealpha($tmp, true);
        }

        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        ob_start();
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($tmp, null, 90);
                break;
            case 'png':
                imagepng($tmp);
                break;
            case 'gif':
                imagegif($tmp);
                break;
        }
        $imageData = ob_get_clean();

        imagedestroy($src);
        imagedestroy($tmp);

        return [$imageData, $newWidth, $newHeight];
    }

    // Exact square resize
    private function resizeImageGDSquare($filePath, $size, $extension)
    {
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $src = imagecreatefromjpeg($filePath);
                break;
            case 'png':
                $src = imagecreatefrompng($filePath);
                break;
            case 'gif':
                $src = imagecreatefromgif($filePath);
                break;
            default:
                throw new \Exception("Unsupported image type: $extension");
        }

        $tmp = imagecreatetruecolor($size, $size);

        if ($extension === 'png' || $extension === 'gif') {
            imagecolortransparent($tmp, imagecolorallocatealpha($tmp, 0, 0, 0, 127));
            imagealphablending($tmp, false);
            imagesavealpha($tmp, true);
        }

        $origWidth = imagesx($src);
        $origHeight = imagesy($src);

        // Center crop for square
        $minSide = min($origWidth, $origHeight);
        $srcX = (int)(($origWidth - $minSide) / 2);
        $srcY = (int)(($origHeight - $minSide) / 2);

        imagecopyresampled($tmp, $src, 0, 0, $srcX, $srcY, $size, $size, $minSide, $minSide);

        ob_start();
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($tmp, null, 90);
                break;
            case 'png':
                imagepng($tmp);
                break;
            case 'gif':
                imagegif($tmp);
                break;
        }
        $imageData = ob_get_clean();

        imagedestroy($src);
        imagedestroy($tmp);

        return [$imageData, $size, $size];
    }
}
