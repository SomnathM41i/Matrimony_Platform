@extends('user.layouts.app')

@section('title', 'My Matches — ' . config('app.name'))

@section('content')

@php
    $fmt = fn($v) => $v ? ucwords(str_replace('_', ' ', $v)) : '—';
@endphp

{{-- ══════════════════════════════════════════════════════
     PAGE HERO
════════════════════════════════════════════════════════ --}}
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('user.dashboard') }}">Dashboard</a>
            <span>/</span>
            <span>My Matches</span>
        </div>
        <h1>Your Matches 💕</h1>
        <p>Profiles tailored to your preferences &amp; partner criteria</p>
    </div>
</section>

<style>
/* ── Page shell ─────────────────────────────────── */
.matches-page { background: var(--bg-light, #f7f4f0); min-height: 100vh; padding-bottom: 60px; }

/* ── Layout ─────────────────────────────────────── */
.matches-layout { display: grid; grid-template-columns: 280px 1fr; gap: 24px; align-items: start; }
@media (max-width: 900px) { .matches-layout { grid-template-columns: 1fr; } }

/* ── Sidebar card ───────────────────────────────── */
.filter-card { background: #fff; border-radius: 14px; padding: 22px; box-shadow: 0 2px 12px rgba(0,0,0,.06); position: sticky; top: 20px; }
.filter-card h3 { font-size: 0.95rem; font-weight: 700; color: #1a1033; margin: 0 0 16px; display:flex;align-items:center;gap:8px; }
.filter-section { margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #f0edf8; }
.filter-section:last-child { margin-bottom: 0; padding-bottom: 0; border: none; }
.filter-label { font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: .04em; margin-bottom: 8px; display: block; }
.filter-range { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.filter-range input[type=number], .filter-range input[type=text] {
    width: 100%; padding: 7px 10px; border: 1.5px solid #e5e7eb; border-radius: 8px;
    font-size: 0.85rem; color: #1f2937; background: #fafafa; outline: none;
    transition: border-color .2s;
}
.filter-range input:focus { border-color: #7c3aed; }
.filter-select {
    width: 100%; padding: 8px 10px; border: 1.5px solid #e5e7eb; border-radius: 8px;
    font-size: 0.85rem; color: #1f2937; background: #fafafa; outline: none; cursor: pointer;
    transition: border-color .2s;
}
.filter-select:focus { border-color: #7c3aed; }
.filter-checkbox-group { display: flex; flex-direction: column; gap: 7px; }
.filter-checkbox-group label { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: #374151; cursor: pointer; }
.filter-checkbox-group input[type=checkbox] { accent-color: #7c3aed; width: 15px; height: 15px; }
.filter-toggle { display: flex; align-items: center; justify-content: space-between; }
.filter-toggle .toggle-label { font-size: 0.85rem; color: #374151; }
.filter-toggle input[type=checkbox] { accent-color: #7c3aed; width: 16px; height: 16px; cursor: pointer; }

/* ── Quick stats (sidebar) ──────────────────────── */
.stat-mini { background: #f9f7ff; border-radius: 10px; padding: 12px 14px; margin-bottom: 14px; display: flex; gap: 10px; align-items: center; }
.stat-mini .num { font-size: 1.3rem; font-weight: 700; color: #7c3aed; }
.stat-mini .lbl { font-size: 0.78rem; color: #6b7280; line-height: 1.3; }

/* ── Top bar ────────────────────────────────────── */
.matches-topbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
.matches-topbar .count-label { font-size: 0.9rem; color: #6b7280; }
.matches-topbar .count-label strong { color: #1a1033; }
.sort-select {
    padding: 8px 14px; border: 1.5px solid #e5e7eb; border-radius: 8px;
    font-size: 0.85rem; color: #374151; background: #fff; cursor: pointer; outline: none;
}
.sort-select:focus { border-color: #7c3aed; }

/* ── Match cards grid ───────────────────────────── */
.matches-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }

/* ── Individual match card ──────────────────────── */
.match-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    border: 1.5px solid transparent;
    transition: transform .2s, box-shadow .2s, border-color .2s;
    position: relative;
}
.match-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(124,58,237,.12); border-color: rgba(124,58,237,.2); }

/* Photo area */
.match-photo-wrap { position: relative; height: 210px; background: #f0edf8; overflow: hidden; }
.match-photo { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .35s; }
.match-card:hover .match-photo { transform: scale(1.04); }
.match-photo-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #c4b5fd; font-size: 4rem; }

/* Premium badge */
.match-premium-badge {
    position: absolute; top: 10px; right: 10px;
    background: linear-gradient(135deg, #d4a017, #f59e0b);
    color: #1a1033; font-size: 0.65rem; font-weight: 700;
    padding: 3px 8px; border-radius: 20px; letter-spacing: .03em;
}

/* Compat score ring */
.match-compat {
    position: absolute; bottom: 10px; left: 10px;
    background: rgba(26,16,51,.82); backdrop-filter: blur(6px);
    color: #fff; font-size: 0.7rem; font-weight: 700;
    padding: 4px 9px; border-radius: 20px;
    display: flex; align-items: center; gap: 5px;
}
.match-compat .dot {
    width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
}
.match-compat .dot.high   { background: #22c55e; }
.match-compat .dot.medium { background: #f59e0b; }
.match-compat .dot.low    { background: #ef4444; }

/* Card body */
.match-body { padding: 14px; }
.match-name { font-size: 1rem; font-weight: 700; color: #1a1033; margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.match-age-id { font-size: 0.78rem; color: #7c3aed; font-weight: 600; margin-bottom: 8px; }
.match-meta { display: flex; flex-direction: column; gap: 3px; margin-bottom: 12px; }
.match-meta-item { font-size: 0.78rem; color: #6b7280; display: flex; align-items: center; gap: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.match-meta-item svg { flex-shrink: 0; }

/* Actions */
.match-actions { display: flex; gap: 8px; }
.match-btn {
    flex: 1; padding: 8px 4px; border-radius: 8px; font-size: 0.78rem; font-weight: 600;
    border: none; cursor: pointer; transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 5px;
    text-decoration: none;
}
.match-btn.primary { background: #7c3aed; color: #fff; }
.match-btn.primary:hover { background: #6d28d9; }
.match-btn.primary:disabled, .match-btn.primary.sent { background: #d8b4fe; color: #6d28d9; cursor: default; }
.match-btn.outline { background: transparent; color: #7c3aed; border: 1.5px solid #7c3aed; }
.match-btn.outline:hover { background: #f3e8ff; }
.match-btn.outline.shortlisted { background: #f3e8ff; color: #7c3aed; }
.match-btn.ghost { background: transparent; color: #9ca3af; border: 1.5px solid #e5e7eb; }
.match-btn.ghost:hover { color: #374151; border-color: #9ca3af; }

/* ── Empty state ────────────────────────────────── */
.empty-state { text-align: center; padding: 60px 24px; }
.empty-state .icon { font-size: 3.5rem; margin-bottom: 16px; }
.empty-state h3 { font-size: 1.2rem; color: #1a1033; margin: 0 0 8px; }
.match-empty-state p { font-size: 0.9rem; color: #6b7280; margin: 0 0 20px; }

/* ── Pagination ─────────────────────────────────── */
.pagination-wrap { margin-top: 32px; display: flex; justify-content: center; }
.pagination-wrap nav span, .pagination-wrap nav a {
    display: inline-flex; align-items: center; justify-content: center;
    padding: 8px 14px; margin: 0 2px; border-radius: 8px; font-size: 0.85rem;
    border: 1.5px solid #e5e7eb; color: #374151; text-decoration: none; transition: all .2s;
}
.pagination-wrap nav a:hover { background: #f3e8ff; border-color: #7c3aed; color: #7c3aed; }
.pagination-wrap nav span[aria-current] { background: #7c3aed; color: #fff; border-color: #7c3aed; }
</style>

<div class="matches-page">
<div class="container section-sm">

    {{-- Flash messages --}}
    @foreach(['success','error','info'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type }}" style="margin-bottom:16px;">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    <div class="matches-layout">

        {{-- ═══════════════════════════════════════════
             SIDEBAR — FILTERS
        ════════════════════════════════════════════ --}}
        <aside>

            {{-- Quick stats --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:18px;">
                <div class="stat-mini">
                    <div>
                        <div class="num">{{ $stats['total_matches'] }}</div>
                        <div class="lbl">Total<br>Matches</div>
                    </div>
                </div>
                <div class="stat-mini">
                    <div>
                        <div class="num" style="color:#d4a017;">{{ $stats['interests_sent'] }}</div>
                        <div class="lbl">Interests<br>Sent</div>
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('user.matches.index') }}" id="filterForm">

                <div class="filter-card">
                    <h3>
                        <svg width="16" height="16" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M6 12h12M9 18h6"/></svg>
                        Filter Matches
                    </h3>

                    {{-- Age --}}
                    <div class="filter-section">
                        <span class="filter-label">Age Range</span>
                        <div class="filter-range">
                            <div>
                                <input type="number" name="age_min" value="{{ $filters['age_min'] ?? 18 }}" min="18" max="80" placeholder="Min">
                            </div>
                            <div>
                                <input type="number" name="age_max" value="{{ $filters['age_max'] ?? 60 }}" min="18" max="80" placeholder="Max">
                            </div>
                        </div>
                    </div>

                    {{-- Marital Status --}}
                    <div class="filter-section">
                        <span class="filter-label">Marital Status</span>
                        <div class="filter-checkbox-group">
                            @foreach(['never_married','divorced','widowed','separated'] as $ms)
                                <label>
                                    <input type="checkbox" name="marital_status[]" value="{{ $ms }}"
                                        {{ in_array($ms, $filters['marital_status'] ?? []) ? 'checked' : '' }}>
                                    {{ $fmt($ms) }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Height --}}
                    <div class="filter-section">
                        <span class="filter-label">Height (cm)</span>
                        <div class="filter-range">
                            <input type="number" name="height_min" value="{{ $filters['height_min'] ?? '' }}" placeholder="Min" min="120" max="220">
                            <input type="number" name="height_max" value="{{ $filters['height_max'] ?? '' }}" placeholder="Max" min="120" max="220">
                        </div>
                    </div>

                    {{-- Photo --}}
                    <div class="filter-section">
                        <div class="filter-toggle">
                            <span class="toggle-label">With Photo Only</span>
                            <input type="checkbox" name="with_photo" value="1"
                                {{ ($filters['with_photo'] ?? true) ? 'checked' : '' }}>
                        </div>
                    </div>

                    {{-- Premium --}}
                    <div class="filter-section">
                        <div class="filter-toggle">
                            <span class="toggle-label">Premium Members</span>
                            <input type="checkbox" name="premium_only" value="1"
                                {{ !empty($filters['premium_only']) ? 'checked' : '' }}>
                        </div>
                    </div>

                    {{-- Keep sort --}}
                    <input type="hidden" name="sort" value="{{ $sort }}">

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:4px;">
                        <button type="submit" class="btn btn-primary btn-sm" style="justify-content:center;">Apply</button>
                        <a href="{{ route('user.matches.index') }}" class="btn btn-outline btn-sm" style="justify-content:center;text-align:center;">Reset</a>
                    </div>

                </div>

            </form>

        </aside>

        {{-- ═══════════════════════════════════════════
             MAIN — MATCHES GRID
        ════════════════════════════════════════════ --}}
        <div>

            {{-- Top bar --}}
            <div class="matches-topbar">
                <p class="count-label">
                    Showing <strong>{{ $matches->firstItem() ?? 0 }}–{{ $matches->lastItem() ?? 0 }}</strong>
                    of <strong>{{ $matches->total() }}</strong> matches
                </p>
                <form method="GET" action="{{ route('user.matches.index') }}" id="sortForm">
                    @foreach($filters as $key => $val)
                        @if(is_array($val))
                            @foreach($val as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endif
                    @endforeach
                    <select name="sort" class="sort-select" onchange="this.form.submit()">
                        <option value="relevance"  {{ $sort === 'relevance'  ? 'selected' : '' }}>Sort: Most Relevant</option>
                        <option value="newest"     {{ $sort === 'newest'     ? 'selected' : '' }}>Newest Profiles</option>
                        <option value="last_active"{{ $sort === 'last_active'? 'selected' : '' }}>Last Active</option>
                        <option value="age_asc"    {{ $sort === 'age_asc'   ? 'selected' : '' }}>Age: Low to High</option>
                        <option value="age_desc"   {{ $sort === 'age_desc'  ? 'selected' : '' }}>Age: High to Low</option>
                        <option value="completion" {{ $sort === 'completion' ? 'selected' : '' }}>Profile Completeness</option>
                    </select>
                </form>
            </div>

            @if($matches->isEmpty())
                {{-- Empty state --}}
                <div class="empty-state">
                    <div class="icon">💔</div>
                    <h3>No matches found</h3>
                    <p class="match-empty-state">Try adjusting your filters or update your partner preferences for better suggestions.</p>
                    <a href="{{ route('user.profile.setup.show', 6) }}" class="btn btn-primary">Update Preferences</a>
                </div>
            @else
                <div class="matches-grid">
                    @foreach($matches as $match)
            
                        @php
                            $mp         = $match->profile;
                            $photoUrl   = $match->primaryPhoto?->url ?? null;
                            $name       = $mp?->first_name ? ($mp->first_name . ' ' . substr($mp->last_name ?? '', 0, 1) . '.') : $match->name;
                            $age        = $match->date_of_birth?->age;
                            $compat     = $match->compat_score;
                            $compatDot  = $compat >= 70 ? 'high' : ($compat >= 45 ? 'medium' : 'low');
                            $compatClr  = $compat >= 70 ? '#22c55e' : ($compat >= 45 ? '#f59e0b' : '#ef4444');

                            $locationParts = array_filter([
                                $mp?->city?->name,
                                $mp?->state?->name,
                            ]);
                            $location = implode(', ', $locationParts);

                            $details = array_filter([
                                $mp?->educationLevel?->name,
                                $mp?->profession?->name,
                                $mp?->religion?->name,
                            ]);
                        @endphp

                        <div class="match-card">

                            {{-- Photo --}}
                            <div class="match-photo-wrap">
                                @if($photoUrl)
                                    <img src="{{ $photoUrl }}" alt="{{ $name }}" class="match-photo"
                                         onerror="this.parentElement.innerHTML='<div class=\'match-photo-placeholder\'>👤</div>'">
                                @else
                                    <div class="match-photo-placeholder">👤</div>
                                @endif

                                @if($match->is_premium)
                                    <span class="match-premium-badge">⭐ Premium</span>
                                @endif

                                <div class="match-compat">
                                    <span class="dot {{ $compatDot }}"></span>
                                    {{ $compat }}% Match
                                </div>
                            </div>

                            {{-- Body --}}
                            <div class="match-body">
                                <h3 class="match-name" title="{{ $name }}">{{ $name }}</h3>
                                <p class="match-age-id">
                                    {{ $age ? $age . ' yrs' : '' }}
                                    @if($match->profile_slug)
                                        &nbsp;·&nbsp; <span style="font-weight:400;color:#9ca3af;">{{ $match->profile_slug }}</span>
                                    @endif
                                </p>

                                <div class="match-meta">
                                    @if($location)
                                        <span class="match-meta-item">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 21s-8-7.33-8-13a8 8 0 0116 0c0 5.67-8 13-8 13z"/><circle cx="12" cy="8" r="2"/></svg>
                                            {{ $location }}
                                        </span>
                                    @endif
                                    @foreach(array_slice($details, 0, 2) as $detail)
                                        <span class="match-meta-item">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l3 3"/></svg>
                                            {{ $detail }}
                                        </span>
                                    @endforeach
                                </div>

                                {{-- Actions --}}
                                <div class="match-actions">
                                    {{-- Send / Sent Interest --}}
                                    @if($match->interest_sent)
                                        <button class="match-btn primary sent" disabled>
                                            ✓ Sent
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('user.interests.send', $match) }}" style="flex:1;">
                                            @csrf
                                            <button type="submit" class="match-btn primary" style="width:100%;">
                                                💌 Interest
                                            </button>
                                        </form>
                                    @endif

                                    {{-- View Profile --}}
                                    <a href="{{ route('user.profile.public', $match->profile_slug ?? $match->id) }}"
                                       class="match-btn ghost"
                                       title="View Profile">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>

                                    {{-- Shortlist --}}
                                    <form method="POST" action="{{ route('user.shortlist.toggle', $match) }}" style="display:contents;">
                                        @csrf
                                        <button type="submit" class="match-btn outline {{ $match->is_shortlisted ? 'shortlisted' : '' }}" title="{{ $match->is_shortlisted ? 'Remove from shortlist' : 'Shortlist' }}">
                                            {{ $match->is_shortlisted ? '★' : '☆' }}
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($matches->hasPages())
                    <div class="pagination-wrap">
                        {{ $matches->links() }}
                    </div>
                @endif
            @endif

        </div>{{-- /main --}}
    </div>{{-- /layout --}}
</div>{{-- /container --}}
</div>{{-- /matches-page --}}

@endsection