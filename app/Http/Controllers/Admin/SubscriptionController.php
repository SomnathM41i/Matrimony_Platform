<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use App\Models\PaymentTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $subscriptions = UserSubscription::with('user','plan')
                                         ->when($request->active, fn($q) => $q->where('is_active', true))
                                         ->latest()->paginate(20);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }
    public function show(UserSubscription $subscription): View { $subscription->load('user','plan'); return view('admin.subscriptions.show', compact('subscription')); }
    public function cancel(UserSubscription $subscription): RedirectResponse { $subscription->update(['is_active' => false]); return back()->with('success', 'Subscription cancelled.'); }
    public function transactions(Request $request): View { $transactions = PaymentTransaction::with('user')->latest()->paginate(20); return view('admin.subscriptions.transactions', compact('transactions')); }
    public function transactionDetail(PaymentTransaction $transaction): View { return view('admin.subscriptions.transaction-detail', compact('transaction')); }
    public function refund(Request $request, PaymentTransaction $transaction): RedirectResponse { /* process refund via payment gateway */ return back()->with('success', 'Refund initiated.'); }
}