<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Notice::query();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('union_id')) {
            $query->where('union_id', $request->union_id);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $notices = $query->latest()->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $notices
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:global,admin,union',
            'union_id' => 'nullable|integer|required_if:type,union',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $notice = Notice::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Notice created successfully',
            'data' => $notice
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notice = Notice::find($id);

        if (!$notice) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notice not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $notice
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $notice = Notice::find($id);

        if (!$notice) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notice not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:global,admin,union',
            'union_id' => 'nullable|integer|required_if:type,union',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $notice->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Notice updated successfully',
            'data' => $notice
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $notice = Notice::find($id);

        if (!$notice) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notice not found'
            ], 404);
        }

        $notice->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Notice deleted successfully'
        ]);
    }
}
