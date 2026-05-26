<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcNotice;
use Illuminate\Http\Request;

class UcNoticeController extends Controller
{
    public function index(Request $request)
    {
        $target = $request->query('target');
        $query = UcNotice::query()->orderBy('id', 'desc');

        if ($target) {
            $query->where('target', $target);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'priority' => 'required|string',
            'target' => 'required|string',
            'content' => 'required|string',
        ]);

        $validated['custom_id'] = 'NTC-' . (UcNotice::count() + 1);
        $validated['date'] = date('Y-m-d');

        $notice = UcNotice::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Announcement published successfully!',
            'data' => $notice
        ], 201);
    }
}
