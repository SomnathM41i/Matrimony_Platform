<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $reports = Report::with('reporter','reportedUser')
                         ->when($request->status, fn($q) => $q->where('status', $request->status))
                         ->latest()->paginate(20);
        return view('admin.reports.index', compact('reports'));
    }
    public function show(Report $report): View { $report->load('reporter','reportedUser'); return view('admin.reports.show', compact('report')); }
    public function resolve(Request $request, Report $report): RedirectResponse
    {
        $report->update(['status' => 'resolved', 'admin_notes' => $request->notes, 'resolved_at' => now()]);
        return back()->with('success', 'Report resolved.');
    }
    public function dismiss(Report $report): RedirectResponse { $report->update(['status' => 'dismissed']); return back()->with('success', 'Report dismissed.'); }
}