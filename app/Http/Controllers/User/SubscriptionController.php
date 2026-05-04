<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function show(): View
    {
        $user = Auth::user()->load(['activeSubscription.package', 'primaryPhoto']);

        $activeSubscription = $user->activeSubscription;

        // Subscription history (last 10)
        $history = UserSubscription::where('user_id', $user->id)
            ->with('package')
            ->latest()
            ->take(10)
            ->get();

        return view('user.subscription.show', compact('user', 'activeSubscription', 'history'));
    }
}