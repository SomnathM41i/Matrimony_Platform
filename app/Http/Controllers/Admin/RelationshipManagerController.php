<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RelationshipManagerAssignment;
use App\Models\RmInteraction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RelationshipManagerController extends Controller
{
    public function index(): View
    {
        $rms = User::whereHas('role', fn($q) => $q->where('name', 'relationship_manager'))
                   ->withCount('assignedUsers')->paginate(20);
        return view('admin.rm.index', compact('rms'));
    }
    public function show(User $rm): View { $rm->load('assignedUsers.profile'); return view('admin.rm.show', compact('rm')); }
    public function assignUser(User $rm, User $user): RedirectResponse
    {
        $user->update(['assigned_rm_id' => $rm->id]);
        RelationshipManagerAssignment::create(['rm_id' => $rm->id, 'user_id' => $user->id, 'is_active' => true, 'assigned_at' => now()]);
        return back()->with('success', 'User assigned.');
    }
    public function unassignUser(User $rm, User $user): RedirectResponse
    {
        $user->update(['assigned_rm_id' => null]);
        RelationshipManagerAssignment::where('rm_id', $rm->id)->where('user_id', $user->id)->update(['is_active' => false, 'unassigned_at' => now()]);
        return back()->with('success', 'User unassigned.');
    }
    public function interactions(User $rm): View { return view('admin.rm.interactions', ['rm' => $rm, 'interactions' => $rm->rmInteractions()->with('user')->latest('interacted_at')->paginate(20)]); }
    public function logInteraction(Request $request, User $rm): RedirectResponse
    {
        $data = $request->validate(['user_id' => 'required|exists:users,id', 'type' => 'required|string', 'description' => 'nullable|string']);
        RmInteraction::create(array_merge($data, ['rm_id' => $rm->id, 'interacted_at' => now()]));
        return back()->with('success', 'Interaction logged.');
    }
    public function assignedUsers(User $rm): View { return view('admin.rm.assigned-users', ['rm' => $rm, 'users' => $rm->assignedUsers()->with('profile')->paginate(20)]); }
}