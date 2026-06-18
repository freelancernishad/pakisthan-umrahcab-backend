<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcPriceList;
use Illuminate\Http\Request;

class UcPriceListController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $search = $request->query('search', '');

        $query = UcPriceList::orderBy('id', 'asc');

        if (!empty($search)) {
            $query->where('route', 'like', "%{$search}%");
        }

        if ($request->query('paginate') === 'false') {
            return response()->json($query->get());
        }

        return response()->json($query->paginate($perPage));
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route' => 'required|string|unique:uc_price_lists,route',
            'sedan_price' => 'nullable|numeric',
            'sedan_dates' => 'nullable|string',
            'suv_price' => 'nullable|numeric',
            'suv_dates' => 'nullable|string',
            'van_price' => 'nullable|numeric',
            'van_dates' => 'nullable|string',
            'coach_price' => 'nullable|numeric',
            'coach_dates' => 'nullable|string',
        ]);

        $priceList = UcPriceList::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'New route package added successfully!',
            'data' => $priceList
        ]);
    }

    public function destroy($id)
    {
        $priceList = UcPriceList::findOrFail($id);
        $priceList->delete();

        return response()->json([
            'success' => true,
            'message' => 'Route package deleted successfully!'
        ]);
    }
}
