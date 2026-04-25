<?php

// ──────────────────────────────────────────────────────────────────────────────
// PERMISSION CONTROLLER (in same file for brevity — split if preferred)
// ──────────────────────────────────────────────────────────────────────────────

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(): View
    {
        $permissions = Permission::withCount('roles')->orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create(): View
    {
        $groups = Permission::distinct()->pluck('group')->filter()->sort()->values();
        return view('admin.permissions.create', compact('groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:100', 'unique:permissions,name'],
            'display_name' => ['required', 'string', 'max:150'],
            'group'        => ['nullable', 'string', 'max:50'],
        ]);

        Permission::create($validated);

        return redirect()->route('admin.permissions.index')
                         ->with('success', 'Permission created.');
    }

    public function edit(Permission $permission): View
    {
        $groups = Permission::distinct()->pluck('group')->filter()->sort()->values();
        return view('admin.permissions.edit', compact('permission', 'groups'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:150'],
            'group'        => ['nullable', 'string', 'max:50'],
        ]);

        $permission->update($validated);

        return redirect()->route('admin.permissions.index')
                         ->with('success', 'Permission updated.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();
        return redirect()->route('admin.permissions.index')
                         ->with('success', 'Permission deleted.');
    }
}
