<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsPageController extends Controller
{
    public function index(): View
    {
        $pages = CmsPage::orderBy('sort_order')->paginate(20);
        return view('admin.cms.pages.index', compact('pages'));
    }

    public function create(): View { return view('admin.cms.pages.create'); }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'            => ['required', 'string', 'max:200'],
            'slug'             => ['required', 'string', 'unique:cms_pages,slug'],
            'content'          => ['nullable', 'string'],
            'excerpt'          => ['nullable', 'string', 'max:500'],
            'meta_title'       => ['nullable', 'string', 'max:200'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'is_active'        => ['boolean'],
            'template'         => ['nullable', 'string'],
        ]);
        $page = CmsPage::create($data);
        return redirect()->route('admin.cms.pages.edit', $page)->with('success', 'Page created.');
    }

    public function edit(CmsPage $page): View { return view('admin.cms.pages.edit', compact('page')); }

    public function update(Request $request, CmsPage $page): RedirectResponse
    {
        $data = $request->validate([
            'title'            => ['required', 'string', 'max:200'],
            'slug'             => ['required', 'string', 'unique:cms_pages,slug,' . $page->id],
            'content'          => ['nullable', 'string'],
            'excerpt'          => ['nullable', 'string', 'max:500'],
            'meta_title'       => ['nullable', 'string', 'max:200'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'is_active'        => ['boolean'],
            'template'         => ['nullable', 'string'],
        ]);
        $page->update($data);
        return back()->with('success', 'Page updated.');
    }

    public function destroy(CmsPage $page): RedirectResponse
    {
        abort_if($page->is_system, 403, 'System pages cannot be deleted.');
        $page->delete();
        return redirect()->route('admin.cms.pages.index')->with('success', 'Page deleted.');
    }

    public function toggle(CmsPage $page): RedirectResponse
    {
        $page->update(['is_active' => !$page->is_active]);
        return back()->with('success', 'Page status toggled.');
    }

    public function reorder(Request $request): \Illuminate\Http\JsonResponse
    {
        foreach ($request->input('order', []) as $index => $id) {
            CmsPage::where('id', $id)->update(['sort_order' => $index]);
        }
        return response()->json(['success' => true]);
    }
}