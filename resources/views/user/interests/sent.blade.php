@extends('user.layouts.app')

@section('title', 'Interests Sent — ' . config('app.name'))

@section('content')

@php
    $fmt = fn($v) => $v ? ucwords(str_replace('_', ' ', $v)) : '—';

    $statusConfig = [
        'pending'  => ['label' => 'Pending',  'color' => '#f59e0b', 'bg' => '#fef3c7', 'icon' => '⏳'],
        'accepted' => ['label' => 'Accepted', 'color' => '#22c55e', 'bg' => '#dcfce7', 'icon' => '✅'],
        'declined' => ['label' => 'Declined', 'color' => '#ef4444', 'bg' => '#fee2e2', 'icon' => '❌'],
    ];
@endphp

{{-- PAGE HERO --}}
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a><span>/</span>
            <a href="{{ route('user.dashboard') }}">Dashboard</a><span>/</span>
            <span>Interests Sent</span>
        </div>
        <h1>Interests Sent 💌</h1>
        <p>Track all the profiles you've shown interest in</p>
    </div>
</section>

<style>
.interests-page { background: var(--bg-light, #f7f4f0); min-height: 100vh; padding-bottom: 60px; }

/* ── Tabs ── */
.int-tabs { display: flex; gap: 0; border-bottom: 2px solid #e5e7eb; margin-bottom: 28px; overflow-x: auto; }
.int-tab {
    display: flex; align-items: center; gap: 8px;
    padding: 12px 20px; font-size: 0.88rem; font-weight: 600;
    color: #6b7280; border-bottom: 3px solid transparent; margin-bottom: -2px;
    text-decoration: none; transition: all .2s; white-space: nowrap; cursor: pointer;
}
.int-tab:hover { color: #7c3aed; }
.int-tab.active { color: #7c3aed; border-bottom-color: #7c3aed; }
.int-tab .badge {
    font-size: 0.7rem; font-weight: 700; padding: 2px 7px; border-radius: 20px;
    background: #f3e8ff; color: #7c3aed;
}
.int-tab.active .badge { background: #7c3aed; color: #fff; }

/* ── Cards grid ── */
.int-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 18px; }

/* ── Interest card ── */
.int-card {
    background: #fff; border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    border: 1.5px solid transparent;
    transition: transform .2s, box-shadow .2s;
    overflow: hidden;
    display: flex; flex-direction: column;
}
.int-card:hover { transform: translateY(-2px); box-shadow: 0 6px 22px rgba(124,58,237,.1); border-color: rgba(124,58,237,.15); }

/* Card header (photo + basic info row) */
.int-card-header { display: flex; gap: 14px; padding: 16px; align-items: flex-start; }
.int-avatar {
    width: 72px; height: 72px; border-radius: 50%; object-fit: cover;
    border: 2.5px solid #e9d5ff; flex-shrink: 0;
}
.int-avatar-placeholder {
    width: 72px; height: 72px; border-radius: 50%;
    background: #f3e8ff; display: flex; align-items: center; justify-content: center;
    font-size: 2rem; flex-shrink: 0;
}
.int-info { flex: 1; min-width: 0; }
.int-name { font-size: 1rem; font-weight: 700; color: #1a1033; margin: 0 0 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.int-age-id { font-size: 0.78rem; color: #7c3aed; font-weight: 600; margin-bottom: 6px; }
.int-meta { font-size: 0.78rem; color: #6b7280; display: flex; flex-direction: column; gap: 2px; }

/* Status pill */
.int-status {
    align-self: flex-start; padding: 4px 10px; border-radius: 20px;
    font-size: 0.72rem; font-weight: 700; letter-spacing: .03em; white-space: nowrap;
}

/* Message snippet */
.int-message {
    padding: 0 16px 12px; font-size: 0.82rem; color: #6b7280;
    font-style: italic; border-bottom: 1px solid #f0edf8;
    line-height: 1.5;
}
.int-message::before { content: '"'; }
.int-message::after  { content: '"'; }

/* Footer */
.int-card-footer { padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
.int-date { font-size: 0.75rem; color: #9ca3af; }
.int-actions { display: flex; gap: 8px; }
.int-btn {
    padding: 7px 14px; border-radius: 8px; font-size: 0.78rem; font-weight: 600;
    border: none; cursor: pointer; transition: all .2s; text-decoration: none; display:inline-flex;align-items:center;gap:5px;
}
.int-btn.view   { background: #f3e8ff; color: #7c3aed; }
.int-btn.view:hover { background: #e9d5ff; }
.int-btn.cancel { background: #fee2e2; color: #ef4444; }
.int-btn.cancel:hover { background: #fecaca; }
.int-btn.disabled { background: #f3f4f6; color: #9ca3af; cursor: default; }

/* ── Empty state ── */
.empty-state { text-align: center; padding: 60px 24px; }
.empty-state .icon { font-size: 3.5rem; margin-bottom: 16px; }
.empty-state h3 { font-size: 1.2rem; color: #1a1033; margin: 0 0 8px; }
.empty-state p { font-size: 0.9rem; color: #6b7280; margin: 0 0 20px; }

/* ── Pagination ── */
.pagination-wrap { margin-top: 32px; display: flex; justify-content: center; }
.pagination-wrap nav span, .pagination-wrap nav a {
    display: inline-flex; align-items: center; justify-content: center;
    padding: 8px 14px; margin: 0 2px; border-radius: 8px; font-size: 0.85rem;
    border: 1.5px solid #e5e7eb; color: #374151; text-decoration: none; transition: all .2s;
}
.pagination-wrap nav a:hover { background: #f3e8ff; border-color: #7c3aed; color: #7c3aed; }
.pagination-wrap nav span[aria-current] { background: #7c3aed; color: #fff; border-color: #7c3aed; }
</style>

<div class="interests-page">
<div class="container section-sm">

    {{-- Flash messages --}}
    @foreach(['success','error','info'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type }}" style="margin-bottom:16px;">{{ session($type) }}</div>
        @endif
    @endforeach

    {{-- Sub-nav: Sent / Received --}}
    <div style="display:flex;gap:12px;margin-bottom:24px;">
        <a href="{{ route('user.interests.sent') }}"
           class="btn btn-primary btn-sm"
           style="background:#7c3aed;">
           💌 Sent ({{ $counts['all'] }})
        </a>
        <a href="{{ route('user.interests.received') }}"
           class="btn btn-outline btn-sm">
           📬 Received
        </a>
        <a href="{{ route('user.matches.index') }}"
           class="btn btn-outline btn-sm">
           💕 Browse Matches
        </a>
    </div>

    {{-- Status Tabs --}}
    <div class="int-tabs">
        @foreach(['all' => 'All', 'pending' => 'Pending', 'accepted' => 'Accepted', 'declined' => 'Declined'] as $key => $label)
            <a href="{{ route('user.interests.sent', ['status' => $key]) }}"
               class="int-tab {{ $statusFilter === $key ? 'active' : '' }}">
                {{ $label }}
                <span class="badge">{{ $counts[$key] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    @if($interests->isEmpty())
        <div class="empty-state">
            <div class="icon">💌</div>
            <h3>No interests {{ $statusFilter !== 'all' ? "that are $statusFilter" : 'sent yet' }}</h3>
            <p>
                @if($statusFilter === 'all')
                    Start browsing matches and send your first interest!
                @else
                    No {{ $statusFilter }} interests to show right now.
                @endif
            </p>
            <a href="{{ route('user.matches.index') }}" class="btn btn-primary">Browse Matches</a>
        </div>
    @else
        <div class="int-grid">
            @foreach($interests as $interest)
                @php
                    $other  = $interest->receiver;
                    $mp     = $other?->profile;
                    $photo  = $other?->primaryPhoto?->url;
                    $name   = $mp?->first_name
                                ? ($mp->first_name . ' ' . substr($mp->last_name ?? '', 0, 1) . '.')
                                : ($other?->name ?? 'Unknown');
                    $age    = $other?->date_of_birth?->age;

                    $sc     = $statusConfig[$interest->status] ?? ['label'=>ucfirst($interest->status),'color'=>'#6b7280','bg'=>'#f3f4f6','icon'=>''];

                    $locationParts = array_filter([
                        $mp?->city?->name,
                        $mp?->state?->name,
                    ]);
                    $location = implode(', ', $locationParts);
                @endphp

                <div class="int-card">
                    <div class="int-card-header">
                        {{-- Avatar --}}
                        @if($photo)
                            <img src="{{ $photo }}" alt="{{ $name }}" class="int-avatar"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="int-avatar-placeholder" style="display:none;">👤</div>
                        @else
                            <div class="int-avatar-placeholder">👤</div>
                        @endif

                        {{-- Info --}}
                        <div class="int-info">
                            <h3 class="int-name">{{ $name }}</h3>
                            <p class="int-age-id">
                                {{ $age ? $age . ' yrs' : '' }}
                                @if($other?->profile_slug)
                                    &nbsp;·&nbsp;<span style="color:#9ca3af;font-weight:400;">{{ $other->profile_slug }}</span>
                                @endif
                            </p>
                            <div class="int-meta">
                                @if($location)
                                    <span>📍 {{ $location }}</span>
                                @endif
                                @if($mp?->religion?->name)
                                    <span>🙏 {{ $mp->religion->name }}</span>
                                @endif
                                @if($mp?->educationLevel?->name)
                                    <span>🎓 {{ $mp->educationLevel->name }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Status badge --}}
                        <span class="int-status"
                              style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};">
                            {{ $sc['icon'] }} {{ $sc['label'] }}
                        </span>
                    </div>

                    @if($interest->message)
                        <div class="int-message">{{ Str::limit($interest->message, 100) }}</div>
                    @endif

                    <div class="int-card-footer">
                        <span class="int-date">
                            Sent {{ $interest->created_at->diffForHumans() }}
                            @if($interest->responded_at)
                                &nbsp;·&nbsp; Responded {{ $interest->responded_at->diffForHumans() }}
                            @endif
                        </span>
                        <div class="int-actions">
                            {{-- View profile --}}
                            <a href="{{ route('user.profile.public', $other?->profile_slug ?? $other?->id) }}"
                               class="int-btn view">
                               <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                               View
                            </a>

                            {{-- Cancel — only for pending --}}
                            @if($interest->status === 'pending')
                                <form method="POST" action="{{ route('user.interests.cancel', $interest) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="int-btn cancel"
                                            onclick="return confirm('Cancel this interest?')">
                                        ✕ Cancel
                                    </button>
                                </form>
                            @else
                                <span class="int-btn disabled">
                                    {{ $interest->status === 'accepted' ? '💬 Message' : '—' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($interests->hasPages())
            <div class="pagination-wrap">{{ $interests->links() }}</div>
        @endif
    @endif

</div>
</div>

@endsection