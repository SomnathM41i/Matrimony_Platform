<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MatrimonyController extends Controller
{
    public function profiles(Request $request): View
    {
        $users = User::with('profile')
                     ->whereHas('role', fn($q) => $q->where('name', 'user'))
                     ->when($request->status, fn($q) => $q->where('profile_status', $request->status))
                     ->paginate(20);
        return view('admin.matrimony.profiles', compact('users'));
    }
    public function profileDetail(User $user): View { $user->load('profile.religion','profile.caste','profile.city','partnerPreference','photos'); return view('admin.matrimony.profile-detail', compact('user')); }
    public function approveProfile(User $user): RedirectResponse { $user->update(['profile_status' => 'approved']); return back()->with('success', 'Profile approved.'); }
    public function rejectProfile(User $user): RedirectResponse { $user->update(['profile_status' => 'rejected']); return back()->with('success', 'Profile rejected.'); }
    public function interests(Request $request): View { return view('admin.matrimony.interests', ['interests' => \App\Models\Interest::with('sender','receiver')->latest()->paginate(20)]); }
    public function shortlists(Request $request): View { return view('admin.matrimony.shortlists', ['shortlists' => \App\Models\Shortlist::with('user')->latest()->paginate(20)]); }
    public function compatibility(Request $request): View { return view('admin.matrimony.compatibility'); }
    public function photoRequests(Request $request): View { return view('admin.matrimony.photo-requests'); }
}