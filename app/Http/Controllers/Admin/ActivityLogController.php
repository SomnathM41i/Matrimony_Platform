<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::with('user')
                           ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
                           ->when($request->action,  fn($q) => $q->where('action', $request->action))
                           ->latest('created_at')->paginate(50);
        return view('admin.logs.index', compact('logs'));
    }
    public function show(ActivityLog $log): View { return view('admin.logs.show', compact('log')); }
    public function purge(Request $request): RedirectResponse
    {
        $before = $request->validate(['before' => 'required|date'])['before'];
        ActivityLog::where('created_at', '<', $before)->delete();
        return redirect()->route('admin.logs.index')->with('success', 'Logs purged.');
    }
    public function export(): RedirectResponse { abort(501, 'Export not yet implemented.'); }
}