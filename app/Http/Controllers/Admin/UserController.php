<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\SubscriptionPackage;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Carbon\Carbon;

class UserController extends Controller
{
    // =========================================================================
    // LIST USERS
    // =========================================================================
    public function index(Request $request): View
    {
        $users = User::with('role', 'profile')
            ->when($request->search, fn($q) =>
                $q->where(fn($q) =>
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%")
                      ->orWhere('phone', 'like', "%{$request->search}%")
                )
            )
            ->when($request->role, fn($q) =>
                $q->whereHas('role', fn($q) => $q->where('name', $request->role))
            )
            ->when($request->status, fn($q) =>
                $q->where('account_status', $request->status)
            )
            ->when($request->premium, fn($q) =>
                $q->where('is_premium', true)
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => Role::all(),
        ]);
    }

    // =========================================================================
    // CREATE
    // =========================================================================
    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => Role::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role_id'        => ['required', 'exists:roles,id'],
            'name'           => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'gender'         => ['nullable', 'in:male,female,other'],
            'account_status' => ['required', 'in:active,suspended,banned,pending'],
            'password'       => ['required', 'confirmed', 'min:8'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        $user->markEmailAsVerified();

        $this->logActivity('user_created', $user, null, $user->toArray());

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User created successfully.');
    }

    // =========================================================================
    // SHOW / EDIT / UPDATE
    // =========================================================================
    public function show(User $user): View
    {
        $user->load('role', 'profile', 'activeSubscription', 'assignedRm');

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user'  => $user,
            'roles' => Role::all(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role_id'        => ['required', 'exists:roles,id'],
            'name'           => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone'          => ['nullable', 'string', 'max:20'],
            'gender'         => ['nullable', 'in:male,female,other'],
            'account_status' => ['required', 'in:active,suspended,banned,pending'],
            'password'       => ['nullable', 'confirmed', 'min:8'],
        ]);

        $old = $user->getOriginal();

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        $this->logActivity('user_updated', $user, $old, $user->fresh()->toArray());

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    // =========================================================================
    // DELETE / RESTORE
    // =========================================================================
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        $this->logActivity('user_deleted', $user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted (soft).');
    }

    public function forceDelete(User $user): RedirectResponse
    {
        $user->forceDelete();

        $this->logActivity('user_force_deleted', $user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User permanently deleted.');
    }

    public function restore(int $id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        $this->logActivity('user_restored', $user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User restored.');
    }

    public function trashed(): View
    {
        $users = User::onlyTrashed()
            ->with('role')
            ->latest('deleted_at')
            ->paginate(20);

        return view('admin.users.trashed', compact('users'));
    }

    // =========================================================================
    // STATUS / PREMIUM / EMAIL
    // =========================================================================
    public function toggleStatus(User $user): RedirectResponse
    {
        $user->update([
            'account_status' => $user->account_status === 'active' ? 'suspended' : 'active',
        ]);

        $this->logActivity('status_toggled', $user);

        return back()->with('success', 'User status updated.');
    }

    public function togglePremium(User $user): RedirectResponse
    {
        $user->update([
            'is_premium'         => !$user->is_premium,
            'premium_expires_at' => !$user->is_premium ? now()->addYear() : null,
        ]);

        $this->logActivity('premium_toggled', $user);

        return back()->with('success', 'Premium status updated.');
    }

    public function verifyEmail(User $user): RedirectResponse
    {
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            $this->logActivity('email_verified', $user);
        }

        return back()->with('success', 'Email verified.');
    }

    // =========================================================================
    // ACTIVITY / PROFILE
    // =========================================================================
    public function activity(User $user): View
    {
        $logs = $user->activityLogs()->latest('created_at')->paginate(30);

        return view('admin.users.activity', compact('user', 'logs'));
    }

    public function profile(User $user): View
    {
        $user->load(
            'profile.religion',
            'profile.caste',
            'profile.city',
            'profile.educationLevel',
            'profile.profession'
        );

        return view('admin.users.profile', compact('user'));
    }

    public function export(Request $request)
    {
        abort(501, 'Export not yet implemented.');
    }

    // =========================================================================
    // ASSIGN PLAN
    // =========================================================================
    public function showAssignPlan(User $user): View
    {
        $plans = SubscriptionPackage::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();

        return view('admin.users.assign-plan', [
            'user' => $user,
            'plans' => $plans,
            'activeSubscription' => $user->activeSubscription,
        ]);
    }

    public function assignPlan(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'plan_id'    => ['required', 'exists:subscription_packages,id'],
            'start_date' => ['required', 'date'],
            'note'       => ['nullable', 'string', 'max:500'],
        ]);

        $plan = SubscriptionPackage::findOrFail($request->plan_id);

        UserSubscription::where('user_id', $user->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $startDate = Carbon::parse($request->start_date);
        $endDate   = $startDate->copy()->addDays($plan->duration_days);
///dd($plan->price);
        UserSubscription::create([
            'user_id'                 => $user->id,
            'subscription_package_id' => $plan->id,
            'amount_paid'             => $plan->price ?? 0, // ✅ FIX
            'currency'                => 'INR',             
            'is_active'               => true,
            'starts_at'               => $startDate,
            'expires_at'              => $endDate,
            'assigned_by_admin'       => true,
            'admin_note'              => $request->note,
            'payment_status'          => 'completed',       
        ]);

        $user->update([
            'is_premium'         => true,
            'premium_expires_at' => $endDate,
        ]);

        $this->logActivity('plan_assigned', $user, null, [
            'plan' => $plan->name,
            'expires_at' => $endDate,
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Plan \"{$plan->name}\" assigned successfully.");
    }

    // =========================================================================
    // IMPERSONATION
    // =========================================================================
    public function loginAs(User $user): RedirectResponse
    {
        if (in_array($user->role?->name, ['super_admin', 'admin'], true)) {
            return back()->with('error', 'Cannot impersonate admin.');
        }

        if ($user->account_status !== 'active') {
            return back()->with('error', 'User not active.');
        }

        $admin = Auth::user();

        session([
            'impersonating' => true,
            'impersonator_id' => $admin->id,
            'impersonator_name' => $admin->name,
        ]);

        $this->logActivity('impersonation_started', $user, null, [
            'admin' => $admin->name,
        ]);

        Auth::login($user);

        return redirect(route('home'))
            ->with('impersonation_notice', "Browsing as {$user->name}.");
    }

    public function stopImpersonation(): RedirectResponse
    {
        if (!session('impersonating')) {
            return redirect()->route('admin.dashboard');
        }

        $adminId = session('impersonator_id');

        session()->forget(['impersonating', 'impersonator_id', 'impersonator_name']);

        $admin = User::findOrFail($adminId);
        Auth::login($admin);

        $this->logActivity('impersonation_stopped', $admin);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Impersonation ended.');
    }

    // =========================================================================
    //  CENTRAL LOGGER
    // =========================================================================
    private function logActivity($action, $model, $old = null, $new = null): void
    {
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'model_type'  => get_class($model),
            'model_id'    => $model->id,
            'old_values'  => $old,
            'new_values'  => $new,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'created_at'  => now(),
        ]);
    }
}