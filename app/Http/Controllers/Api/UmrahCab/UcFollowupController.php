<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcFollowup;
use Illuminate\Http\Request;

class UcFollowupController extends Controller
{
    public function index(Request $request)
    {
        $perPage   = (int) $request->get('per_page', 10);
        $page      = (int) $request->get('page', 1);
        $search    = $request->get('search', '');
        $company   = $request->get('company', '');
        $status    = $request->get('status', '');
        $startDate = $request->get('start_date', '');
        $endDate   = $request->get('end_date', '');

        $query = UcFollowup::with('customer')->orderBy('id', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title',   'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('notes',   'like', "%{$search}%");
            });
        }

        if ($company) {
            $query->where('agent', $company);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($paginated);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'agent' => 'required|string',
            'contact' => 'required|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'customer_id' => 'nullable|integer',
        ]);

        $count = UcFollowup::count() + 501;
        $validated['custom_id'] = "#FLP-{$count}";
        $validated['status'] = 'Pending';

        $followup = UcFollowup::create($validated);
        $followup->load('customer');

        return response()->json([
            'success' => true,
            'message' => 'Agent followup logged successfully!',
            'data' => $followup
        ], 201);
    }

    public function show($id)
    {
        $followup = UcFollowup::with('customer')->findOrFail($id);
        return response()->json($followup);
    }

    public function update(Request $request, $id)
    {
        $followup = UcFollowup::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string',
            'agent' => 'required|string',
            'contact' => 'required|string',
            'date' => 'required|date',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'customer_id' => 'nullable|integer',
        ]);

        $followup->update($validated);
        $followup->load('customer');

        return response()->json([
            'success' => true,
            'message' => 'Agent followup updated successfully!',
            'data' => $followup
        ]);
    }

    public function destroy($id)
    {
        $followup = UcFollowup::findOrFail($id);
        $followup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Agent followup deleted successfully!'
        ]);
    }
}
