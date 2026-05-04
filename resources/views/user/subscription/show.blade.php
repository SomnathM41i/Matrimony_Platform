@extends('user.layouts.app')

@section('title', 'My Subscription — ' . config('app.name'))

@section('content')

<section class="page-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a><span>/</span>
            <a href="{{ route('user.dashboard') }}">Dashboard</a><span>/</span>
            <span>My Subscription</span>
        </div>
        <h1>My Subscription</h1>
        <p>View your current plan, benefits, and payment history.</p>
    </div>
</section>

<style>
.sub-page { background: var(--bg-light, #f7f4f0); padding-bottom: 80px; }

/* ── Status card ─────────────────────────────────────────────────── */
.sub-status-card {
    border-radius: 20px;
    padding: 32px;
    margin-bottom: 28px;
    display: flex;
    align-items: flex-start;
    gap: 24px;
    flex-wrap: wrap;
}
.sub-status-card.active {
    background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
    color: #fff;
    box-shadow: 0 8px 32px rgba(124,58,237,.28);
}
.sub-status-card.expired {
    background: #fff;
    border: 2px solid #e5e7eb;
    color: var(--text, #1a1033);
}
.sub-status-card.free {
    background: #fff;
    border: 2px solid #e5e7eb;
    color: var(--text, #1a1033);
}
.sub-status-icon { font-size: 3rem; flex-shrink: 0; }
.sub-status-body { flex: 1; min-width: 200px; }
.sub-status-body h2 { margin: 0 0 6px; font-size: 1.4rem; }
.sub-status-body p  { margin: 0 0 6px; opacity: .88; font-size: 0.9rem; }
.sub-status-badge {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 50px;
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: 10px;
}
.badge-active  { background: rgba(255,255,255,.25); color: #fff; border: 1.5px solid rgba(255,255,255,.4); }
.badge-expired { background: #fef2f2; color: #ef4444; border: 1.5px solid #fca5a5; }
.badge-free    { background: #f0fdf4; color: #16a34a; border: 1.5px solid #86efac; }

/* ── Feature list in status card ──────────────────────────────────── */
.sub-features { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px; }
.sub-feat-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 50px;
    padding: 5px 12px;
    font-size: 0.78rem;
    font-weight: 600;
}
.sub-feat-pill.dark {
    background: #f5f3ff;
    border-color: #e9d5ff;
    color: #6d28d9;
}

/* ── Stats strip ─────────────────────────────────────────────────── */
.sub-stats {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}
.sub-stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 20px 18px;
    text-align: center;
    border: 1.5px solid #f3e8ff;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}
.sub-stat-num  { font-size: 1.8rem; font-weight: 800; color: var(--primary, #7c3aed); line-height: 1; }
.sub-stat-lbl  { font-size: 0.76rem; color: #6b7280; margin-top: 4px; }

/* ── Upgrade CTA card ────────────────────────────────────────────── */
.sub-upgrade-cta {
    background: #faf5ff;
    border: 1.5px solid #e9d5ff;
    border-radius: 16px;
    padding: 24px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 28px;
}

/* ── History table ───────────────────────────────────────────────── */
.sub-history-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
.sub-history-table th { background: #7c3aed; color: #fff; padding: 13px 16px; text-align: left; font-size: 0.82rem; font-weight: 700; }
.sub-history-table td { padding: 13px 16px; font-size: 0.85rem; border-bottom: 1px solid #f3e8ff; color: var(--text, #374151); vertical-align: middle; }
.sub-history-table tbody tr:last-child td { border-bottom: none; }
.sub-history-table tbody tr:hover td { background: #faf5ff; }
.status-pill {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 50px;
    font-size: 0.72rem;
    font-weight: 700;
}
.status-pill.completed { background: #dcfce7; color: #16a34a; }
.status-pill.pending   { background: #fef9c3; color: #ca8a04; }
.status-pill.failed    { background: #fee2e2; color: #dc2626; }
.status-pill.refunded  { background: #e0f2fe; color: #0284c7; }
</style>

<div class="sub-page">
<div class="container section">

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:20px;">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" style="margin-bottom:20px;">❌ {{ session('error') }}</div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
        CURRENT PLAN STATUS
    ══════════════════════════════════════════════════════════════ --}}
    @if($activeSubscription && $activeSubscription->isValid())
        @php $pkg = $activeSubscription->package; @endphp
        <div class="sub-status-card active">
            <div class="sub-status-icon">💎</div>
            <div class="sub-status-body">
                <span class="sub-status-badge badge-active">✓ Active</span>
                <h2>{{ $pkg->name }}</h2>
                <p>Started: {{ $activeSubscription->starts_at->format('d M Y') }}
                   &nbsp;·&nbsp;
                   Expires: <strong>{{ $activeSubscription->expires_at->format('d M Y') }}</strong>
                   ({{ $activeSubscription->expires_at->diffForHumans() }})
                </p>
                @if($activeSubscription->amount_paid > 0)
                    <p>Paid: <strong>₹{{ number_format($activeSubscription->amount_paid, 0) }}</strong>
                        @if($activeSubscription->payment_gateway)
                            via {{ ucfirst($activeSubscription->payment_gateway) }}
                        @endif
                    </p>
                @endif

                <div class="sub-features">
                    @if($pkg->can_see_contact)
                        <span class="sub-feat-pill">📞 Contact Access</span>
                    @endif
                    @if($pkg->messages_per_day > 0)
                        <span class="sub-feat-pill">💬 Direct Messaging</span>
                    @endif
                    @if($pkg->priority_in_search)
                        <span class="sub-feat-pill">🔝 Priority Listing</span>
                    @endif
                    @if($pkg->highlight_profile)
                        <span class="sub-feat-pill">⭐ Profile Highlight</span>
                    @endif
                    @if($pkg->can_see_full_horoscope)
                        <span class="sub-feat-pill">🔭 Horoscope Matching</span>
                    @endif
                    @if($pkg->rm_assistance)
                        <span class="sub-feat-pill">🤝 Relationship Manager</span>
                    @endif
                    @if($pkg->whatsapp_support)
                        <span class="sub-feat-pill">📱 WhatsApp Support</span>
                    @endif
                    @if($pkg->photo_gallery_limit <= 0)
                        <span class="sub-feat-pill">📷 Unlimited Photos</span>
                    @elseif($pkg->photo_gallery_limit > 1)
                        <span class="sub-feat-pill">📷 Up to {{ $pkg->photo_gallery_limit }} Photos</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats strip --}}
        <div class="sub-stats">
            <div class="sub-stat-card">
                <div class="sub-stat-num">
                    {{ $activeSubscription->expires_at->diffInDays(now()) > 0
                        ? $activeSubscription->expires_at->diffInDays(now())
                        : $activeSubscription->expires_at->diffInDays(now(), false) }}
                </div>
                <div class="sub-stat-lbl">Days Remaining</div>
            </div>
            <div class="sub-stat-card">
                <div class="sub-stat-num">{{ $pkg->photo_gallery_limit <= 0 ? '∞' : $pkg->photo_gallery_limit }}</div>
                <div class="sub-stat-lbl">Photo Limit</div>
            </div>
            <div class="sub-stat-card">
                <div class="sub-stat-num">{{ $pkg->messages_per_day <= 0 ? '∞' : $pkg->messages_per_day }}</div>
                <div class="sub-stat-lbl">Messages / Day</div>
            </div>
            <div class="sub-stat-card">
                <div class="sub-stat-num">{{ $pkg->interests_per_day <= 0 ? '∞' : $pkg->interests_per_day }}</div>
                <div class="sub-stat-lbl">Interests / Day</div>
            </div>
        </div>

    @else
        {{-- No active subscription --}}
        <div class="sub-status-card free">
            <div class="sub-status-icon">🆓</div>
            <div class="sub-status-body">
                <span class="sub-status-badge badge-free">Free Plan</span>
                <h2>Basic (Free) Plan</h2>
                <p>You are currently on the free plan. Upgrade to unlock direct messaging, contact access, and more.</p>
                <div class="sub-features">
                    <span class="sub-feat-pill dark">✓ Create Profile</span>
                    <span class="sub-feat-pill dark">✓ Express Interest</span>
                    <span class="sub-feat-pill dark">✓ Basic Search</span>
                </div>
            </div>
        </div>

        {{-- Upgrade nudge --}}
        <div class="sub-upgrade-cta">
            <div>
                <h3 style="margin:0 0 6px;font-size:1.05rem;">Ready to find your match faster?</h3>
                <p style="margin:0;font-size:0.87rem;color:#6b7280;">Premium members get 3× more profile responses on average.</p>
            </div>
            <a href="{{ route('user.packages.index') }}" class="btn btn-primary">
                View Plans →
            </a>
        </div>
    @endif


    {{-- ══════════════════════════════════════════════════════════════
        SUBSCRIPTION HISTORY
    ══════════════════════════════════════════════════════════════ --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
        <h3 style="margin:0;font-size:1.05rem;">Subscription History</h3>
        <a href="{{ route('user.packages.index') }}" class="btn btn-outline btn-sm">Browse Plans</a>
    </div>

    @if($history->isEmpty())
        <div class="card" style="padding:40px;text-align:center;color:#9ca3af;">
            <div style="font-size:2.5rem;margin-bottom:12px;">📋</div>
            <p style="margin:0;">No subscription history yet.</p>
        </div>
    @else
        <div style="overflow-x:auto;border-radius:16px;">
            <table class="sub-history-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Plan</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Starts</th>
                        <th>Expires</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $i => $sub)
                        <tr>
                            <td style="color:#9ca3af;">{{ $i + 1 }}</td>
                            <td>
                                <strong>{{ $sub->package?->name ?? 'N/A' }}</strong>
                                @if($sub->isValid())
                                    <span class="status-pill completed" style="margin-left:6px;">Active</span>
                                @elseif($sub->isExpired())
                                    <span class="status-pill failed" style="margin-left:6px;">Expired</span>
                                @endif
                            </td>
                            <td>
                                @if($sub->amount_paid > 0)
                                    ₹{{ number_format($sub->amount_paid, 0) }}
                                @else
                                    <span style="color:#9ca3af;">Free</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-pill {{ $sub->payment_status }}">
                                    {{ ucfirst($sub->payment_status ?? 'unknown') }}
                                </span>
                            </td>
                            <td>{{ $sub->starts_at?->format('d M Y') ?? '—' }}</td>
                            <td>{{ $sub->expires_at?->format('d M Y') ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div><!-- /.container -->
</div><!-- /.sub-page -->
@endsection