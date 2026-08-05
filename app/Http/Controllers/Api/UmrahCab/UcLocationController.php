<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcLocation;
use Illuminate\Http\Request;

class UcLocationController extends Controller
{
    public function index()
    {
        $locations = UcLocation::orderBy('name', 'asc')->get();
        return response()->json($locations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:uc_locations,name',
            'type' => 'nullable|string'
        ]);

        if (empty($validated['type'])) {
            $validated['type'] = 'both';
        }

        $location = UcLocation::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Location added successfully!',
            'data' => $location
        ]);
    }

    public function destroy($id)
    {
        $location = UcLocation::findOrFail($id);
        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Location deleted successfully!'
        ]);
    }
}
