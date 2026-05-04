@extends('user.layouts.app')

@section('title', 'Interests Received — ' . config('app.name'))

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
            <span>Interests Received</span>
        </div>
        <h1>Interests Received 📬</h1>
        <p>{{ $counts['pending'] }} new interest{{ $counts['pending'] !== 1 ? 's' : '' }} waiting for your response</p>
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
    text-decoration: none; transition: all .2s; white-space: nowrap;
}
.int-tab:hover { color: #7c3aed; }
.int-tab.active { color: #7c3aed; border-bottom-color: #7c3aed; }
.int-tab .badge {
    font-size: 0.7rem; font-weight: 700; padding: 2px 7px; border-radius: 20px;
    background: #f3e8ff; color: #7c3aed;
}
.int-tab .badge.alert { background: #ef4444; color: #fff; }
.int-tab.active .badge { background: #7c3aed; color: #fff; }

/* ── Card ── */
.int-card {
    background: #fff; border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    border: 1.5px solid transparent; overflow: hidden;
    margin-bottom: 16px; transition: transform .2s, box-shadow .2s;
}
.int-card.pending-card { border-left: 4px solid #f59e0b; }
.int-card:hover { transform: translateY(-1px); box-shadow: 0 6px 22px rgba(124,58,237,.1); }

.int-card-inner { display: flex; gap: 0; align-items: stretch; }

/* Left: photo column */
.int-photo-col { width: 110px; flex-shrink: 0; position: relative; }
.int-photo-col img, .int-photo-placeholder {
    width: 110px; height: 100%; object-fit: cover; display: block; min-height: 130px;
}
.int-photo-placeholder {
    background: #f3e8ff; display: flex; align-items: center; justify-content: center;
    font-size: 2.8rem;
}

/* Center: info */
.int-info-col { flex: 1; padding: 16px 18px; min-width: 0; }
.int-name { font-size: 1.05rem; font-weight: 700; color: #1a1033; margin: 0 0 3px; }
.int-age-id { font-size: 0.8rem; color: #7c3aed; font-weight: 600; margin-bottom: 8px; }
.int-meta-row { display: flex; flex-wrap: wrap; gap: 8px 16px; margin-bottom: 10px; }
.int-meta-item { font-size: 0.8rem; color: #6b7280; display: flex; align-items: center; gap: 5px; }
.int-message-snippet {
    font-size: 0.82rem; color: #374151; font-style: italic;
    background: #faf7ff; border-radius: 8px; padding: 8px 12px;
    border-left: 3px solid #e9d5ff; margin-top: 8px; line-height: 1.5;
}
.int-message-snippet::before { content: '"'; color: #7c3aed; }
.int-message-snippet::after  { content: '"'; color: #7c3aed; }

/* Right: actions column */
.int-action-col {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 10px; padding: 16px; border-left: 1px solid #f0edf8; min-width: 130px;
}
.int-status-pill {
    padding: 4px 12px; border-radius: 20px; font-size: 0.72rem; font-weight: 700;
    letter-spacing: .03em; text-align: center;
}
.int-act-btn {
    width: 100%; padding: 9px 10px; border-radius: 9px; font-size: 0.82rem; font-weight: 600;
    border: none; cursor: pointer; transition: all .2s; text-align: center;
    display: flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none;
}
.int-act-btn.accept  { background: #dcfce7; color: #166534; }
.int-act-btn.accept:hover { background: #bbf7d0; }
.int-act-btn.decline { background: #fee2e2; color: #991b1b; }
.int-act-btn.decline:hover { background: #fecaca; }
.int-act-btn.view    { background: #f3e8ff; color: #7c3aed; }
.int-act-btn.view:hover { background: #e9d5ff; }
.int-act-btn.msg     { background: #7c3aed; color: #fff; }
.int-act-btn.msg:hover { background: #6d28d9; }
.int-date-tiny { font-size: 0.7rem; color: #9ca3af; text-align: center; }

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

@media (max-width: 640px) {
    .int-card-inner { flex-direction: column; }
    .int-photo-col { width: 100%; height: 160px; }
    .int-photo-col img, .int-photo-placeholder { width: 100%; height: 160px; }
    .int-action-col { flex-direction: row; flex-wrap: wrap; border-left: none; border-top: 1px solid #f0edf8; min-width: unset; }
    .int-act-btn { width: auto; flex: 1; }
}
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
    <div style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;">
        <a href="{{ route('user.interests.sent') }}" class="btn btn-outline btn-sm">💌 Sent</a>
        <a href="{{ route('user.interests.received') }}" class="btn btn-primary btn-sm" style="background:#7c3aed;">
            📬 Received ({{ $counts['all'] }})
        </a>
        <a href="{{ route('user.matches.index') }}" class="btn btn-outline btn-sm">💕 Browse Matches</a>
    </div>

    {{-- Status Tabs --}}
    <div class="int-tabs">
        @foreach(['all' => 'All', 'pending' => 'Pending', 'accepted' => 'Accepted', 'declined' => 'Declined'] as $key => $label)
            <a href="{{ route('user.interests.received', ['status' => $key]) }}"
               class="int-tab {{ $statusFilter === $key ? 'active' : '' }}">
                {{ $label }}
                <span class="badge {{ $key === 'pending' && $counts['pending'] > 0 && $statusFilter !== 'pending' ? 'alert' : '' }}">
                    {{ $counts[$key] ?? 0 }}
                </span>
            </a>
        @endforeach
    </div>

    @if($interests->isEmpty())
        <div class="empty-state">
            <div class="icon">📬</div>
            <h3>No interests {{ $statusFilter !== 'all' ? "that are $statusFilter" : 'received yet' }}</h3>
            <p>
                @if($statusFilter === 'all')
                    Complete your profile to attract more interest from potential matches.
                @else
                    No {{ $statusFilter }} interests at the moment.
                @endif
            </p>
            <a href="{{ route('user.profile.me') }}" class="btn btn-primary">Complete Profile</a>
        </div>
    @else
        @foreach($interests as $interest)
            @php
                $sender = $interest->sender;
                $mp     = $sender?->profile;
                $photo  = $sender?->primaryPhoto?->url;
                $name   = $mp?->first_name
                            ? ($mp->first_name . ' ' . substr($mp->last_name ?? '', 0, 1) . '.')
                            : ($sender?->name ?? 'Unknown');
                $age    = $sender?->date_of_birth?->age;
                $sc     = $statusConfig[$interest->status] ?? ['label'=>ucfirst($interest->status),'color'=>'#6b7280','bg'=>'#f3f4f6','icon'=>''];

                $locationParts = array_filter([$mp?->city?->name, $mp?->state?->name]);
                $location = implode(', ', $locationParts);
            @endphp

            <div class="int-card {{ $interest->status === 'pending' ? 'pending-card' : '' }}">
                <div class="int-card-inner">

                    {{-- Photo --}}
                    <div class="int-photo-col">
                        @if($photo)
                            <img src="{{ $photo }}" alt="{{ $name }}"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="int-photo-placeholder" style="display:none;">👤</div>
                        @else
                            <div class="int-photo-placeholder">👤</div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="int-info-col">
                        <h3 class="int-name">{{ $name }}</h3>
                        <p class="int-age-id">
                            {{ $age ? $age . ' yrs' : '' }}
                            @if($sender?->profile_slug)
                                &nbsp;·&nbsp;<span style="color:#9ca3af;font-weight:400;">{{ $sender->profile_slug }}</span>
                            @endif
                        </p>

                        <div class="int-meta-row">
                            @if($location)
                                <span class="int-meta-item">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 21s-8-7.33-8-13a8 8 0 0116 0c0 5.67-8 13-8 13z"/><circle cx="12" cy="8" r="2"/></svg>
                                    {{ $location }}
                                </span>
                            @endif
                            @if($mp?->religion?->name)
                                <span class="int-meta-item">🙏 {{ $mp->religion->name }}</span>
                            @endif
                            @if($mp?->educationLevel?->name)
                                <span class="int-meta-item">🎓 {{ $mp->educationLevel->name }}</span>
                            @endif
                            @if($mp?->profession?->name)
                                <span class="int-meta-item">💼 {{ $mp->profession->name }}</span>
                            @endif
                            @if($mp?->marital_status)
                                <span class="int-meta-item">💍 {{ $fmt($mp->marital_status) }}</span>
                            @endif
                        </div>

                        @if($interest->message)
                            <div class="int-message-snippet">{{ Str::limit($interest->message, 120) }}</div>
                        @endif

                        <p style="font-size:0.75rem;color:#9ca3af;margin:10px 0 0;">
                            Received {{ $interest->created_at->diffForHumans() }}
                            @if($interest->responded_at)
                                &nbsp;·&nbsp; You responded {{ $interest->responded_at->diffForHumans() }}
                            @endif
                        </p>
                    </div>

                    {{-- Actions column --}}
                    <div class="int-action-col">

                        {{-- Status pill (non-pending) --}}
                        @if($interest->status !== 'pending')
                            <span class="int-status-pill" style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};">
                                {{ $sc['icon'] }} {{ $sc['label'] }}
                            </span>
                        @endif

                        {{-- Accept / Decline buttons for pending --}}
                        @if($interest->status === 'pending')
                            <form method="POST" action="{{ route('user.interests.accept', $interest) }}" style="width:100%;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="int-act-btn accept">✅ Accept</button>
                            </form>
                            <form method="POST" action="{{ route('user.interests.decline', $interest) }}" style="width:100%;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="int-act-btn decline">✕ Decline</button>
                            </form>
                        @endif

                        {{-- View Profile --}}
                        <a href="{{ route('user.profile.public', $sender?->profile_slug ?? $sender?->id) }}"
                           class="int-act-btn view">
                           <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                           Profile
                        </a>

                        {{-- Message if accepted --}}
                        @if($interest->status === 'accepted')
                            <a href="{{ route('user.messages.index') }}" class="int-act-btn msg">
                                💬 Message
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach

        @if($interests->hasPages())
            <div class="pagination-wrap">{{ $interests->links() }}</div>
        @endif
    @endif

</div>
</div>

@endsection