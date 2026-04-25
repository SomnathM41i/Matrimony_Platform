<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View { return view('admin.cms.faqs.index', ['faqs' => Faq::orderBy('sort_order')->paginate(20)]); }
    public function create(): View { return view('admin.cms.faqs.create'); }
    public function store(Request $request): RedirectResponse { Faq::create($request->validate(['question' => 'required', 'answer' => 'required', 'category' => 'nullable|string', 'sort_order' => 'nullable|integer'])); return redirect()->route('admin.cms.faqs.index')->with('success', 'FAQ created.'); }
    public function edit(Faq $faq): View { return view('admin.cms.faqs.edit', compact('faq')); }
    public function update(Request $request, Faq $faq): RedirectResponse { $faq->update($request->all()); return back()->with('success', 'FAQ updated.'); }
    public function destroy(Faq $faq): RedirectResponse { $faq->delete(); return redirect()->route('admin.cms.faqs.index')->with('success', 'Deleted.'); }
    public function toggle(Faq $faq): RedirectResponse { $faq->update(['is_active' => !$faq->is_active]); return back()->with('success', 'Toggled.'); }
    public function reorder(Request $request): \Illuminate\Http\JsonResponse { foreach ($request->input('order', []) as $i => $id) Faq::where('id', $id)->update(['sort_order' => $i]); return response()->json(['success' => true]); }
}