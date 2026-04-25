<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View { return view('admin.cms.menus.index', ['locations' => MenuLocation::with('menus')->get(), 'menus' => Menu::with('location','parent')->paginate(30)]); }
    public function create(): View { return view('admin.cms.menus.create', ['locations' => MenuLocation::all(), 'parentMenus' => Menu::whereNull('parent_id')->get()]); }
    public function store(Request $request): RedirectResponse { Menu::create($request->all()); return redirect()->route('admin.cms.menus.index')->with('success', 'Menu item created.'); }
    public function edit(Menu $menu): View { return view('admin.cms.menus.edit', compact('menu') + ['locations' => MenuLocation::all(), 'parentMenus' => Menu::whereNull('parent_id')->where('id','!=',$menu->id)->get()]); }
    public function update(Request $request, Menu $menu): RedirectResponse { $menu->update($request->all()); return back()->with('success', 'Menu updated.'); }
    public function destroy(Menu $menu): RedirectResponse { $menu->delete(); return redirect()->route('admin.cms.menus.index')->with('success', 'Menu deleted.'); }
    public function reorder(Request $request): \Illuminate\Http\JsonResponse { foreach ($request->input('order', []) as $i => $id) Menu::where('id', $id)->update(['sort_order' => $i]); return response()->json(['success' => true]); }
}