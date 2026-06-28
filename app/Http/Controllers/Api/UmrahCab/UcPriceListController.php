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
        $groupName = $request->query('group_name', 'Standard');

        if (auth()->guard('company')->check()) {
            $company = auth()->guard('company')->user();
            $groupName = $company->price_group ?? 'Standard';
        }

        // Always query the Standard routes as the base structure
        $query = UcPriceList::where('group_name', 'Standard')->orderBy('id', 'asc');

        if (!empty($search)) {
            $query->where('route', 'like', "%{$search}%");
        }

        if ($request->query('paginate') === 'false') {
            $standardRoutes = $query->get();
            return response()->json($this->overlayCustomPrices($standardRoutes, $groupName));
        }

        $paginator = $query->paginate($perPage);
        $paginator->getCollection()->transform(function ($stdRoute) use ($groupName) {
            return $this->getOverlaidRoute($stdRoute, $groupName);
        });

        return response()->json($paginator);
    }

    private function getOverlaidRoute($stdRoute, $groupName)
    {
        if ($groupName === 'Standard') {
            return $stdRoute;
        }

        $custom = UcPriceList::where('group_name', $groupName)
            ->where('route', $stdRoute->route)
            ->first();

        if ($custom) {
            return $custom;
        }

        return $stdRoute;
    }

    private function overlayCustomPrices($standardRoutes, $groupName)
    {
        if ($groupName === 'Standard') {
            return $standardRoutes;
        }

        $customRoutes = UcPriceList::where('group_name', $groupName)
            ->get()
            ->keyBy('route');

        return $standardRoutes->map(function ($stdRoute) use ($customRoutes) {
            if ($customRoutes->has($stdRoute->route)) {
                return $customRoutes->get($stdRoute->route);
            }
            return $stdRoute;
        });
    }

    public function groups()
    {
        $groups = UcPriceList::pluck('group_name')->unique()->values()->toArray();
        if (!in_array('Standard', $groups)) {
            array_unshift($groups, 'Standard');
        }
        return response()->json($groups);
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
            'group_name' => 'nullable|string',
        ]);

        $priceList = UcPriceList::findOrFail($id);
        $groupName = $validated['group_name'] ?? $priceList->group_name;

        // If target group name is different, handle custom override creation
        if ($groupName !== $priceList->group_name) {
            $override = UcPriceList::where('group_name', $groupName)
                ->where('route', $priceList->route)
                ->first();

            if (!$override) {
                $override = UcPriceList::create(array_merge($validated, [
                    'route' => $priceList->route,
                    'group_name' => $groupName
                ]));
            } else {
                $override->update($validated);
            }

            return response()->json([
                'success' => true,
                'message' => 'Rates matrix updated successfully!',
                'data' => $override
            ]);
        }

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
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'group_name' => 'nullable|string'
        ]);

        $dates = $validated['start_date'] . ' to ' . $validated['end_date'];
        $groupName = $validated['group_name'] ?? 'Standard';

        UcPriceList::where('group_name', $groupName)->update([
            'sedan_dates' => $dates,
            'suv_dates' => $dates,
            'van_dates' => $dates,
            'coach_dates' => $dates,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bulk dates applied to all routes successfully!'
        ]);
    }

    public function store(Request $request)
    {
        $groupName = $request->input('group_name', 'Standard');
        $validated = $request->validate([
            'route' => 'required|string|unique:uc_price_lists,route,NULL,id,group_name,' . $groupName,
            'group_name' => 'nullable|string',
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
        UcPriceList::where('route', $priceList->route)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Route package deleted successfully!'
        ]);
    }

    public function locations()
    {
        $routes = UcPriceList::pluck('route')->toArray();
        $locationsSet = [];
        
        $fallbackLocations = [
            "Jeddah Airport (JED) - Terminal 1",
            "Jeddah Airport (JED) - North Terminal",
            "Makkah Hotel",
            "Madinah Hotel",
            "Jeddah Hotel",
            "Makkah Haram",
            "Madinah Haram",
            "Makkah Station (Haramain)",
            "Madinah Station (Haramain)",
            "Jeddah Station (Haramain)",
            "Taif",
            "Yanbu"
        ];
        
        foreach ($fallbackLocations as $loc) {
            $locationsSet[strtolower($loc)] = $loc;
        }

        foreach ($routes as $route) {
            if (empty($route)) continue;
            
            $routeStr = explode('★', $route)[0];
            $routeStr = explode('(', $routeStr)[0];
            $routeStr = trim($routeStr);
            
            $parts = preg_split('/\s+to\s+|\s+TO\s+|\s*-\s*|\s*>\s*/i', $routeStr);
            foreach ($parts as $p) {
                $cleaned = trim($p);
                if (strlen($cleaned) > 2) {
                    $formatted = ucwords(strtolower($cleaned));
                    $locationsSet[strtolower($formatted)] = $formatted;
                }
            }
        }

        return response()->json(array_values($locationsSet));
    }
}
