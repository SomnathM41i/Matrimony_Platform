<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View { $banners = Banner::orderBy('sort_order')->paginate(20); return view('admin.cms.banners.index', compact('banners')); }
    public function create(): View { return view('admin.cms.banners.create'); }
    public function store(Request $request): RedirectResponse { /* validate + store image */ return redirect()->route('admin.cms.banners.index')->with('success', 'Banner created.'); }
    public function edit(Banner $banner): View { return view('admin.cms.banners.edit', compact('banner')); }
    public function update(Request $request, Banner $banner): RedirectResponse { /* update */ return back()->with('success', 'Banner updated.'); }
    public function destroy(Banner $banner): RedirectResponse { $banner->delete(); return redirect()->route('admin.cms.banners.index')->with('success', 'Banner deleted.'); }
    public function toggle(Banner $banner): RedirectResponse { $banner->update(['is_active' => !$banner->is_active]); return back()->with('success', 'Banner toggled.'); }
}