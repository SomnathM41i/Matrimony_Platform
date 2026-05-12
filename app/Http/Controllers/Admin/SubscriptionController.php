<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\SubscriptionPackage;
use App\Models\UserSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    // =========================================================================
    // PLANS — SubscriptionPackage CRUD
    // Route::resource('plans', SubscriptionController::class)
    // =========================================================================

    /**
     * GET /admin/plans
     * List all subscription packages (plans).
     */
    public function index(Request $request): View
    {
        $plans = SubscriptionPackage::query()
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('is_active', $request->status === 'active');
            })
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();

        // Subscriber counts per plan
        $subscriberCounts = UserSubscription::query()
            ->where('is_active', true)
            ->selectRaw('subscription_package_id, COUNT(*) as cnt')
            ->groupBy('subscription_package_id')
            ->pluck('cnt', 'subscription_package_id');

        // Revenue per plan (sum of successful transactions)
        $revenueTotals = PaymentTransaction::query()
            ->where('status', 'success')
            ->selectRaw('user_subscription_id, SUM(amount) as total')
            ->groupBy('user_subscription_id')
            ->pluck('total', 'user_subscription_id');

        $stats = [
            'total_plans'       => $plans->count(),
            'active_plans'      => $plans->where('is_active', true)->count(),
            'total_subscribers' => $subscriberCounts->sum(),
            'total_revenue'     => PaymentTransaction::where('status', 'success')->sum('amount'),
        ];

        return view('admin.plans.index', compact('plans', 'subscriberCounts', 'revenueTotals', 'stats'));
    }

    /**
     * GET /admin/plans/create
     */
    public function create(): View
    {
        $plan = new SubscriptionPackage();
        return view('admin.plans.create', compact('plan'));
    }

    /**
     * POST /admin/plans
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($request->name);

        SubscriptionPackage::create($data);

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan \"{$request->name}\" created successfully.");
    }

    /**
     * GET /admin/plans/{plan}/edit
     */
    public function edit(SubscriptionPackage $plan): View
    {
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * PUT /admin/plans/{plan}
     */
    public function update(Request $request, SubscriptionPackage $plan): RedirectResponse
    {
        $data = $this->validatedData($request, $plan->id);

        // Regenerate slug only if name changed
        if ($plan->name !== $request->name) {
            $data['slug'] = $this->uniqueSlug($request->name, $plan->id);
        }

        $plan->update($data);

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan \"{$plan->name}\" updated successfully.");
    }

    /**
     * DELETE /admin/plans/{plan}
     */
    public function destroy(SubscriptionPackage $plan): RedirectResponse
    {
        // Safety: don't delete plans with active subscribers
        $activeCount = UserSubscription::where('subscription_package_id', $plan->id)
            ->where('is_active', true)
            ->count();

        if ($activeCount > 0) {
            return back()->with('error', "Cannot delete plan \"{$plan->name}\" — it has {$activeCount} active subscriber(s). Deactivate it instead.");
        }

        $name = $plan->name;
        $plan->delete();

        return redirect()->route('admin.plans.index')
                         ->with('success', "Plan \"{$name}\" deleted.");
    }

    // =========================================================================
    // EXISTING — UserSubscription & PaymentTransaction management
    // =========================================================================

    public function show(UserSubscription $subscription): View
    {
        $subscription->load('user', 'plan');
        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function cancel(UserSubscription $subscription): RedirectResponse
    {
        $subscription->update(['is_active' => false]);
        return back()->with('success', 'Subscription cancelled.');
    }

    public function transactions(Request $request): View
    {
        $transactions = PaymentTransaction::with('user')
            ->latest()
            ->paginate(20);
        return view('admin.subscriptions.transactions', compact('transactions'));
    }

    public function transactionDetail(PaymentTransaction $transaction): View
    {
        return view('admin.subscriptions.transaction-detail', compact('transaction'));
    }

    public function refund(Request $request, PaymentTransaction $transaction): RedirectResponse
    {
        // Hook your payment gateway here
        return back()->with('success', 'Refund initiated.');
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    private function validatedData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'                  => 'required|string|max:100',
            'description'           => 'nullable|string|max:500',
            'price'                 => 'required|numeric|min:0',
            'currency'              => 'required|string|size:3',
            'duration_days'         => 'required|integer|min:1',
            'trial_days'            => 'nullable|integer|min:0',
            'sort_order'            => 'nullable|integer|min:0',
            'is_active'             => 'boolean',
            'is_featured'           => 'boolean',
            // Limits
            'contact_views'         => 'nullable|integer|min:0',
            'interests_limit'       => 'nullable|integer|min:0',
            'messages_limit'        => 'nullable|integer|min:0',
            'photo_gallery_limit'   => 'nullable|integer|min:0',
            // Feature toggles
            'can_see_contact'       => 'boolean',
            'can_see_full_horoscope'=> 'boolean',
            'highlight_profile'     => 'boolean',
            'priority_in_search'    => 'boolean',
            'whatsapp_support'      => 'boolean',
            'rm_assistance'         => 'boolean',
            // Extra features (JSON array)
            'extra_features'        => 'nullable|array',
            'extra_features.*'      => 'string|max:100',
        ]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;

        while (
            SubscriptionPackage::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}