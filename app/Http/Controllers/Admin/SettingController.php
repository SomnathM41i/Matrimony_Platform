<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeatureFlag;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

// ─────────────────────────────────────────────────────────────────────────────
// SETTING CONTROLLER
// ─────────────────────────────────────────────────────────────────────────────

class SettingController extends Controller
{
    /** Groups that have their own dedicated view */
    private const GROUPS = [
        'general', 'mail', 'sms', 'payment', 'social', 'notifications', 'limits', 'appearance',
    ];

    public function index(): View
    {
        $groups   = self::GROUPS;
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.index', compact('groups', 'settings'));
    }

    public function group(string $group): View
    {
        abort_unless(in_array($group, self::GROUPS), 404);
        $settings = Setting::where('group', $group)->orderBy('key')->get()->keyBy('key');
        return view("admin.settings.groups.{$group}", compact('settings', 'group'));
    }

    public function update(Request $request, string $group): RedirectResponse
    {
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            Setting::set($key, $value);
        }
        return back()->with('success', ucfirst($group) . ' settings saved.');
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        $settings = $request->validate(['settings' => ['required', 'array']]);
        foreach ($settings['settings'] as $key => $value) {
            Setting::set($key, $value);
        }
        return back()->with('success', 'Settings updated.');
    }
}



