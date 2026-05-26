<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    public function index()
    {
        $logs = EmailLog::with('template')->latest()->paginate(15);
        return view('admin.email-logs.index', compact('logs'));
    }

    public function destroy(EmailLog $emailLog)
    {
        $emailLog->delete();
        return back()->with('success', 'Log entry deleted successfully.');
    }
}
