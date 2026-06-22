<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcHotel;
use Illuminate\Http\Request;

class UcHotelController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->query('city');
        $search = $request->query('search');
        $active = $request->query('active');

        $query = UcHotel::orderBy('name', 'asc');

        if ($city) {
            $query->where('city', $city);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($active !== null) {
            $query->where('active', (int)$active);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'active' => 'sometimes|integer|in:0,1'
        ]);

        $hotel = UcHotel::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Hotel added successfully!',
            'data' => $hotel
        ], 201);
    }

    public function show($id)
    {
        $hotel = UcHotel::find($id);

        if (!$hotel) {
            return response()->json([
                'success' => false,
                'message' => 'Hotel not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $hotel
        ]);
    }

    public function update(Request $request, $id)
    {
        $hotel = UcHotel::find($id);

        if (!$hotel) {
            return response()->json([
                'success' => false,
                'message' => 'Hotel not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'active' => 'required|integer|in:0,1'
        ]);

        $hotel->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Hotel updated successfully!',
            'data' => $hotel
        ]);
    }

    public function destroy($id)
    {
        $hotel = UcHotel::find($id);

        if (!$hotel) {
            return response()->json([
                'success' => false,
                'message' => 'Hotel not found'
            ], 404);
        }

        $hotel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hotel deleted successfully!'
        ]);
    }
}
