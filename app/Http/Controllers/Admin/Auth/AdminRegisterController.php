<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * AdminRegisterController
 *
 * Used for the FIRST-TIME super admin setup only.
 * Once a super_admin exists, this route should be disabled
 * by removing the route or adding a `->middleware('admin.setup')` check.
 *
 * Subsequent admin/RM accounts are created via the User Management panel.
 */
class AdminRegisterController extends Controller
{
    public function showRegister(): View|RedirectResponse
    {
        // Redirect if a super admin already exists — setup is already done
        if (User::whereHas('role', fn($q) => $q->where('name', 'super_admin'))->exists()) {
            return redirect()->route('admin.login')
                             ->with('info', 'Admin setup is already complete. Please log in.');
        }

        return view('admin.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        // Block registration if super admin already exists
        if (User::whereHas('role', fn($q) => $q->where('name', 'super_admin'))->exists()) {
            return redirect()->route('admin.login')
                             ->with('error', 'Registration is disabled. An admin already exists.');
        }

        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'password'              => ['required', 'confirmed', 'min:8'],
        ]);

        // Fetch or create the super_admin role
        $role = Role::firstOrCreate(
            ['name' => 'super_admin'],
            ['display_name' => 'Super Administrator', 'description' => 'Full access to all features']
        );

        $user = User::create([
            'role_id'        => $role->id,
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'] ?? null,
            'password'       => Hash::make($validated['password']),
            'account_status' => 'active',
            'profile_status' => 'verified',
        ]);

        // Mark email as verified immediately for super admin
        $user->markEmailAsVerified();

        Auth::login($user);

        return redirect()->route('admin.dashboard')
                         ->with('success', 'Super admin account created successfully. Welcome!');
    }
}
