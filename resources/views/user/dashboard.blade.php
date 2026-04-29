@extends('user.layouts.app')

@section('title', 'Dashboard — ' . config('app.name'))

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════════════
    PAGE HERO
    $user->name  →  User model has 'name' (combined). first_name is in profile.
════════════════════════════════════════════════════════════════════════════ --}}
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Dashboard</span>
        </div>
        <h1>
            Welcome,
            {{ $user->profile?->first_name ?? $user->name }} 👋
        </h1>
        <p>Manage your profile, connections &amp; activity</p>
    </div>
</section>


<div class="container section-sm">

    {{-- ─── FLASH MESSAGES ──────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:20px;">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info" style="margin-bottom:20px;">
            ℹ️ {{ session('info') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" style="margin-bottom:20px;">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- ─── SITE NOTICE (from Settings table via controller) ───────────────
        $siteNotice is pulled by DashboardController via Setting::get('site_notice')
    ──────────────────────────────────────────────────────────────────────── --}}
    @if($siteNotice)
        <div class="card" style="padding:14px 20px;margin-bottom:24px;border-left:4px solid var(--primary);display:flex;align-items:center;gap:12px;">
            <span style="font-size:1.2rem;">📢</span>
            <p style="margin:0;font-size:0.95rem;">{{ $siteNotice }}</p>
        </div>
    @endif

    {{-- ─── PROFILE INCOMPLETE BANNER ──────────────────────────────────────
        $completionSteps → from getMissingProfileSteps() in DashboardController
        Each step: ['label' => '...', 'route' => '...', 'step' => N]
        Route names updated to use new wizard route: user.profile.setup.show
    ──────────────────────────────────────────────────────────────────────── --}}
    @if($stats['profile_completion'] < 100)
        <div class="card" style="padding:24px;margin-bottom:24px;">

            {{-- Header row --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                <h3 style="margin:0;">Profile Completion</h3>
                <span style="font-size:0.9rem;color:{{ $stats['profile_completion'] >= 70 ? 'var(--success)' : 'var(--warning)' }};font-weight:600;">
                    {{ $stats['profile_completion'] }}%
                </span>
            </div>

            {{-- Progress bar --}}
            <div style="background:var(--bg-light);border-radius:10px;height:10px;overflow:hidden;">
                <div style="
                    width:{{ $stats['profile_completion'] }}%;
                    background:{{ $stats['profile_completion'] >= 70 ? 'var(--success, #22c55e)' : 'var(--primary)' }};
                    height:100%;
                    border-radius:10px;
                    transition:width 0.4s ease;
                "></div>
            </div>

            {{-- Incomplete step buttons --}}
            @if(count($completionSteps))
                <p style="margin:14px 0 10px;font-size:0.875rem;color:var(--text-muted);">
                    Complete these sections to improve your profile:
                </p>
                <div style="display:flex;flex-wrap:wrap;gap:8px;">
                    @foreach($completionSteps as $step)
                        <a href="{{ $step['route'] }}" class="btn btn-outline btn-sm">
                            {{ $step['label'] }}
                        </a>
                    @endforeach
                </div>
            @else
                <p style="margin-top:10px;font-size:0.875rem;color:var(--text-muted);">
                    ✅ Great! Your profile looks complete.
                </p>
            @endif

        </div>
    @endif


    {{-- ─── STATS GRID ──────────────────────────────────────────────────────
        All 4 stats come from DashboardController $stats array
        unread_interests, accepted_interests, profile_views_month, shortlisted_by_count
    ──────────────────────────────────────────────────────────────────────── --}}
    <div class="stats-grid" style="margin-bottom:32px;">

        <div class="card stat-item">
            <div class="stat-number">{{ $stats['unread_interests'] }}</div>
            <div class="stat-label">New Interests</div>
        </div>

        <div class="card stat-item">
            <div class="stat-number">{{ $stats['accepted_interests'] }}</div>
            <div class="stat-label">Connections</div>
        </div>

        <div class="card stat-item">
            <div class="stat-number">{{ $stats['profile_views_month'] }}</div>
            <div class="stat-label">Profile Views</div>
            <div style="font-size:0.75rem;color:var(--text-muted);margin-top:2px;">Last 30 days</div>
        </div>

        <div class="card stat-item">
            <div class="stat-number">{{ $stats['shortlisted_by_count'] }}</div>
            <div class="stat-label">Shortlisted By</div>
        </div>

    </div>


    {{-- ─── MAIN CONTENT GRID ──────────────────────────────────────────────
        2-column on desktop, stacked on mobile
    ──────────────────────────────────────────────────────────────────────── --}}
    <div class="dashboard-grid">

        {{-- ══ LEFT COLUMN ════════════════════════════════════════════════ --}}
        <div>

            {{-- USER IDENTITY CARD ─────────────────────────────────────────
                Photo: primaryPhoto() relationship → ProfilePhoto model
                  uses $photo->url accessor (asset('storage/'.$path))
                  Null-safe: ?->url with fallback to default asset
                Name: first_name + last_name live in UserProfile, not User
                  Use optional() to prevent errors if profile row missing
            ─────────────────────────────────────────────────────────────── --}}
            <div class="card" style="padding:24px;margin-bottom:24px;">
                <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">

                    {{-- PHOTO --}}
                    <div style="position:relative;flex-shrink:0;">
                        <img
                            src="{{ $user->primaryPhoto?->url ?? asset('assets/images/default-user.png') }}"
                            alt="{{ $user->profile?->full_name ?? $user->name }}"
                            style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);">

                        {{-- Premium badge --}}
                        @if($user->isPremiumActive())
                            <span style="position:absolute;bottom:0;right:0;background:var(--gold,#f59e0b);border-radius:50%;width:22px;height:22px;display:flex;align-items:center;justify-content:center;font-size:11px;border:2px solid #fff;" title="Premium Member">
                                💎
                            </span>
                        @endif
                    </div>

                    {{-- INFO --}}
                    <div style="flex:1;min-width:0;">
                        <h3 style="margin:0 0 4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $user->profile?->full_name ?? $user->name }}
                        </h3>

                        <p style="margin:0 0 4px;font-size:0.875rem;color:var(--text-muted);">
                            {{ $user->email }}
                        </p>

                        @if($user->profile?->city)
                            <p style="margin:0 0 10px;font-size:0.85rem;color:var(--text-muted);">
                                📍 {{ $user->profile->city->name }}{{ $user->profile->state ? ', ' . $user->profile->state->name : '' }}
                            </p>
                        @endif

                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                            <a href="{{ route('user.profile.me') }}" class="btn btn-outline btn-sm">
                                👤 View Profile
                            </a>
                            <a href="{{ route('user.profile.edit') }}" class="btn btn-primary btn-sm">
                                ✏️ Edit Profile
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            {{-- QUICK ACTIONS ───────────────────────────────────────────────
                Routes match exactly what's defined in user.php routes file
            ─────────────────────────────────────────────────────────────── --}}
            <div class="card" style="padding:24px;margin-bottom:24px;">
                <h3 style="margin:0 0 16px;">Quick Actions</h3>

                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;">

                    <a href="{{ route('user.search.index') }}" class="quick-action-btn">
                        <span style="font-size:1.4rem;">🔍</span>
                        <span>Search</span>
                    </a>

                    <a href="{{ route('user.matches.index') }}" class="quick-action-btn">
                        <span style="font-size:1.4rem;">💞</span>
                        <span>Matches</span>
                    </a>

                    <a href="{{ route('user.interests.received') }}" class="quick-action-btn">
                        <span style="font-size:1.4rem;">💌</span>
                        <span>
                            Interests
                            @if($stats['unread_interests'] > 0)
                                <span class="badge-count">{{ $stats['unread_interests'] }}</span>
                            @endif
                        </span>
                    </a>

                    <a href="{{ route('user.shortlist.index') }}" class="quick-action-btn">
                        <span style="font-size:1.4rem;">⭐</span>
                        <span>Shortlist</span>
                    </a>

                    <a href="{{ route('user.messages.index') }}" class="quick-action-btn">
                        <span style="font-size:1.4rem;">💬</span>
                        <span>Messages</span>
                    </a>

                    <a href="{{ route('user.subscription.show') }}" class="quick-action-btn" style="border-color:var(--gold,#f59e0b);color:var(--gold,#f59e0b);">
                        <span style="font-size:1.4rem;">💎</span>
                        <span>Upgrade</span>
                    </a>

                </div>
            </div>

            {{-- PROFILE SETUP PROGRESS ──────────────────────────────────────
                Only shown if profile_status is NOT 'complete'
                Uses wizard routes: user.profile.setup.show
            ─────────────────────────────────────────────────────────────── --}}
            @if($user->profile_status !== 'complete')
                <div class="card" style="padding:24px;">
                    <h3 style="margin:0 0 16px;">Complete Your Profile</h3>

                    <div style="display:flex;flex-direction:column;gap:10px;">
                        @php
                            $wizardSteps = [
                                1 => ['label' => 'Basic Information',     'icon' => '👤', 'field' => 'height_cm'],
                                2 => ['label' => 'Religion & Community',  'icon' => '🙏', 'field' => 'religion_id'],
                                3 => ['label' => 'Horoscope',             'icon' => '⭐', 'field' => 'rashi_id'],
                                4 => ['label' => 'Education & Career',    'icon' => '🎓', 'field' => 'education_level_id'],
                                5 => ['label' => 'Location & Family',     'icon' => '📍', 'field' => 'country_id'],
                                6 => ['label' => 'Partner Preferences',   'icon' => '💞', 'field' => null],
                                7 => ['label' => 'Photos & Privacy',      'icon' => '📷', 'field' => null],
                            ];
                        @endphp

                        @foreach($wizardSteps as $num => $wStep)
                            @php
                                $isDone = match($num) {
                                    1 => $user->profile?->height_cm,
                                    2 => $user->profile?->religion_id,
                                    3 => $user->profile?->rashi_id || $user->profile?->manglik_status,
                                    4 => $user->profile?->education_level_id,
                                    5 => $user->profile?->country_id,
                                    6 => $user->partnerPreference !== null,
                                    7 => $user->photos->isNotEmpty(),
                                    default => false,
                                };
                            @endphp

                            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;background:var(--bg-light);border-radius:8px;">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <span>{{ $wStep['icon'] }}</span>
                                    <span style="font-size:0.9rem;{{ $isDone ? 'color:var(--text-muted);text-decoration:line-through;' : '' }}">
                                        {{ $wStep['label'] }}
                                    </span>
                                </div>

                                @if($isDone)
                                    <span style="color:var(--success,#22c55e);font-size:0.85rem;font-weight:600;">✅ Done</span>
                                @else
                                    <a href="{{ route('user.profile.setup.show', $num) }}" class="btn btn-primary btn-sm" style="font-size:0.8rem;padding:4px 12px;">
                                        Fill Now
                                    </a>
                                @endif
                            </div>

                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- ══ RIGHT COLUMN ════════════════════════════════════════════════ --}}
        <div>

            {{-- SUBSCRIPTION CARD ──────────────────────────────────────────
                $stats['subscription'] → User->activeSubscription relationship
                  is_active=true AND expires_at > now()
                  plan_name, expires_at come from UserSubscription model
            ─────────────────────────────────────────────────────────────── --}}
            <div class="card" style="padding:24px;margin-bottom:24px;">
                <h3 style="margin:0 0 14px;">Your Plan</h3>

                @if($stats['subscription'])
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                        <span style="font-size:1.5rem;">💎</span>
                        <div>
                            <p style="margin:0;font-weight:600;">{{ $stats['subscription']->plan_name }}</p>
                            <p style="margin:0;font-size:0.82rem;color:var(--text-muted);">
                                Expires: {{ \Carbon\Carbon::parse($stats['subscription']->expires_at)->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('user.subscription.show') }}" class="btn btn-outline btn-sm">
                        View Details
                    </a>
                @else
                    <p style="margin:0 0 14px;font-size:0.9rem;color:var(--text-muted);">
                        You are on the <strong>Free Plan</strong>. Upgrade to unlock premium features.
                    </p>
                    <a href="{{ route('user.packages.index') }}" class="btn btn-gold btn-sm" style="width:100%;justify-content:center;">
                        💎 Upgrade Now
                    </a>
                @endif
            </div>

            {{-- PROFILE SNAPSHOT ────────────────────────────────────────────
                Quick stats about the profile — age, religion, location
                All from $user->profile (UserProfile model)
            ─────────────────────────────────────────────────────────────── --}}
            @if($user->profile)
                <div class="card" style="padding:24px;margin-bottom:24px;">
                    <h3 style="margin:0 0 14px;">Profile Snapshot</h3>

                    <ul style="list-style:none;padding:0;margin:0;font-size:0.9rem;display:flex;flex-direction:column;gap:8px;">

                        @if($user->age)
                            <li style="display:flex;justify-content:space-between;">
                                <span style="color:var(--text-muted);">Age</span>
                                <span>{{ $user->age }} years</span>
                            </li>
                        @endif

                        @if($user->profile->religion)
                            <li style="display:flex;justify-content:space-between;">
                                <span style="color:var(--text-muted);">Religion</span>
                                <span>{{ $user->profile->religion->name }}</span>
                            </li>
                        @endif

                        @if($user->profile->mother_tongue)
                            <li style="display:flex;justify-content:space-between;">
                                <span style="color:var(--text-muted);">Mother Tongue</span>
                                <span>{{ $user->profile->mother_tongue->name }}</span>
                            </li>
                        @endif

                        @if($user->profile->height_cm)
                            <li style="display:flex;justify-content:space-between;">
                                <span style="color:var(--text-muted);">Height</span>
                                <span>{{ $user->profile->height_feet }}</span>
                            </li>
                        @endif

                        @if($user->profile->education_level)
                            <li style="display:flex;justify-content:space-between;">
                                <span style="color:var(--text-muted);">Education</span>
                                <span>{{ $user->profile->education_level->name }}</span>
                            </li>
                        @endif

                        @if($user->profile->city)
                            <li style="display:flex;justify-content:space-between;">
                                <span style="color:var(--text-muted);">Location</span>
                                <span>{{ $user->profile->city->name }}</span>
                            </li>
                        @endif

                    </ul>

                </div>
            @endif

            {{-- RECENT ACTIVITY ─────────────────────────────────────────────
                Placeholder for Phase 7 (ActivityLog system)
                Wired up when activityLogs relationship is implemented
            ─────────────────────────────────────────────────────────────── --}}
            <div class="card" style="padding:24px;">
                <h3 style="margin:0 0 14px;">Recent Activity</h3>

                @php
                    $recentLogs = $user->activityLogs()
                                       ->latest()
                                       ->take(5)
                                       ->get();
                @endphp

                @if($recentLogs->isEmpty())
                    <p style="font-size:0.875rem;color:var(--text-muted);margin:0;">
                        Your recent actions will appear here.
                    </p>
                @else
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;">
                        @foreach($recentLogs as $log)
                            <li style="font-size:0.85rem;border-bottom:1px solid var(--border);padding-bottom:8px;">
                                <span>{{ $log->description }}</span>
                                <div style="color:var(--text-muted);font-size:0.78rem;margin-top:2px;">
                                    {{ $log->created_at->diffForHumans() }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif

            </div>

        </div>

    </div>
</div>


{{-- ─── DASHBOARD-SPECIFIC STYLES ───────────────────────────────────────── --}}
<style>
/* 2-col grid → stacks at 768px */
.dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    align-items: start;
}
@media (max-width: 768px) {
    .dashboard-grid { grid-template-columns: 1fr; }
}

/* Quick action tile */
.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 14px 10px;
    border: 1px solid var(--border);
    border-radius: 10px;
    text-decoration: none;
    color: var(--text);
    font-size: 0.85rem;
    font-weight: 500;
    text-align: center;
    transition: var(--transition);
}
.quick-action-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--bg-light);
    transform: translateY(-2px);
}

/* Unread badge on interests button */
.badge-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--primary);
    color: #fff;
    border-radius: 50%;
    font-size: 0.7rem;
    min-width: 18px;
    height: 18px;
    padding: 0 4px;
    margin-left: 4px;
    vertical-align: middle;
}

/* Flash alerts */
.alert {
    padding: 12px 18px;
    border-radius: 8px;
    font-size: 0.9rem;
}
.alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.alert-info    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.alert-error   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
</style>

@endsection