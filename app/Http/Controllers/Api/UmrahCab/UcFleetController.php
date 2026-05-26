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
}
