<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View { return view('admin.notifications.index', ['notifications' => UserNotification::latest()->paginate(20)]); }
    public function create(): View { return view('admin.notifications.create'); }
    public function sendBulk(Request $request): RedirectResponse { /* dispatch bulk notification job */ return redirect()->route('admin.notifications.index')->with('success', 'Bulk notification queued.'); }
    public function sendToUser(Request $request, \App\Models\User $user): RedirectResponse { /* send individual notification */ return back()->with('success', 'Notification sent.'); }
    public function templates(): View { return view('admin.notifications.templates'); }
    public function pushLogs(): View { return view('admin.notifications.push-logs'); }
}