<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View { return view('admin.cms.testimonials.index', ['testimonials' => Testimonial::orderBy('sort_order')->paginate(20)]); }
    public function create(): View { return view('admin.cms.testimonials.create'); }
    public function store(Request $request): RedirectResponse { Testimonial::create($request->validated()); return redirect()->route('admin.cms.testimonials.index')->with('success', 'Testimonial created.'); }
    public function edit(Testimonial $testimonial): View { return view('admin.cms.testimonials.edit', compact('testimonial')); }
    public function update(Request $request, Testimonial $testimonial): RedirectResponse { $testimonial->update($request->validated()); return back()->with('success', 'Testimonial updated.'); }
    public function destroy(Testimonial $testimonial): RedirectResponse { $testimonial->delete(); return redirect()->route('admin.cms.testimonials.index')->with('success', 'Deleted.'); }
    public function toggle(Testimonial $testimonial): RedirectResponse { $testimonial->update(['is_active' => !$testimonial->is_active]); return back()->with('success', 'Toggled.'); }
}