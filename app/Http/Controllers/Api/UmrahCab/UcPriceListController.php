<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcPriceList;
use App\Models\UmrahCab\UcLocation;
use Illuminate\Http\Request;

class UcPriceListController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $search = $request->query('search', '');
        if ($request->has('group_name') && !empty($request->query('group_name'))) {
            $groupName = $request->query('group_name');
        } elseif (auth()->guard('company')->check()) {
            $company = auth()->guard('company')->user();
            $groupName = $company->price_group ?? 'Standard';
        } else {
            $groupName = 'Standard';
        }

        // Always query the Standard routes as the base structure
        $query = UcPriceList::where('group_name', 'Standard')->orderBy('id', 'asc');

        if (!empty($search)) {
            $query->where('route', 'like', "%{$search}%");
        }

        if ($request->query('paginate') === 'false') {
            $standardRoutes = $query->get();
            $overlaid = $this->overlayCustomPrices($standardRoutes, $groupName);
            $filtered = $overlaid->filter(function ($item) {
                return !isset($item->custom_prices['is_hidden']) || !$item->custom_prices['is_hidden'];
            })->values();
            return response()->json($filtered);
        }

        $paginator = $query->paginate($perPage);
        $transformedCollection = $paginator->getCollection()->map(function ($stdRoute) use ($groupName) {
            return $this->getOverlaidRoute($stdRoute, $groupName);
        })->filter(function ($item) {
            return !isset($item->custom_prices['is_hidden']) || !$item->custom_prices['is_hidden'];
        })->values();

        $paginator->setCollection($transformedCollection);

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
            'custom_prices' => 'nullable|array',
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

        $priceLists = UcPriceList::where('group_name', $groupName)->get();
        foreach ($priceLists as $pl) {
            $custom = $pl->custom_prices;
            if (is_array($custom)) {
                foreach ($custom as $vehicleName => &$details) {
                    if (is_array($details)) {
                        $details['from'] = $validated['start_date'];
                        $details['to'] = $validated['end_date'];
                    }
                }
                $pl->custom_prices = $custom;
            }
            $pl->sedan_dates = $dates;
            $pl->suv_dates = $dates;
            $pl->van_dates = $dates;
            $pl->coach_dates = $dates;
            $pl->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Bulk dates applied to all routes successfully!'
        ]);
    }

    public function store(Request $request)
    {
        $groupName = $request->input('group_name', 'Standard');
        $validated = $request->validate([
            'route' => 'required|string',
            'group_name' => 'nullable|string',
            'pickup_id' => 'nullable|integer|exists:uc_locations,id',
            'destination_id' => 'nullable|integer|exists:uc_locations,id',
            'sedan_price' => 'nullable|numeric',
            'sedan_dates' => 'nullable|string',
            'suv_price' => 'nullable|numeric',
            'suv_dates' => 'nullable|string',
            'van_price' => 'nullable|numeric',
            'van_dates' => 'nullable|string',
            'coach_price' => 'nullable|numeric',
            'coach_dates' => 'nullable|string',
            'custom_prices' => 'nullable|array',
        ]);

        // Auto-resolve pickup_id / destination_id from route if not provided
        if (empty($validated['pickup_id']) || empty($validated['destination_id'])) {
            $parts = preg_split('/\s+to\s+/i', $validated['route']);
            if (count($parts) === 2) {
                $pickupName = trim($parts[0]);
                $destName = trim($parts[1]);

                $cleanName = function($name) {
                    $n = strtolower($name);
                    $n = str_replace('jaddah', 'jeddah', $n);
                    $n = str_replace('madina', 'madinah', $n);
                    if (strpos($n, 'madinahh') !== false) {
                        $n = str_replace('madinahh', 'madinah', $n);
                    }
                    return trim($n);
                };

                $pClean = $cleanName($pickupName);
                $dClean = $cleanName($destName);

                if (empty($validated['pickup_id'])) {
                    $pLoc = \App\Models\UmrahCab\UcLocation::where('name', $pickupName)
                        ->orWhere('name', 'like', "%{$pickupName}%")
                        ->orWhere('name', 'like', "%{$pClean}%")
                        ->first();
                    if ($pLoc) $validated['pickup_id'] = $pLoc->id;
                }
                if (empty($validated['destination_id'])) {
                    $dLoc = \App\Models\UmrahCab\UcLocation::where('name', $destName)
                        ->orWhere('name', 'like', "%{$destName}%")
                        ->orWhere('name', 'like', "%{$dClean}%")
                        ->first();
                    if ($dLoc) $validated['destination_id'] = $dLoc->id;
                }
            }
        }

        // Ensure base Standard route entry exists so index() returns it in matrix query
        $stdRoute = UcPriceList::where('group_name', 'Standard')
            ->where('route', $validated['route'])
            ->first();

        if (!$stdRoute) {
            $stdData = array_merge($validated, ['group_name' => 'Standard']);
            $stdRoute = UcPriceList::create($stdData);
        }

        if ($groupName !== 'Standard') {
            $customData = array_merge($validated, ['group_name' => $groupName]);
            $priceList = UcPriceList::where('group_name', $groupName)
                ->where('route', $validated['route'])
                ->first();

            if ($priceList) {
                $customPrices = $priceList->custom_prices ?? [];
                if (is_array($customPrices) && isset($customPrices['is_hidden'])) {
                    unset($customPrices['is_hidden']);
                }
                $customData['custom_prices'] = array_merge($customPrices, $validated['custom_prices'] ?? []);
                $priceList->update($customData);
            } else {
                $priceList = UcPriceList::create($customData);
            }
        } else {
            $priceList = $stdRoute;
        }

        return response()->json([
            'success' => true,
            'message' => 'New route package added successfully!',
            'data' => $priceList
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $priceList = UcPriceList::findOrFail($id);
        $groupName = $request->query('group_name', $priceList->group_name);

        if ($groupName && $groupName !== 'Standard') {
            $custom = UcPriceList::where('group_name', $groupName)
                ->where('route', $priceList->route)
                ->first();

            if ($custom) {
                $custom_prices = $custom->custom_prices ?? [];
                if (!is_array($custom_prices)) $custom_prices = [];
                $custom_prices['is_hidden'] = true;
                $custom->custom_prices = $custom_prices;
                $custom->save();
            } else {
                UcPriceList::create([
                    'route' => $priceList->route,
                    'group_name' => $groupName,
                    'pickup_id' => $priceList->pickup_id,
                    'destination_id' => $priceList->destination_id,
                    'sedan_price' => $priceList->sedan_price,
                    'suv_price' => $priceList->suv_price,
                    'van_price' => $priceList->van_price,
                    'coach_price' => $priceList->coach_price,
                    'custom_prices' => ['is_hidden' => true]
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Route package removed from tier '{$groupName}' successfully!"
            ]);
        }

        // Standard deletion removes the route globally across all groups
        UcPriceList::where('route', $priceList->route)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Route package deleted successfully!'
        ]);
    }

    public function hiddenRoutes(Request $request)
    {
        $groupName = $request->query('group_name', 'Standard');
        if ($groupName === 'Standard') {
            return response()->json([]);
        }

        $hidden = UcPriceList::where('group_name', $groupName)
            ->get()
            ->filter(function ($item) {
                return isset($item->custom_prices['is_hidden']) && $item->custom_prices['is_hidden'];
            })
            ->values();

        return response()->json($hidden);
    }

    public function restore(Request $request)
    {
        $validated = $request->validate([
            'route' => 'required|string',
            'group_name' => 'required|string'
        ]);

        $custom = UcPriceList::where('group_name', $validated['group_name'])
            ->where('route', $validated['route'])
            ->first();

        if ($custom) {
            $custom_prices = $custom->custom_prices ?? [];
            if (is_array($custom_prices) && isset($custom_prices['is_hidden'])) {
                unset($custom_prices['is_hidden']);
            }
            if (empty($custom_prices) || count($custom_prices) === 0) {
                $custom->delete();
            } else {
                $custom->custom_prices = $custom_prices;
                $custom->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Route '{$validated['route']}' restored to tier '{$validated['group_name']}' successfully!"
        ]);
    }

    public function locations(Request $request)
    {
        if ($request->query('detailed') === 'true') {
            $locations = \App\Models\UmrahCab\UcLocation::orderBy('name', 'asc')->get(['id', 'name', 'type']);
            return response()->json($locations);
        }
        $locations = \App\Models\UmrahCab\UcLocation::orderBy('name', 'asc')->pluck('name');
        return response()->json($locations);
    }

    public function publicRates()
    {
        $rates = UcPriceList::where('group_name', 'Standard')->get();
        return response()->json($rates);
    }
}
