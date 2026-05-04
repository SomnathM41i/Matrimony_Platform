<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPackage;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PackagesController extends Controller
{
    public function index(): View
    {
        $packages = SubscriptionPackage::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();

        $user = Auth::user()->load(['activeSubscription.package']);

        $activeSubscription = $user->activeSubscription;

        return view('user.packages.index', compact('packages', 'activeSubscription'));
    }
}