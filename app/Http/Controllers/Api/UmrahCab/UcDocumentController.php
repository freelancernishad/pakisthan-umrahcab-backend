<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcDocument;
use App\Models\UmrahCab\UcCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UcDocumentController extends Controller
{
    public function index(Request $request)
    {
        $customerId = $request->query('customer_id');
        $query = UcDocument::orderBy('id', 'desc');

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($request->has('page')) {
            $perPage = $request->query('per_page', 10);
            return response()->json($query->paginate($perPage));
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:uc_customers,id',
            'title' => 'nullable|string',
            'document_file' => 'required|file|mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx|max:10240', // 10MB max
        ]);

        $file = $request->file('document_file');
        $originalName = $file->getClientOriginalName();
        $title = $validated['title'] ?: pathinfo($originalName, PATHINFO_FILENAME);
        $fileType = $file->getClientOriginalExtension();
        $filename = 'doc_' . time() . '_' . uniqid() . '.' . $fileType;

        // Save to public disk
        $file->move(public_path('uploads/documents'), $filename);
        $filePath = '/uploads/documents/' . $filename;

        // Determine who uploaded it
        $uploadedBy = 'Admin';
        if (Auth::guard('api')->check()) {
            $uploadedBy = Auth::guard('api')->user()->name ?? 'Agent';
        } elseif (Auth::guard('admin')->check()) {
            $uploadedBy = Auth::guard('admin')->user()->name ?? 'Admin';
        }

        $document = UcDocument::create([
            'customer_id' => $validated['customer_id'],
            'title' => $title,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'uploaded_by' => $uploadedBy,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully!',
            'data' => $document
        ], 201);
    }

    public function destroy($id)
    {
        $document = UcDocument::findOrFail($id);

        // Delete from public folder if exists
        $filePath = public_path($document->file_path);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully!'
        ]);
    }

    public function downloadFile(Request $request)
    {
        $path = $request->query('path');
        if (!$path) {
            return abort(400, 'Path is required.');
        }

        // Normalize path to always start with a slash for validation
        $normalizedPath = $path;
        if (!str_starts_with($normalizedPath, '/')) {
            $normalizedPath = '/' . $normalizedPath;
        }

        // Standard path traversal prevention
        if (str_contains($normalizedPath, '..') || !str_starts_with($normalizedPath, '/uploads/')) {
            return abort(403, 'Unauthorized access.');
        }

        $fullPath = public_path($normalizedPath);
        if (!file_exists($fullPath)) {
            return abort(404, 'File not found.');
        }

        return response()->download($fullPath);
    }

    public function viewFile(Request $request)
    {
        $path = $request->query('path');
        if (!$path) {
            return abort(400, 'Path is required.');
        }

        // Normalize path to always start with a slash for validation
        $normalizedPath = $path;
        if (!str_starts_with($normalizedPath, '/')) {
            $normalizedPath = '/' . $normalizedPath;
        }

        // Standard path traversal prevention
        if (str_contains($normalizedPath, '..') || !str_starts_with($normalizedPath, '/uploads/')) {
            return abort(403, 'Unauthorized access.');
        }

        $fullPath = public_path($normalizedPath);
        if (!file_exists($fullPath)) {
            return abort(404, 'File not found.');
        }

        return response()->file($fullPath);
    }
}
