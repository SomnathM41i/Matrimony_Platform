<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAnalyticsSnapshot;
use App\Models\ContactForm;
use App\Models\Report;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = $this->getStats();
        $recentUsers        = User::with('role', 'profile')
                                  ->whereHas('role', fn($q) => $q->where('name', 'user'))
                                  ->latest()
                                  ->take(10)
                                  ->get();
        $pendingReports     = Report::where('status', 'pending')->count();
        $pendingContacts    = ContactForm::where('status', 'pending')->count();
        $latestSnapshot     = AdminAnalyticsSnapshot::latest('snapshot_date')->first();

        return view('admin.dashboard', compact(
            'stats', 'recentUsers', 'pendingReports', 'pendingContacts', 'latestSnapshot'
        ));
    }

    public function stats(): JsonResponse
    {
        return response()->json($this->getStats());
    }

    private function getStats(): array
    {
        $totalUsers = User::whereHas('role', fn($q) => $q->where('name', 'user'))->count();

        $activeUsers = User::where('account_status', 'active')->count();

        return [
            'total_users'          => $totalUsers,
            'new_users_today'      => User::whereDate('created_at', today())->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'active_users'         => $activeUsers,
            'premium_users'        => User::where('is_premium', true)->count(),
            'pending_profiles'     => User::where('profile_status', 'pending')->count(),
            'pending_reports'      => Report::where('status', 'pending')->count(),
            'open_contacts'        => ContactForm::where('status', 'pending')->count(),
            'revenue_month'        => \App\Models\PaymentTransaction::where('status', 'success')
                                        ->whereMonth('created_at', now()->month)
                                        ->sum('amount') ?? 0,
        ];
    }
}
