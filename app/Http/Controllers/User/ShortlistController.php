<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\Shortlist;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ShortlistController extends Controller
{
    // =========================================================================
    // SHORTLIST INDEX
    // GET /user/shortlist  →  user.shortlist.index
    // =========================================================================

    public function index(Request $request): View
    {
        $authUser = Auth::user()->load(['sentInterests', 'shortlists']);

        $sortBy = $request->input('sort', 'newest'); // newest | age_asc | age_desc | name

        $query = Shortlist::where('user_id', $authUser->id)
            ->with([
                'shortlistedUser',
                'shortlistedUser.profile',
                'shortlistedUser.profile.religion',
                'shortlistedUser.profile.caste',
                'shortlistedUser.profile.educationLevel',
                'shortlistedUser.profile.profession',
                'shortlistedUser.profile.city',
                'shortlistedUser.profile.state',
                'shortlistedUser.primaryPhoto',
            ]);

        match ($sortBy) {
            'name'     => $query->join('users as su', 'shortlists.shortlisted_user_id', '=', 'su.id')
                                 ->orderBy('su.name')->select('shortlists.*'),
            'age_asc'  => $query->join('users as su', 'shortlists.shortlisted_user_id', '=', 'su.id')
                                 ->orderByDesc('su.date_of_birth')->select('shortlists.*'),
            'age_desc' => $query->join('users as su', 'shortlists.shortlisted_user_id', '=', 'su.id')
                                 ->orderBy('su.date_of_birth')->select('shortlists.*'),
            default    => $query->latest(), // newest shortlisted first
        };

        $shortlists = $query->paginate(12)->withQueryString();

        // Enrich with interest status
        $sentIds = $authUser->sentInterests->pluck('receiver_id')->flip();

        $shortlists->getCollection()->transform(function (Shortlist $sl) use ($sentIds) {
            $sl->shortlistedUser->interest_sent = $sentIds->has($sl->shortlisted_user_id);
            return $sl;
        });

        $totalCount = Shortlist::where('user_id', $authUser->id)->count();

        return view('user.shortlist.index', compact('shortlists', 'sortBy', 'totalCount'));
    }

    // =========================================================================
    // TOGGLE SHORTLIST
    // POST /user/shortlist/toggle/{user}  →  user.shortlist.toggle
    // =========================================================================

    public function toggle(Request $request, User $user): RedirectResponse|JsonResponse
    {
        $authUser = Auth::user();

        if ($authUser->id === $user->id) {
            return $this->respond($request, false, 'You cannot shortlist yourself.');
        }

        $existing = Shortlist::where('user_id', $authUser->id)
            ->where('shortlisted_user_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return $this->respond($request, false, 'Removed from shortlist.');
        }

        Shortlist::create([
            'user_id'            => $authUser->id,
            'shortlisted_user_id'=> $user->id,
        ]);

        return $this->respond($request, true, 'Added to shortlist!');
    }

    // =========================================================================
    // REMOVE FROM SHORTLIST
    // DELETE /user/shortlist/{shortlist}  →  user.shortlist.remove
    // =========================================================================

    public function remove(Request $request, Shortlist $shortlist): RedirectResponse|JsonResponse
    {
        abort_if($shortlist->user_id !== Auth::id(), 403);
        $shortlist->delete();
        return $this->respond($request, false, 'Removed from shortlist.');
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    private function respond(Request $request, bool $shortlisted, string $message): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'shortlisted' => $shortlisted,
                'message'     => $message,
            ]);
        }
        return back()->with('success', $message);
    }
}