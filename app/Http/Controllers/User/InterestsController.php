<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\SubscriptionUsage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InterestsController extends Controller
{
    // =========================================================================
    // SENT INTERESTS
    // GET /user/interests/sent  →  user.interests.sent
    // =========================================================================

    public function sent(Request $request): View
    {
        $authUser = Auth::user();

        $statusFilter = $request->input('status', 'all'); // all | pending | accepted | declined

        $query = Interest::where('sender_id', $authUser->id)
            ->with([
                'receiver',
                'receiver.profile',
                'receiver.profile.religion',
                'receiver.profile.city',
                'receiver.profile.educationLevel',
                'receiver.primaryPhoto',
            ])
            ->latest();

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $interests = $query->paginate(12)->withQueryString();

        // Counts for the tab badges
        $counts = [
            'all'      => Interest::where('sender_id', $authUser->id)->count(),
            'pending'  => Interest::where('sender_id', $authUser->id)->where('status', 'pending')->count(),
            'accepted' => Interest::where('sender_id', $authUser->id)->where('status', 'accepted')->count(),
            'declined' => Interest::where('sender_id', $authUser->id)->where('status', 'declined')->count(),
        ];

        return view('user.interests.sent', compact('interests', 'statusFilter', 'counts'));
    }

    // =========================================================================
    // RECEIVED INTERESTS
    // GET /user/interests/received  →  user.interests.received
    // =========================================================================

    public function received(Request $request): View
    {
        $authUser = Auth::user();

        $statusFilter = $request->input('status', 'pending'); // pending | accepted | declined

        $query = Interest::where('receiver_id', $authUser->id)
            ->with([
                'sender',
                'sender.profile',
                'sender.profile.religion',
                'sender.profile.city',
                'sender.profile.educationLevel',
                'sender.primaryPhoto',
            ])
            ->latest();

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $interests = $query->paginate(12)->withQueryString();

        $counts = [
            'all'      => Interest::where('receiver_id', $authUser->id)->count(),
            'pending'  => Interest::where('receiver_id', $authUser->id)->where('status', 'pending')->count(),
            'accepted' => Interest::where('receiver_id', $authUser->id)->where('status', 'accepted')->count(),
            'declined' => Interest::where('receiver_id', $authUser->id)->where('status', 'declined')->count(),
        ];

        return view('user.interests.received', compact('interests', 'statusFilter', 'counts'));
    }

    // =========================================================================
    // SEND INTEREST
    // POST /user/interests/send/{user}  →  user.interests.send
    // =========================================================================

    public function send(Request $request, User $user): RedirectResponse|JsonResponse
    {
        $authUser = Auth::user();

        // Guard: can't send to self
        if ($authUser->id === $user->id) {
            return $this->respond($request, 'error', 'You cannot send interest to yourself.');
        }

        // Guard: already sent
        $existing = Interest::where('sender_id', $authUser->id)
                            ->where('receiver_id', $user->id)
                            ->first();

        if ($existing) {
            return $this->respond($request, 'info', 'Interest already sent.');
        }

        // Guard: total interests limit for the plan duration
        $authUser->loadMissing('activeSubscription.package');
        $sub = $authUser->activeSubscription;
        if ($sub?->isValid()) {
            $limit = (int) $sub->package->interests_limit;
            if ($limit > 0) {
                $used = SubscriptionUsage::getUsed($sub->id, 'interests_limit');
                if ($used >= $limit) {
                    return $this->respond($request, 'error',
                        "You have reached your plan's interest limit of {$limit}. Upgrade your plan to send more."
                    );
                }
            }
        }

        $request->validate([
            'message' => 'nullable|string|max:500',
        ]);

        Interest::create([
            'sender_id'   => $authUser->id,
            'receiver_id' => $user->id,
            'status'      => 'pending',
            'message'     => $request->input('message'),
        ]);

        // Track usage against subscription
        if ($sub?->isValid()) {
            SubscriptionUsage::increment($authUser->id, $sub->id, 'interests_limit');
        }

        return $this->respond($request, 'success', 'Interest sent successfully!');
    }

    // =========================================================================
    // CANCEL INTEREST (sender withdraws a pending interest)
    // DELETE /user/interests/{interest}/cancel  →  user.interests.cancel
    // =========================================================================

    public function cancel(Request $request, Interest $interest): RedirectResponse|JsonResponse
    {
        $authUser = Auth::user();

        if ($interest->sender_id !== $authUser->id) {
            abort(403, 'Unauthorized.');
        }

        if ($interest->status !== 'pending') {
            return $this->respond($request, 'error', 'Only pending interests can be cancelled.');
        }

        $interest->delete();

        return $this->respond($request, 'success', 'Interest cancelled.');
    }

    // =========================================================================
    // ACCEPT INTEREST (receiver accepts)
    // PATCH /user/interests/{interest}/accept  →  user.interests.accept
    // =========================================================================

    public function accept(Request $request, Interest $interest): RedirectResponse|JsonResponse
    {
        $authUser = Auth::user();

        if ($interest->receiver_id !== $authUser->id) {
            abort(403, 'Unauthorized.');
        }

        if ($interest->status !== 'pending') {
            return $this->respond($request, 'error', 'This interest has already been responded to.');
        }

        $interest->update([
            'status'       => 'accepted',
            'responded_at' => now(),
        ]);

        return $this->respond($request, 'success', 'Interest accepted! You are now connected.');
    }

    // =========================================================================
    // DECLINE INTEREST (receiver declines)
    // PATCH /user/interests/{interest}/decline  →  user.interests.decline
    // =========================================================================

    public function decline(Request $request, Interest $interest): RedirectResponse|JsonResponse
    {
        $authUser = Auth::user();

        if ($interest->receiver_id !== $authUser->id) {
            abort(403, 'Unauthorized.');
        }

        if ($interest->status !== 'pending') {
            return $this->respond($request, 'error', 'This interest has already been responded to.');
        }

        $interest->update([
            'status'       => 'declined',
            'responded_at' => now(),
        ]);

        return $this->respond($request, 'success', 'Interest declined.');
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Unified response — JSON for AJAX, redirect for standard form POST.
     */
    private function respond(Request $request, string $type, string $message): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            $status = $type === 'success' ? 200 : ($type === 'error' ? 422 : 200);
            return response()->json(['type' => $type, 'message' => $message], $status);
        }

        return back()->with($type, $message);
    }
}