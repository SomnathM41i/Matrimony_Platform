@extends('user.layouts.app')

@section('title', 'Search Members — ' . config('app.name'))

@section('content')

@php
    $fmt = fn($v) => $v ? ucwords(str_replace('_', ' ', $v)) : '—';
    $f   = $filters ?? [];
@endphp

<section class="page-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a><span>/</span>
            <a href="{{ route('user.dashboard') }}">Dashboard</a><span>/</span>
            <span>Search</span>
        </div>
        <h1>Search Members 🔍</h1>
        <p>Filter by any combination of criteria to find your ideal match</p>
    </div>
</section>

<style>
.search-page { background: var(--bg-light, #f7f4f0); min-height: 100vh; padding-bottom: 60px; }
.search-layout { display: grid; grid-template-columns: 290px 1fr; gap: 24px; align-items: start; }
@media (max-width: 900px) { .search-layout { grid-template-columns: 1fr; } }

/* ── Sidebar ── */
.search-sidebar { position: sticky; top: 20px; }
.filter-card { background: #fff; border-radius: 14px; padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,.06); margin-bottom: 16px; }
.filter-card-title { font-size: 0.88rem; font-weight: 700; color: #1a1033; margin: 0 0 14px; display: flex; align-items: center; gap: 7px; }
.filter-section { margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #f0edf8; }
.filter-section:last-child { margin-bottom: 0; padding-bottom: 0; border: none; }
.filter-label { font-size: 0.7rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; display: block; }
.filter-input, .filter-select {
    width: 100%; padding: 8px 10px; border: 1.5px solid #e5e7eb; border-radius: 8px;
    font-size: 0.84rem; color: #1f2937; background: #fafafa; outline: none;
    transition: border-color .2s; box-sizing: border-box;
}
.filter-input:focus, .filter-select:focus { border-color: #7c3aed; }
.filter-range { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.filter-checkbox-group { display: flex; flex-direction: column; gap: 6px; }
.filter-checkbox-group label { display: flex; align-items: center; gap: 7px; font-size: 0.83rem; color: #374151; cursor: pointer; }
.filter-checkbox-group input[type=checkbox] { accent-color: #7c3aed; width: 14px; height: 14px; }
.filter-toggle { display: flex; align-items: center; justify-content: space-between; font-size: 0.84rem; color: #374151; }
.filter-toggle input[type=checkbox] { accent-color: #7c3aed; width: 15px; height: 15px; cursor: pointer; }
.btn-apply { width: 100%; padding: 10px; background: #7c3aed; color: #fff; border: none; border-radius: 9px; font-size: 0.88rem; font-weight: 600; cursor: pointer; transition: background .2s; }
.btn-apply:hover { background: #6d28d9; }
.btn-reset { display: block; text-align: center; margin-top: 8px; font-size: 0.8rem; color: #9ca3af; text-decoration: none; }
.btn-reset:hover { color: #7c3aed; }

/* ── Saved searches ── */
.saved-search-item {
    display: flex; align-items: center; justify-content: space-between; gap: 8px;
    padding: 8px 10px; background: #f9f7ff; border-radius: 8px;
    margin-bottom: 6px; font-size: 0.82rem;
}
.saved-search-item a.run { color: #7c3aed; font-weight: 600; text-decoration: none; flex: 1; }
.saved-search-item a.run:hover { text-decoration: underline; }
.saved-search-item button { background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.8rem; padding: 0 4px; }

/* ── Top bar ── */
.search-topbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 18px; }
.result-count { font-size: 0.9rem; color: #6b7280; }
.result-count strong { color: #1a1033; }
.sort-select { padding: 8px 12px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 0.84rem; color: #374151; background: #fff; cursor: pointer; outline: none; }
.sort-select:focus { border-color: #7c3aed; }

/* ── Active filter chips ── */
.active-filters { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
.filter-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px; font-size: 0.76rem; font-weight: 600;
    background: #f3e8ff; color: #7c3aed; border: 1px solid #e9d5ff;
}
.filter-chip a { color: #9ca3af; text-decoration: none; font-size: 0.8rem; margin-left: 2px; }
.filter-chip a:hover { color: #ef4444; }

/* ── Result cards ── */
.results-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 18px; }

.member-card {
    background: #fff; border-radius: 14px; overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,.06); border: 1.5px solid transparent;
    transition: transform .2s, box-shadow .2s, border-color .2s;
}
.member-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(124,58,237,.1); border-color: rgba(124,58,237,.18); }
.member-photo-wrap { height: 200px; background: #f3e8ff; overflow: hidden; position: relative; }
.member-photo { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .3s; }
.member-card:hover .member-photo { transform: scale(1.04); }
.member-photo-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 4rem; color: #c4b5fd; }
.member-premium-badge {
    position: absolute; top: 9px; right: 9px;
    background: linear-gradient(135deg, #d4a017, #f59e0b);
    color: #1a1033; font-size: 0.62rem; font-weight: 700;
    padding: 2px 7px; border-radius: 20px;
}
.member-body { padding: 13px; }
.member-name { font-size: 0.97rem; font-weight: 700; color: #1a1033; margin: 0 0 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.member-sub { font-size: 0.77rem; color: #7c3aed; font-weight: 600; margin-bottom: 8px; }
.member-meta { display: flex; flex-direction: column; gap: 3px; margin-bottom: 11px; }
.member-meta-item { font-size: 0.77rem; color: #6b7280; display: flex; align-items: center; gap: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.member-actions { display: flex; gap: 7px; }
.m-btn {
    flex: 1; padding: 7px 4px; border-radius: 8px; font-size: 0.76rem; font-weight: 600;
    border: none; cursor: pointer; transition: all .2s;
    display: flex; align-items: center; justify-content: center; gap: 4px; text-decoration: none;
}
.m-btn.primary { background: #7c3aed; color: #fff; }
.m-btn.primary:hover { background: #6d28d9; }
.m-btn.primary.sent { background: #d8b4fe; color: #6d28d9; cursor: default; }
.m-btn.outline { background: transparent; color: #7c3aed; border: 1.5px solid #7c3aed; }
.m-btn.outline:hover { background: #f3e8ff; }
.m-btn.outline.shortlisted { background: #f3e8ff; }
.m-btn.ghost { background: transparent; color: #9ca3af; border: 1.5px solid #e5e7eb; }
.m-btn.ghost:hover { color: #374151; border-color: #9ca3af; }

/* ── Empty / prompt state ── */
.search-prompt { text-align: center; padding: 60px 24px; background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
.search-prompt .icon { font-size: 3.5rem; margin-bottom: 14px; }
.search-prompt h3 { font-size: 1.15rem; color: #1a1033; margin: 0 0 8px; }
.search-prompt p { font-size: 0.88rem; color: #6b7280; margin: 0; }

/* ── Save search form ── */
.save-search-bar { display: flex; gap: 8px; margin-top: 12px; }
.save-search-bar input { flex: 1; padding: 8px 12px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 0.84rem; outline: none; }
.save-search-bar input:focus { border-color: #7c3aed; }
.save-search-bar button { padding: 8px 14px; background: #7c3aed; color: #fff; border: none; border-radius: 8px; font-size: 0.82rem; font-weight: 600; cursor: pointer; }

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

<div class="search-page">
<div class="container section-sm">

    @foreach(['success','error','info'] as $t)
        @if(session($t))
            <div class="alert alert-{{ $t }}" style="margin-bottom:14px;">{{ session($t) }}</div>
        @endif
    @endforeach

    <div class="search-layout">

        {{-- ══════════════════ SIDEBAR ══════════════════ --}}
        <aside class="search-sidebar">

            {{-- Saved searches --}}
            @if($savedSearches->isNotEmpty())
            <div class="filter-card">
                <h3 class="filter-card-title">
                    <svg width="15" height="15" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg>
                    Saved Searches
                </h3>
                @foreach($savedSearches as $ss)
                    <div class="saved-search-item">
                        <a class="run" href="{{ route('user.search.index', $ss->filters ?? []) }}">
                            📋 {{ $ss->name }}
                        </a>
                        <form method="POST" action="{{ route('user.search.saved.delete', $ss) }}" style="margin:0;">
                            @csrf @method('DELETE')
                            <button type="submit" title="Delete">✕</button>
                        </form>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- Filter form --}}
            <form method="GET" action="{{ route('user.search.index') }}" id="searchForm">

                {{-- Keyword --}}
                <div class="filter-card">
                    <h3 class="filter-card-title">
                        <svg width="15" height="15" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                        Keyword Search
                    </h3>
                    <input type="text" name="keyword" class="filter-input"
                           value="{{ $f['keyword'] ?? '' }}"
                           placeholder="Name, Profile ID…">
                </div>

                {{-- Basic Criteria --}}
                <div class="filter-card">
                    <h3 class="filter-card-title">
                        <svg width="15" height="15" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M6 12h12M9 18h6"/></svg>
                        Basic Criteria
                    </h3>

                    <div class="filter-section">
                        <span class="filter-label">Age Range</span>
                        <div class="filter-range">
                            <input type="number" name="age_min" class="filter-input" value="{{ $f['age_min'] ?? 18 }}" min="18" max="80" placeholder="Min">
                            <input type="number" name="age_max" class="filter-input" value="{{ $f['age_max'] ?? 70 }}" min="18" max="80" placeholder="Max">
                        </div>
                    </div>

                    <div class="filter-section">
                        <span class="filter-label">Height (cm)</span>
                        <div class="filter-range">
                            <input type="number" name="height_min" class="filter-input" value="{{ $f['height_min'] ?? '' }}" placeholder="Min" min="120" max="220">
                            <input type="number" name="height_max" class="filter-input" value="{{ $f['height_max'] ?? '' }}" placeholder="Max" min="120" max="220">
                        </div>
                    </div>

                    <div class="filter-section">
                        <span class="filter-label">Marital Status</span>
                        <div class="filter-checkbox-group">
                            @foreach(['never_married','divorced','widowed','separated'] as $ms)
                                <label>
                                    <input type="checkbox" name="marital_status[]" value="{{ $ms }}"
                                        {{ in_array($ms, (array)($f['marital_status'] ?? [])) ? 'checked' : '' }}>
                                    {{ $fmt($ms) }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-section">
                        <div class="filter-toggle">
                            <span>With Photo Only</span>
                            <input type="checkbox" name="with_photo" value="1"
                                   {{ !empty($f['with_photo']) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                {{-- Religion & Community --}}
                <div class="filter-card">
                    <h3 class="filter-card-title">🛕 Religion</h3>

                    <div class="filter-section">
                        <span class="filter-label">Religion</span>
                        <select name="religion_id" class="filter-select"
                                hx-get="{{ route('user.ajax.castes_by_religion', '__ID__') }}"
                                onchange="loadCastes(this.value)">
                            <option value="">Any</option>
                            @foreach($religions as $r)
                                <option value="{{ $r->id }}" {{ ($f['religion_id'] ?? '') == $r->id ? 'selected' : '' }}>
                                    {{ $r->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-section">
                        <span class="filter-label">Caste</span>
                        <select name="caste_id" class="filter-select" id="casteSelect">
                            <option value="">Any</option>
                            @foreach($castes as $c)
                                <option value="{{ $c->id }}" {{ ($f['caste_id'] ?? '') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-section">
                        <span class="filter-label">Manglik</span>
                        <select name="manglik_status" class="filter-select">
                            <option value="">Any</option>
                            @foreach(['manglik','non_manglik','anshik_manglik'] as $mg)
                                <option value="{{ $mg }}" {{ ($f['manglik_status'] ?? '') === $mg ? 'selected' : '' }}>
                                    {{ $fmt($mg) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Education & Career --}}
                <div class="filter-card">
                    <h3 class="filter-card-title">🎓 Education & Career</h3>

                    <div class="filter-section">
                        <span class="filter-label">Education</span>
                        <select name="education_level_id" class="filter-select">
                            <option value="">Any</option>
                            @foreach($educationLevels as $el)
                                <option value="{{ $el->id }}" {{ ($f['education_level_id'] ?? '') == $el->id ? 'selected' : '' }}>
                                    {{ $el->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-section">
                        <span class="filter-label">Profession</span>
                        <select name="profession_id" class="filter-select">
                            <option value="">Any</option>
                            @foreach($professions as $p)
                                <option value="{{ $p->id }}" {{ ($f['profession_id'] ?? '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-section">
                        <span class="filter-label">Annual Income</span>
                        <select name="annual_income_range_id" class="filter-select">
                            <option value="">Any</option>
                            @foreach($incomeRanges as $ir)
                                <option value="{{ $ir->id }}" {{ ($f['annual_income_range_id'] ?? '') == $ir->id ? 'selected' : '' }}>
                                    {{ $ir->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Location --}}
                <div class="filter-card">
                    <h3 class="filter-card-title">📍 Location</h3>

                    <div class="filter-section">
                        <span class="filter-label">Country</span>
                        <select name="country_id" class="filter-select" onchange="loadStates(this.value)">
                            <option value="">Any</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}" {{ ($f['country_id'] ?? '') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-section">
                        <span class="filter-label">State</span>
                        <select name="state_id" class="filter-select" id="stateSelect" onchange="loadCities(this.value)">
                            <option value="">Any</option>
                            @foreach($states as $s)
                                <option value="{{ $s->id }}" {{ ($f['state_id'] ?? '') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-section">
                        <span class="filter-label">City</span>
                        <select name="city_id" class="filter-select" id="citySelect">
                            <option value="">Any</option>
                            @foreach($cities as $c)
                                <option value="{{ $c->id }}" {{ ($f['city_id'] ?? '') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Lifestyle --}}
                <div class="filter-card">
                    <h3 class="filter-card-title">🌿 Lifestyle</h3>

                    <div class="filter-section">
                        <span class="filter-label">Diet</span>
                        <select name="diet" class="filter-select">
                            <option value="">Any</option>
                            @foreach(['vegetarian','non_vegetarian','vegan','eggetarian','jain'] as $d)
                                <option value="{{ $d }}" {{ ($f['diet'] ?? '') === $d ? 'selected' : '' }}>{{ $fmt($d) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-section">
                        <span class="filter-label">Body Type</span>
                        <select name="body_type" class="filter-select">
                            <option value="">Any</option>
                            @foreach(['slim','athletic','average','heavy'] as $bt)
                                <option value="{{ $bt }}" {{ ($f['body_type'] ?? '') === $bt ? 'selected' : '' }}>{{ $fmt($bt) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-section">
                        <span class="filter-label">Complexion</span>
                        <select name="complexion" class="filter-select">
                            <option value="">Any</option>
                            @foreach(['fair','wheatish','dark'] as $cx)
                                <option value="{{ $cx }}" {{ ($f['complexion'] ?? '') === $cx ? 'selected' : '' }}>{{ $fmt($cx) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-apply">🔍 Search</button>
                <a href="{{ route('user.search.index') }}" class="btn-reset">Reset All Filters</a>

            </form>
        </aside>

        {{-- ══════════════════ RESULTS ══════════════════ --}}
        <div>

            @if($results === null)
                {{-- No search run yet --}}
                <div class="search-prompt">
                    <div class="icon">🔍</div>
                    <h3>Set your filters and search</h3>
                    <p>Use the filters on the left to find profiles matching exactly what you're looking for.</p>
                </div>

            @elseif($results->isEmpty())
                <div class="search-prompt">
                    <div class="icon">😔</div>
                    <h3>No profiles found</h3>
                    <p>Try broadening your filters — fewer criteria usually returns more matches.</p>
                </div>

            @else

                {{-- Active filter chips --}}
                @if(!empty($f))
                <div class="active-filters">
                    @if(!empty($f['keyword']))
                        <span class="filter-chip">🔤 {{ $f['keyword'] }}<a href="{{ request()->fullUrlWithoutQuery('keyword') }}">✕</a></span>
                    @endif
                    @if(!empty($f['religion_id']))
                        <span class="filter-chip">🛕 Religion set<a href="{{ request()->fullUrlWithoutQuery('religion_id') }}">✕</a></span>
                    @endif
                    @if(!empty($f['city_id']) || !empty($f['state_id']) || !empty($f['country_id']))
                        <span class="filter-chip">📍 Location set</span>
                    @endif
                    @if(!empty($f['education_level_id']))
                        <span class="filter-chip">🎓 Education set</span>
                    @endif
                    @if(!empty($f['with_photo']))
                        <span class="filter-chip">📷 With Photo</span>
                    @endif
                </div>
                @endif

                {{-- Top bar --}}
                <div class="search-topbar">
                    <p class="result-count">
                        <strong>{{ number_format($totalCount) }}</strong> profile{{ $totalCount !== 1 ? 's' : '' }} found
                        @if($results->total() > $results->count())
                            &nbsp;·&nbsp; showing {{ $results->firstItem() }}–{{ $results->lastItem() }}
                        @endif
                    </p>
                    <div style="display:flex;gap:10px;align-items:center;">
                        <form method="GET" action="{{ route('user.search.index') }}" id="sortForm">
                            @foreach($f as $key => $val)
                                @if($key !== 'sort')
                                    @if(is_array($val))
                                        @foreach($val as $v)<input type="hidden" name="{{ $key }}[]" value="{{ $v }}">@endforeach
                                    @else
                                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                                    @endif
                                @endif
                            @endforeach
                            <select name="sort" class="sort-select" onchange="this.form.submit()">
                                <option value="last_active" {{ ($f['sort'] ?? 'last_active') === 'last_active' ? 'selected' : '' }}>Last Active</option>
                                <option value="newest"      {{ ($f['sort'] ?? '') === 'newest'      ? 'selected' : '' }}>Newest</option>
                                <option value="age_asc"     {{ ($f['sort'] ?? '') === 'age_asc'     ? 'selected' : '' }}>Age ↑</option>
                                <option value="age_desc"    {{ ($f['sort'] ?? '') === 'age_desc'    ? 'selected' : '' }}>Age ↓</option>
                                <option value="completion"  {{ ($f['sort'] ?? '') === 'completion'  ? 'selected' : '' }}>Profile %</option>
                            </select>
                        </form>
                    </div>
                </div>

                {{-- Results grid --}}
                <div class="results-grid">
                    @foreach($results as $member)
                        @php
                            $mp    = $member->profile;
                            $photo = $member->primaryPhoto?->url;
                            $name  = $mp?->first_name
                                        ? ($mp->first_name . ' ' . substr($mp->last_name ?? '', 0, 1) . '.')
                                        : $member->name;
                            $age   = $member->date_of_birth?->age;
                            $loc   = collect([$mp?->city?->name, $mp?->state?->name])->filter()->implode(', ');
                        @endphp
                        <div class="member-card">
                            <div class="member-photo-wrap">
                                @if($photo)
                                    <img src="{{ $photo }}" alt="{{ $name }}" class="member-photo"
                                         onerror="this.parentElement.innerHTML='<div class=\'member-photo-placeholder\'>👤</div>'">
                                @else
                                    <div class="member-photo-placeholder">👤</div>
                                @endif
                                @if($member->is_premium)
                                    <span class="member-premium-badge">⭐ Premium</span>
                                @endif
                            </div>
                            <div class="member-body">
                                <h3 class="member-name" title="{{ $name }}">{{ $name }}</h3>
                                <p class="member-sub">
                                    {{ $age ? $age . ' yrs' : '' }}
                                    @if($member->profile_slug) · <span style="color:#9ca3af;font-weight:400;">{{ $member->profile_slug }}</span> @endif
                                </p>
                                <div class="member-meta">
                                    @if($loc)<span class="member-meta-item">📍 {{ $loc }}</span>@endif
                                    @if($mp?->religion?->name)<span class="member-meta-item">🛕 {{ $mp->religion->name }}</span>@endif
                                    @if($mp?->educationLevel?->name)<span class="member-meta-item">🎓 {{ $mp->educationLevel->name }}</span>@endif
                                </div>
                                <div class="member-actions">
                                    @if($member->interest_sent)
                                        <button class="m-btn primary sent" disabled>✓ Sent</button>
                                    @else
                                        <form method="POST" action="{{ route('user.interests.send', $member) }}" style="flex:1;">
                                            @csrf
                                            <button class="m-btn primary" style="width:100%;">💌 Interest</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('user.profile.public', $member->profile_slug ?? $member->id) }}" class="m-btn ghost" title="View">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('user.shortlist.toggle', $member) }}" style="display:contents;">
                                        @csrf
                                        <button class="m-btn outline {{ $member->is_shortlisted ? 'shortlisted' : '' }}" title="Shortlist">
                                            {{ $member->is_shortlisted ? '★' : '☆' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($results->hasPages())
                    <div class="pagination-wrap">{{ $results->links() }}</div>
                @endif

                {{-- Save search bar (shown after results) --}}
                <div style="margin-top:24px;background:#fff;border-radius:12px;padding:16px 20px;box-shadow:0 2px 10px rgba(0,0,0,.06);">
                    <p style="font-size:0.84rem;color:#6b7280;margin:0 0 8px;">💾 Save this search to rerun it later:</p>
                    <form method="POST" action="{{ route('user.search.save') }}" class="save-search-bar">
                        @csrf
                        @foreach($f as $key => $val)
                            @if(is_array($val))
                                @foreach($val as $v)<input type="hidden" name="{{ $key }}[]" value="{{ $v }}">@endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                            @endif
                        @endforeach
                        <input type="text" name="search_name" placeholder="Name this search…" required>
                        <button type="submit">Save</button>
                    </form>
                </div>

            @endif

        </div>{{-- /results --}}
    </div>{{-- /layout --}}
</div>{{-- /container --}}
</div>{{-- /page --}}

<script>
// ── Cascading dropdowns (reuse existing AJAX routes) ────────────────────
async function loadCastes(religionId) {
    const sel = document.getElementById('casteSelect');
    sel.innerHTML = '<option value="">Any</option>';
    if (!religionId) return;
    const res  = await fetch(`{{ url('user/ajax/castes-by-religion') }}/${religionId}`);
    const data = await res.json();
    (data.castes || data).forEach(c => {
        sel.insertAdjacentHTML('beforeend', `<option value="${c.id}">${c.name}</option>`);
    });
}

async function loadStates(countryId) {
    const sel = document.getElementById('stateSelect');
    sel.innerHTML = '<option value="">Any</option>';
    document.getElementById('citySelect').innerHTML = '<option value="">Any</option>';
    if (!countryId) return;
    const res  = await fetch(`{{ url('user/ajax/states-by-country') }}/${countryId}`);
    const data = await res.json();
    (data.states || data).forEach(s => {
        sel.insertAdjacentHTML('beforeend', `<option value="${s.id}">${s.name}</option>`);
    });
}

async function loadCities(stateId) {
    const sel = document.getElementById('citySelect');
    sel.innerHTML = '<option value="">Any</option>';
    if (!stateId) return;
    const res  = await fetch(`{{ url('user/ajax/cities-by-state') }}/${stateId}`);
    const data = await res.json();
    (data.cities || data).forEach(c => {
        sel.insertAdjacentHTML('beforeend', `<option value="${c.id}">${c.name}</option>`);
    });
}
</script>

@endsection