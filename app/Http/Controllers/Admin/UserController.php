<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::with('role', 'profile')
            ->when($request->search, fn($q) => $q->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            }))
            ->when($request->role, fn($q) => $q->whereHas('role', fn($q) => $q->where('name', $request->role)))
            ->when($request->status, fn($q) => $q->where('account_status', $request->status))
            ->when($request->premium, fn($q) => $q->where('is_premium', true))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
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

        // Hash password
        $validated['password'] = \Hash::make($validated['password']);

        // Create user
        $user = \App\Models\User::create($validated);

        // Auto verify email (optional)
        $user->markEmailAsVerified();

        return redirect()
            ->route('admin.users.show', $user->id)
            ->with('success', 'User created successfully.');
    }

    public function show(User $user): View
    {
        $user->load('role', 'profile', 'activeSubscription', 'assignedRm');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
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

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
                         ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete(); // Soft delete
        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted (soft).');
    }

    public function forceDelete(User $user): RedirectResponse
    {
        $user->forceDelete();
        return redirect()->route('admin.users.index')
                         ->with('success', 'User permanently deleted.');
    }

    public function restore(int $id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('admin.users.index')
                         ->with('success', 'User restored.');
    }

    public function trashed(): View
    {
        $users = User::onlyTrashed()->with('role')->latest('deleted_at')->paginate(20);
        return view('admin.users.trashed', compact('users'));
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $user->update([
            'account_status' => $user->account_status === 'active' ? 'suspended' : 'active',
        ]);
        return back()->with('success', 'User status updated.');
    }

    public function togglePremium(User $user): RedirectResponse
    {
        $user->update([
            'is_premium'         => !$user->is_premium,
            'premium_expires_at' => !$user->is_premium ? now()->addYear() : null,
        ]);
        return back()->with('success', 'Premium status updated.');
    }

    public function verifyEmail(User $user): RedirectResponse
    {
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        return back()->with('success', 'Email verified.');
    }

    public function activity(User $user): View
    {
        $logs = $user->activityLogs()->latest('created_at')->paginate(30);
        return view('admin.users.activity', compact('user', 'logs'));
    }

    public function profile(User $user): View
    {
        $user->load('profile.religion', 'profile.caste', 'profile.city', 'profile.educationLevel', 'profile.profession');
        return view('admin.users.profile', compact('user'));
    }

    public function export(Request $request)
    {
        // Blueprint: return CSV export of users
        // Implement with maatwebsite/excel or spatie/simple-excel
        abort(501, 'Export not yet implemented. Add maatwebsite/excel.');
    }
}
