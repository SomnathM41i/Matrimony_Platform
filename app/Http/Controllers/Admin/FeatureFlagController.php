<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeatureFlag;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

// ─────────────────────────────────────────────────────────────────────────────
// FEATURE FLAG CONTROLLER
// ─────────────────────────────────────────────────────────────────────────────

class FeatureFlagController extends Controller
{
    public function index(): View
    {
        $flags = FeatureFlag::orderBy('key')->get();
        return view('admin.feature-flags.index', compact('flags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key'         => ['required', 'string', 'max:100', 'unique:feature_flags,key'],
            'label'       => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'is_enabled'  => ['boolean'],
            'config'      => ['nullable', 'json'],
        ]);

        $validated['is_enabled'] = $request->boolean('is_enabled');
        $validated['config']     = $request->filled('config') ? json_decode($request->config, true) : null;

        FeatureFlag::create($validated);

        return redirect()->route('admin.feature-flags.index')
                         ->with('success', 'Feature flag created.');
    }

    public function update(Request $request, FeatureFlag $flag): RedirectResponse
    {
        $validated = $request->validate([
            'label'       => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'is_enabled'  => ['boolean'],
            'config'      => ['nullable', 'json'],
        ]);

        $validated['is_enabled'] = $request->boolean('is_enabled');
        $validated['config']     = $request->filled('config') ? json_decode($request->config, true) : null;

        $flag->update($validated);

        return redirect()->route('admin.feature-flags.index')
                         ->with('success', "Feature flag '{$flag->key}' updated.");
    }

    public function toggle(FeatureFlag $flag): RedirectResponse
    {
        $flag->update(['is_enabled' => !$flag->is_enabled]);
        $state = $flag->is_enabled ? 'enabled' : 'disabled';
        return back()->with('success', "Feature '{$flag->label}' {$state}.");
    }

    public function destroy(FeatureFlag $flag): RedirectResponse
    {
        $flag->delete();
        return redirect()->route('admin.feature-flags.index')
                         ->with('success', 'Feature flag deleted.');
    }
}