<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcTrain;
use App\Models\UmrahCab\UcAudit;
use Illuminate\Http\Request;

class UcTrainController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $leg = $request->query('leg');
        $status = $request->query('status');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $perPage = $request->query('per_page', 10);

        $query = UcTrain::with(['customer'])->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('train_no', 'like', "%{$search}%")
                  ->orWhere('custom_id', 'like', "%{$search}%")
                  ->orWhere('route', 'like', "%{$search}%");
            });
        }

        if ($leg && $leg !== 'All') {
            $query->where('leg', $leg);
        }

        if ($status && $status !== 'All') {
            $query->where('status', $status);
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        if ($request->query('all') === 'true' || !$request->has('page')) {
            return response()->json($query->get());
        }

        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:uc_customers,id',
            'train_no' => 'required|string',
            'leg' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'route' => 'required|string',
        ]);

        $count = UcTrain::count() + 201;
        $validated['custom_id'] = "#TRN-{$count}";
        $validated['status'] = 'Scheduled';

        $train = UcTrain::create($validated);

        UcAudit::create([
            'custom_id' => $train->custom_id,
            'user_session' => auth()->user() ? auth()->user()->username : 'umrahcab',
            'ip_location' => $request->ip(),
            'performed_action' => "Logged new train record: {$train->custom_id} (No: {$train->train_no}, Route: {$train->route})"
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Train logged successfully!',
            'data' => $train
        ], 201);
    }

    public function show($id)
    {
        $train = UcTrain::with(['customer'])->find($id);

        if (!$train) {
            return response()->json([
                'success' => false,
                'message' => 'Train record not found'
            ], 404);
        }

        $audits = UcAudit::where('custom_id', $train->custom_id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $train->id,
                'customer_id' => $train->customer_id,
                'custom_id' => $train->custom_id,
                'train_no' => $train->train_no,
                'leg' => $train->leg,
                'date' => $train->date,
                'time' => $train->time,
                'route' => $train->route,
                'status' => $train->status,
                'created_at' => $train->created_at,
                'updated_at' => $train->updated_at,
                'customer' => $train->customer,
                'audits' => $audits
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $train = UcTrain::find($id);

        if (!$train) {
            return response()->json([
                'success' => false,
                'message' => 'Train record not found'
            ], 404);
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:uc_customers,id',
            'train_no' => 'required|string',
            'leg' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'route' => 'required|string',
            'status' => 'required|string',
        ]);

        $changes = [];
        if ($train->customer_id != $validated['customer_id']) $changes[] = "customer_id";
        if ($train->train_no != $validated['train_no']) $changes[] = "train_no ({$train->train_no} -> {$validated['train_no']})";
        if ($train->leg != $validated['leg']) $changes[] = "leg ({$train->leg} -> {$validated['leg']})";
        if ($train->date != $validated['date']) $changes[] = "date ({$train->date} -> {$validated['date']})";
        if ($train->time != $validated['time']) $changes[] = "time ({$train->time} -> {$validated['time']})";
        if ($train->route != $validated['route']) $changes[] = "route ({$train->route} -> {$validated['route']})";
        if ($train->status != $validated['status']) $changes[] = "status ({$train->status} -> {$validated['status']})";

        $remark = "Updated train record fields: " . implode(", ", $changes);
        if (empty($changes)) {
            $remark = "Updated train record details (no changes)";
        }

        $train->update($validated);

        UcAudit::create([
            'custom_id' => $train->custom_id,
            'user_session' => auth()->user() ? auth()->user()->username : 'umrahcab',
            'ip_location' => $request->ip(),
            'performed_action' => $remark
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Train record updated successfully!',
            'data' => $train
        ]);
    }

    public function destroy($id)
    {
        $train = UcTrain::find($id);

        if (!$train) {
            return response()->json([
                'success' => false,
                'message' => 'Train record not found'
            ], 404);
        }

        UcAudit::create([
            'custom_id' => $train->custom_id,
            'user_session' => auth()->user() ? auth()->user()->username : 'umrahcab',
            'ip_location' => request()->ip(),
            'performed_action' => "Deleted train record: {$train->custom_id} (No: {$train->train_no}, Route: {$train->route})"
        ]);

        $train->delete();

        return response()->json([
            'success' => true,
            'message' => 'Train record deleted successfully!'
        ]);
    }
}
