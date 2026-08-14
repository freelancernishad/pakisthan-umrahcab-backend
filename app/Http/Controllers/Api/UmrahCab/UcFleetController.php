<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcFleet;
use Illuminate\Http\Request;

class UcFleetController extends Controller
{
    public function index()
    {
        return response()->json(UcFleet::orderBy('id', 'asc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model' => 'required|string|unique:uc_fleet,model',
            'count' => 'required|integer|min:0',
            'active' => 'required|integer|min:0',
            'capacity' => 'nullable|integer|min:1',
            'luggage' => 'nullable|integer|min:0',
            'image' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'fleet_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('uploads/fleet');
            if (!file_exists($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $filename);
            @chmod($uploadDir . '/' . $filename, 0644);

            $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') : null;
            if ($docRoot && realpath($docRoot) !== realpath(public_path())) {
                $docRootTarget = $docRoot . '/uploads/fleet';
                if (!file_exists($docRootTarget)) {
                    @mkdir($docRootTarget, 0755, true);
                }
                @copy($uploadDir . '/' . $filename, $docRootTarget . '/' . $filename);
                @chmod($docRootTarget . '/' . $filename, 0644);
            }

            $publicHtmlDir = base_path('public_html/uploads/fleet');
            if (file_exists(base_path('public_html')) && realpath(base_path('public_html')) !== realpath(public_path())) {
                if (!file_exists($publicHtmlDir)) {
                    @mkdir($publicHtmlDir, 0755, true);
                }
                @copy($uploadDir . '/' . $filename, $publicHtmlDir . '/' . $filename);
                @chmod($publicHtmlDir . '/' . $filename, 0644);
            }

            $validated['image'] = '/uploads/fleet/' . $filename;
        }

        $fleet = UcFleet::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'New vehicle added to fleet!',
            'data' => $fleet
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'count' => 'required|integer',
            'active' => 'required|integer',
            'capacity' => 'nullable|integer|min:1',
            'luggage' => 'nullable|integer|min:0',
            'image' => 'nullable',
        ]);

        $fleet = UcFleet::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($fleet->image) {
                $oldPath = public_path(ltrim($fleet->image, '/'));
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }

                $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') : null;
                if ($docRoot && realpath($docRoot) !== realpath(public_path())) {
                    $oldDocRootPath = $docRoot . $fleet->image;
                    if (file_exists($oldDocRootPath)) {
                        @unlink($oldDocRootPath);
                    }
                }

                $publicHtmlDir = base_path('public_html/uploads/fleet');
                if (file_exists(base_path('public_html')) && realpath(base_path('public_html')) !== realpath(public_path())) {
                    $oldPublicHtmlPath = base_path('public_html' . $fleet->image);
                    if (file_exists($oldPublicHtmlPath)) {
                        @unlink($oldPublicHtmlPath);
                    }
                }
            }

            $file = $request->file('image');
            $filename = 'fleet_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('uploads/fleet');
            if (!file_exists($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $filename);
            @chmod($uploadDir . '/' . $filename, 0644);

            $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') : null;
            if ($docRoot && realpath($docRoot) !== realpath(public_path())) {
                $docRootTarget = $docRoot . '/uploads/fleet';
                if (!file_exists($docRootTarget)) {
                    @mkdir($docRootTarget, 0755, true);
                }
                @copy($uploadDir . '/' . $filename, $docRootTarget . '/' . $filename);
                @chmod($docRootTarget . '/' . $filename, 0644);
            }

            $publicHtmlDir = base_path('public_html/uploads/fleet');
            if (file_exists(base_path('public_html')) && realpath(base_path('public_html')) !== realpath(public_path())) {
                if (!file_exists($publicHtmlDir)) {
                    @mkdir($publicHtmlDir, 0755, true);
                }
                @copy($uploadDir . '/' . $filename, $publicHtmlDir . '/' . $filename);
                @chmod($publicHtmlDir . '/' . $filename, 0644);
            }

            $validated['image'] = '/uploads/fleet/' . $filename;
        }

        $fleet->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Fleet allocation updated!',
            'data' => $fleet
        ]);
    }

    public function destroy($id)
    {
        $fleet = UcFleet::findOrFail($id);
        $fleet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle removed from fleet!'
        ]);
    }
}
