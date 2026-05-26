<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcFollowup;
use Illuminate\Http\Request;

class UcFollowupController extends Controller
{
    public function index()
    {
        return response()->json(UcFollowup::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'agent' => 'required|string',
            'contact' => 'required|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $count = UcFollowup::count() + 501;
        $validated['custom_id'] = "#FLP-{$count}";
        $validated['status'] = 'Pending';

        $followup = UcFollowup::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Agent followup logged successfully!',
            'data' => $followup
        ], 201);
    }
}
