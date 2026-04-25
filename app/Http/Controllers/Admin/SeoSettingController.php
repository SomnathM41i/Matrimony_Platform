<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use App\Models\RobotsTxtSetting;
use App\Models\SitemapEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoSettingController extends Controller
{
    public function index(): View { return view('admin.seo.index', ['settings' => SeoSetting::orderBy('page_identifier')->paginate(20)]); }
    public function create(): View { return view('admin.seo.create'); }
    public function store(Request $request): RedirectResponse { SeoSetting::create($request->all()); return redirect()->route('admin.seo.settings.index')->with('success', 'SEO setting created.'); }
    public function edit(SeoSetting $setting): View { return view('admin.seo.edit', compact('setting')); }
    public function update(Request $request, SeoSetting $setting): RedirectResponse { $setting->update($request->all()); return back()->with('success', 'SEO updated.'); }
    public function destroy(SeoSetting $setting): RedirectResponse { $setting->delete(); return redirect()->route('admin.seo.settings.index')->with('success', 'Deleted.'); }
    public function sitemap(): View { return view('admin.seo.sitemap', ['entries' => SitemapEntry::orderBy('type')->paginate(30)]); }
    public function generateSitemap(Request $request): RedirectResponse { /* dispatch sitemap generation job */ return back()->with('success', 'Sitemap generation queued.'); }
    public function robots(): View { return view('admin.seo.robots', ['content' => RobotsTxtSetting::generate()]); }
    public function updateRobots(Request $request): RedirectResponse { /* update robots entries */ return back()->with('success', 'Robots.txt updated.'); }
}