<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcPriceList;
use Illuminate\Http\Request;

class UcPriceListController extends Controller
{
    public function index()
    {
        return response()->json(UcPriceList::orderBy('id', 'asc')->get());
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sedan_price' => 'nullable|numeric',
            'sedan_dates' => 'nullable|string',
            'suv_price' => 'nullable|numeric',
            'suv_dates' => 'nullable|string',
            'van_price' => 'nullable|numeric',
            'van_dates' => 'nullable|string',
            'coach_price' => 'nullable|numeric',
            'coach_dates' => 'nullable|string',
        ]);

        $priceList = UcPriceList::findOrFail($id);
        $priceList->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rates matrix updated successfully!',
            'data' => $priceList
        ]);
    }

    public function applyBulkDates(Request $request)
    {
        $validated = $request->validate([
            'dates' => 'required|string',
            'carType' => 'required|string', // sedan, suv, van, coach
            'price' => 'required|numeric'
        ]);

        $type = strtolower($validated['carType']);
        $priceField = "{$type}_price";
        $dateField = "{$type}_dates";

        UcPriceList::query()->update([
            $priceField => $validated['price'],
            $dateField => $validated['dates']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bulk rates applied to all routes successfully!'
        ]);
    }
}
