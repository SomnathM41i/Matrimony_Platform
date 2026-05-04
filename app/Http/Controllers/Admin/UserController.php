<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\SubscriptionPackage;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

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
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => Role::all()
        ]);
    }

    // =========================================================================
    // CREATE USER
    // =========================================================================
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'role_id'        => ['required', 'exists:roles,id'],
            'name'           => ['required', 'max:100'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'phone'          => ['nullable'],
            'account_status' => ['required'],
            'password'       => ['required', 'confirmed', 'min:8'],
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $user->markEmailAsVerified();

        $this->log('user_created', $user);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User created successfully.');
    }

    // =========================================================================
    // SHOW USER
    // =========================================================================
    public function show(User $user): View
    {
        $user->load('role', 'profile', 'activeSubscription');
        return view('admin.users.show', compact('user'));
    }

    // =========================================================================
    // UPDATE USER
    // =========================================================================
    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role_id'        => ['required', 'exists:roles,id'],
            'name'           => ['required', 'max:100'],
            'email'          => ['required', 'email', 'unique:users,email,' . $user->id],
            'account_status' => ['required'],
            'password'       => ['nullable', 'confirmed', 'min:8'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $old = $user->toArray();
        $user->update($data);

        $this->log('user_updated', $user, $old, $user->toArray());

        return back()->with('success', 'User updated successfully.');
    }

    // =========================================================================
    // DELETE USER
    // =========================================================================
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        $this->log('user_deleted', $user);

        return back()->with('success', 'User deleted.');
    }

    // =========================================================================
    // TOGGLE STATUS
    // =========================================================================
    public function toggleStatus(User $user): RedirectResponse
    {
        $user->update([
            'account_status' => $user->account_status === 'active' ? 'suspended' : 'active',
        ]);

        $this->log('status_changed', $user);

        return back()->with('success', 'Status updated.');
    }

    // =========================================================================
    // ASSIGN PLAN
    // =========================================================================
    public function assignPlan(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'plan_id'    => ['required', 'exists:subscription_packages,id'],
            'start_date' => ['required', 'date'],
        ]);

        $plan = SubscriptionPackage::findOrFail($request->plan_id);

        UserSubscription::where('user_id', $user->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $start = now();
        $end   = $start->copy()->addDays($plan->duration_days);

        UserSubscription::create([
            'user_id'                 => $user->id,
            'subscription_package_id' => $plan->id,
            'is_active'               => true,
            'starts_at'               => $start,
            'expires_at'              => $end,
        ]);

        $user->update([
            'is_premium' => true,
            'premium_expires_at' => $end,
        ]);

        $this->log('plan_assigned', $user, null, [
            'plan' => $plan->name,
            'expires_at' => $end
        ]);

        return back()->with('success', 'Plan assigned.');
    }

    // =========================================================================
    // IMPERSONATION
    // =========================================================================
    public function loginAs(User $user): RedirectResponse
    {
        if ($user->account_status !== 'active') {
            return back()->with('error', 'User not active.');
        }

        $admin = Auth::user();

        session([
            'impersonating' => true,
            'impersonator_id' => $admin->id,
        ]);

        $this->log('impersonation_started', $user, null, [
            'admin' => $admin->name
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Now logged in as user.');
    }

    public function stopImpersonation(): RedirectResponse
    {
        $adminId = session('impersonator_id');

        session()->forget(['impersonating', 'impersonator_id']);

        $admin = User::findOrFail($adminId);
        Auth::login($admin);

        return redirect()->route('admin.users.index')
            ->with('success', 'Returned to admin.');
    }

    // =========================================================================
    // CENTRAL LOG FUNCTION
    // =========================================================================
    private function log($action, $model, $old = null, $new = null): void
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