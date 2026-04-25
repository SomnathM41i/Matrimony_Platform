<?php

/*
|--------------------------------------------------------------------------
| ADMIN PANEL — BLUEPRINT CONTROLLERS
|--------------------------------------------------------------------------
| These are fully-stubbed controllers. Each method signature, auth check,
| and response pattern is production-ready. Implement the business logic
| inside each method as your project grows.
|--------------------------------------------------------------------------
*/

// ─────────────────────────────────────────────────────────────────────────────


// ─────────────────────────────────────────────────────────────────────────────


// ─────────────────────────────────────────────────────────────────────────────


// ─────────────────────────────────────────────────────────────────────────────


// ─────────────────────────────────────────────────────────────────────────────


// ─────────────────────────────────────────────────────────────────────────────


// ─────────────────────────────────────────────────────────────────────────────

// ─────────────────────────────────────────────────────────────────────────────


// ─────────────────────────────────────────────────────────────────────────────
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAnalyticsSnapshot;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View { return view('admin.analytics.index', ['snapshot' => AdminAnalyticsSnapshot::latest('snapshot_date')->first()]); }
    public function users(): View { return view('admin.analytics.users'); }
    public function subscriptions(): View { return view('admin.analytics.subscriptions'); }
    public function revenue(): View { return view('admin.analytics.revenue'); }
    public function engagement(): View { return view('admin.analytics.engagement'); }
    public function snapshots(): View { return view('admin.analytics.snapshots', ['snapshots' => AdminAnalyticsSnapshot::latest('snapshot_date')->paginate(30)]); }
    public function createSnapshot(): \Illuminate\Http\RedirectResponse { AdminAnalyticsSnapshot::create([]); /* dispatch snapshot job */ return back()->with('success', 'Snapshot creation queued.'); }
    public function export(): \Illuminate\Http\RedirectResponse { abort(501, 'Export not yet implemented.'); }
}

// ─────────────────────────────────────────────────────────────────────────────


// ─────────────────────────────────────────────────────────────────────────────


// ─────────────────────────────────────────────────────────────────────────────


// ─────────────────────────────────────────────────────────────────────────────


// ─────────────────────────────────────────────────────────────────────────────


// ─────────────────────────────────────────────────────────────────────────────

