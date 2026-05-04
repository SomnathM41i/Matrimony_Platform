@extends('user.layouts.app')

@section('title', 'My Shortlist — ' . config('app.name'))

@section('content')

<section class="page-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a><span>/</span>
            <a href="{{ route('user.dashboard') }}">Dashboard</a><span>/</span>
            <span>Shortlist</span>
        </div>
        <h1>My Shortlist ★</h1>
        <p>{{ $totalCount }} profile{{ $totalCount !== 1 ? 's' : '' }} saved — revisit any time</p>
    </div>
</section>

<style>
.sl-page { background: var(--bg-light, #f7f4f0); min-height: 100vh; padding-bottom: 60px; }

/* ── Topbar ── */
.sl-topbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; }
.sl-count { font-size: 0.9rem; color: #6b7280; }
.sl-count strong { color: #1a1033; }
.sl-sort { padding: 8px 12px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 0.84rem; color: #374151; background: #fff; cursor: pointer; outline: none; }
.sl-sort:focus { border-color: #7c3aed; }
.sl-nav-links { display: flex; gap: 10px; }

/* ── Grid ── */
.sl-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }

/* ── Card ── */
.sl-card {
    background: #fff; border-radius: 16px; overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1.5px solid transparent;
    transition: transform .2s, box-shadow .2s, border-color .2s; position: relative;
}
.sl-card:hover { transform: translateY(-3px); box-shadow: 0 8px 26px rgba(124,58,237,.1); border-color: rgba(124,58,237,.18); }

/* Remove button (top-right absolute) */
.sl-remove-btn {
    position: absolute; top: 10px; right: 10px; z-index: 10;
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(0,0,0,.55); border: none; color: #fff;
    font-size: 0.7rem; cursor: pointer; transition: background .2s;
    display: flex; align-items: center; justify-content: center;
    backdrop-filter: blur(4px);
}
.sl-remove-btn:hover { background: #ef4444; }

/* Photo */
.sl-photo-wrap { height: 210px; background: #f3e8ff; overflow: hidden; position: relative; }
.sl-photo { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .3s; }
.sl-card:hover .sl-photo { transform: scale(1.04); }
.sl-photo-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 4rem; color: #c4b5fd; }
.sl-premium-badge {
    position: absolute; bottom: 10px; left: 10px;
    background: linear-gradient(135deg, #d4a017, #f59e0b);
    color: #1a1033; font-size: 0.62rem; font-weight: 700;
    padding: 3px 8px; border-radius: 20px;
}
.sl-saved-since {
    position: absolute; bottom: 10px; right: 10px;
    background: rgba(26,16,51,.75); backdrop-filter: blur(5px);
    color: rgba(255,255,255,0.8); font-size: 0.65rem;
    padding: 3px 8px; border-radius: 20px;
}

/* Body */
.sl-body { padding: 14px; }
.sl-name { font-size: 1rem; font-weight: 700; color: #1a1033; margin: 0 0 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sl-sub { font-size: 0.78rem; color: #7c3aed; font-weight: 600; margin-bottom: 8px; }
.sl-meta { display: flex; flex-direction: column; gap: 3px; margin-bottom: 12px; }
.sl-meta-item { font-size: 0.77rem; color: #6b7280; display: flex; align-items: center; gap: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* Actions */
.sl-actions { display: flex; gap: 7px; }
.sl-btn {
    flex: 1; padding: 8px 4px; border-radius: 8px; font-size: 0.77rem; font-weight: 600;
    border: none; cursor: pointer; transition: all .2s;
    display: flex; align-items: center; justify-content: center; gap: 4px; text-decoration: none;
}
.sl-btn.primary { background: #7c3aed; color: #fff; }
.sl-btn.primary:hover { background: #6d28d9; }
.sl-btn.primary.sent { background: #d8b4fe; color: #6d28d9; cursor: default; }
.sl-btn.ghost { background: transparent; color: #9ca3af; border: 1.5px solid #e5e7eb; }
.sl-btn.ghost:hover { color: #374151; border-color: #9ca3af; }

/* ── Empty ── */
.sl-empty { text-align: center; padding: 60px 24px; background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
.sl-empty .icon { font-size: 3.5rem; margin-bottom: 14px; }
.sl-empty h3 { font-size: 1.15rem; color: #1a1033; margin: 0 0 8px; }
.sl-empty p { font-size: 0.88rem; color: #6b7280; margin: 0 0 18px; }

/* ── Pagination ── */
.pagination-wrap { margin-top: 28px; display: flex; justify-content: center; }
.pagination-wrap nav span, .pagination-wrap nav a {
    display: inline-flex; align-items: center; justify-content: center;
    padding: 8px 14px; margin: 0 2px; border-radius: 8px; font-size: 0.85rem;
    border: 1.5px solid #e5e7eb; color: #374151; text-decoration: none; transition: all .2s;
}
.pagination-wrap nav a:hover { background: #f3e8ff; border-color: #7c3aed; color: #7c3aed; }
.pagination-wrap nav span[aria-current] { background: #7c3aed; color: #fff; border-color: #7c3aed; }
</style>

<div class="sl-page">
<div class="container section-sm">

    @foreach(['success','error','info'] as $t)
        @if(session($t))
            <div class="alert alert-{{ $t }}" style="margin-bottom:14px;">{{ session($t) }}</div>
        @endif
    @endforeach

    {{-- Topbar --}}
    <div class="sl-topbar">
        <div class="sl-nav-links">
            <a href="{{ route('user.matches.index') }}" class="btn btn-outline btn-sm">💕 Matches</a>
            <a href="{{ route('user.search.index') }}"  class="btn btn-outline btn-sm">🔍 Search</a>
            <a href="{{ route('user.interests.sent') }}" class="btn btn-outline btn-sm">💌 Interests</a>
        </div>

        @if($shortlists->isNotEmpty())
        <form method="GET" action="{{ route('user.shortlist.index') }}" id="sortForm">
            <select name="sort" class="sl-sort" onchange="this.form.submit()">
                <option value="newest"   {{ $sortBy === 'newest'   ? 'selected' : '' }}>Sort: Newest Added</option>
                <option value="name"     {{ $sortBy === 'name'     ? 'selected' : '' }}>Sort: Name A–Z</option>
                <option value="age_asc"  {{ $sortBy === 'age_asc'  ? 'selected' : '' }}>Sort: Age ↑</option>
                <option value="age_desc" {{ $sortBy === 'age_desc' ? 'selected' : '' }}>Sort: Age ↓</option>
            </select>
        </form>
        @endif
    </div>

    @if($shortlists->isEmpty())
        <div class="sl-empty">
            <div class="icon">☆</div>
            <h3>Your shortlist is empty</h3>
            <p>Save profiles you like using the ☆ button on any profile or match card.</p>
            <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('user.matches.index') }}" class="btn btn-primary">Browse Matches</a>
                <a href="{{ route('user.search.index') }}"  class="btn btn-outline">Search Members</a>
            </div>
        </div>
    @else
        <div class="sl-grid">
            @foreach($shortlists as $sl)
                @php
                    $member = $sl->shortlistedUser;
                    $mp     = $member->profile;
                    $photo  = $member->primaryPhoto?->url;
                    $name   = $mp?->first_name
                                ? ($mp->first_name . ' ' . substr($mp->last_name ?? '', 0, 1) . '.')
                                : $member->name;
                    $age    = $member->date_of_birth?->age;
                    $loc    = collect([$mp?->city?->name, $mp?->state?->name])->filter()->implode(', ');
                @endphp
                <div class="sl-card">

                    {{-- Remove from shortlist (×) --}}
                    <form method="POST" action="{{ route('user.shortlist.remove', $sl) }}" style="display:contents;">
                        @csrf @method('DELETE')
                        <button type="submit" class="sl-remove-btn" title="Remove from shortlist"
                                onclick="return confirm('Remove from shortlist?')">✕</button>
                    </form>

                    {{-- Photo --}}
                    <div class="sl-photo-wrap">
                        @if($photo)
                            <img src="{{ $photo }}" alt="{{ $name }}" class="sl-photo"
                                 onerror="this.parentElement.innerHTML='<div class=\'sl-photo-placeholder\'>👤</div>'">
                        @else
                            <div class="sl-photo-placeholder">👤</div>
                        @endif
                        @if($member->is_premium)
                            <span class="sl-premium-badge">⭐ Premium</span>
                        @endif
                        <span class="sl-saved-since">★ {{ $sl->created_at->diffForHumans(null, true) }}</span>
                    </div>

                    {{-- Body --}}
                    <div class="sl-body">
                        <h3 class="sl-name" title="{{ $name }}">{{ $name }}</h3>
                        <p class="sl-sub">
                            {{ $age ? $age . ' yrs' : '' }}
                            @if($member->profile_slug)
                                · <span style="color:#9ca3af;font-weight:400;">{{ $member->profile_slug }}</span>
                            @endif
                        </p>
                        <div class="sl-meta">
                            @if($loc)<span class="sl-meta-item">📍 {{ $loc }}</span>@endif
                            @if($mp?->religion?->name)<span class="sl-meta-item">🛕 {{ $mp->religion->name }}</span>@endif
                            @if($mp?->educationLevel?->name)<span class="sl-meta-item">🎓 {{ $mp->educationLevel->name }}</span>@endif
                            @if($mp?->profession?->name)<span class="sl-meta-item">💼 {{ $mp->profession->name }}</span>@endif
                        </div>
                        <div class="sl-actions">
                            @if($member->interest_sent)
                                <button class="sl-btn primary sent" disabled>✓ Interest Sent</button>
                            @else
                                <form method="POST" action="{{ route('user.interests.send', $member) }}" style="flex:1;">
                                    @csrf
                                    <button class="sl-btn primary" style="width:100%;">💌 Send Interest</button>
                                </form>
                            @endif
                            <a href="{{ route('user.profile.public', $member->profile_slug ?? $member->id) }}"
                               class="sl-btn ghost" title="View Profile">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($shortlists->hasPages())
            <div class="pagination-wrap">{{ $shortlists->links() }}</div>
        @endif
    @endif

</div>
</div>
@endsection