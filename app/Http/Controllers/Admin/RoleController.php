<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount('users')->with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:50', 'unique:roles,name'],
            'display_name' => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string'],
            'permissions'  => ['nullable', 'array'],
            'permissions.*'=> ['exists:permissions,id'],
        ]);

        $role = Role::create($validated);
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index')
                         ->with('success', "Role '{$role->display_name}' created.");
    }

    public function edit(Role $role): View
    {
        $permissions    = Permission::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        $rolePermIds    = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermIds'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string'],
            'permissions'  => ['nullable', 'array'],
            'permissions.*'=> ['exists:permissions,id'],
        ]);

        $role->update($validated);
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index')
                         ->with('success', "Role '{$role->display_name}' updated.");
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->users()->exists()) {
            return back()->with('error', 'Cannot delete role: it is assigned to users.');
        }
        $role->delete();
        return redirect()->route('admin.roles.index')
                         ->with('success', 'Role deleted.');
    }

    public function syncPermissions(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->permissions()->sync($request->input('permissions', []));

        return back()->with('success', 'Permissions synced successfully.');
    }
}


