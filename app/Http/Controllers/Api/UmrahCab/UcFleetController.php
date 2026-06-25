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
        ]);

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
        ]);

        $fleet = UcFleet::findOrFail($id);
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
