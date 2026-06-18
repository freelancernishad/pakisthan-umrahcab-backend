<?php

namespace App\Http\Controllers\Api\UmrahCab;

use App\Http\Controllers\Controller;
use App\Models\UmrahCab\UcAudit;
use Illuminate\Http\Request;

class UcAuditController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        return response()->json(UcAudit::orderBy('id', 'desc')->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'performed_action' => 'required|string',
        ]);

        $audit = UcAudit::create([
            'custom_id' => '#AUD-' . rand(4000, 4999),
            'user_session' => 'umrahcab',
            'ip_location' => $request->ip() ?? '127.0.0.1',
            'performed_action' => $validated['performed_action']
        ]);

        return response()->json($audit, 201);
    }
}
