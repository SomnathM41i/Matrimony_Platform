@extends('admin.layouts.app')

@section('title', 'Subscription Plans')

@section('content')

<div class="page-header">
    <div class="page-header-inner">
        <div>
            <div class="page-eyebrow">Business</div>
            <h1 class="page-title">Subscription <em>Plans</em></h1>
            <p class="page-subtitle">Manage pricing packages and feature sets</p>
        </div>
        <a href="{{ route('admin.plans.create') }}" class="btn btn-rose">
            <i class="fas fa-plus"></i> New Plan
        </a>
    </div>
</div>

{{-- Flash --}}
@foreach(['success','error'] as $t)
    @if(session($t))
        <div class="alert-banner alert-{{ $t }}" style="
            margin-bottom:1.5rem; padding:.875rem 1.25rem; border-radius:12px;
            background:{{ $t==='success' ? 'rgba(74,140,106,.12)' : 'rgba(184,76,76,.12)' }};
            border:1px solid {{ $t==='success' ? 'rgba(74,140,106,.3)' : 'rgba(184,76,76,.3)' }};
            color:{{ $t==='success' ? 'var(--success)' : 'var(--danger)' }};
            font-size:.85rem; font-weight:600; display:flex; align-items:center; gap:.75rem;">
            <i class="fas {{ $t==='success' ? 'fa-circle-check' : 'fa-circle-exclamation' }}"></i>
            {{ session($t) }}
        </div>
    @endif
@endforeach

{{-- ── Stats row ── --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:1.5rem;">

    <div class="stat-card rose">
        <div class="stat-top">
            <div class="stat-icon rose"><i class="fas fa-crown"></i></div>
        </div>
        <div class="stat-label">Total Plans</div>
        <div class="stat-value">{{ $stats['total_plans'] }}</div>
        <div class="stat-sub">{{ $stats['active_plans'] }} active</div>
    </div>

    <div class="stat-card gold">
        <div class="stat-top">
            <div class="stat-icon gold"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-label">Active Subscribers</div>
        <div class="stat-value">{{ number_format($stats['total_subscribers']) }}</div>
    </div>

    <div class="stat-card green">
        <div class="stat-top">
            <div class="stat-icon green"><i class="fas fa-indian-rupee-sign"></i></div>
        </div>
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">₹{{ number_format($stats['total_revenue'] / 100000, 1) }}L</div>
        <div class="stat-sub">All time (successful txns)</div>
    </div>

    <div class="stat-card blue">
        <div class="stat-top">
            <div class="stat-icon blue"><i class="fas fa-chart-line"></i></div>
        </div>
        <div class="stat-label">Avg. per Subscriber</div>
        <div class="stat-value">
            ₹{{ $stats['total_subscribers'] > 0 ? number_format($stats['total_revenue'] / $stats['total_subscribers']) : 0 }}
        </div>
    </div>

</div>

{{-- ── Filter bar ── --}}
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.25rem;">
    <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;">
        <select name="status" class="form-control" style="width:auto;min-width:160px;" onchange="this.form.submit()">
            <option value="">All Plans</option>
            <option value="active"   {{ request('status')==='active'   ? 'selected' : '' }}>Active Only</option>
            <option value="inactive" {{ request('status')==='inactive' ? 'selected' : '' }}>Inactive Only</option>
        </select>
    </form>
    <div style="margin-left:auto;font-size:.8rem;color:var(--text-muted);">
        {{ $plans->count() }} plan{{ $plans->count()!==1?'s':'' }}
    </div>
</div>

{{-- ── Plans grid ── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1.25rem;">

    @forelse($plans as $plan)
    @php
        $subs    = $subscriberCounts[$plan->id] ?? 0;
        $revenue = $revenueTotals[$plan->id] ?? 0;
    @endphp

    <div class="card" style="position:relative;overflow:visible;">

        {{-- Featured ribbon --}}
        @if($plan->is_featured)
            <div style="
                position:absolute;top:-10px;left:20px;
                background:var(--premium-grad); color:var(--text-primary);
                font-size:.65rem; font-weight:800; letter-spacing:1.5px;
                text-transform:uppercase; padding:3px 12px; border-radius:20px;
                box-shadow:0 3px 10px rgba(201,168,108,.4);">
                ⭐ Featured
            </div>
        @endif

        <div class="card-header" style="padding:1.25rem 1.5rem 1rem;">
            <div>
                <h3 class="card-title" style="font-size:1.35rem;">
                    <i class="fas fa-crown" style="color:var(--gold);"></i>
                    {{ $plan->name }}
                </h3>
                @if($plan->description)
                    <p style="font-size:.78rem;color:var(--text-muted);margin-top:.25rem;">
                        {{ Str::limit($plan->description, 70) }}
                    </p>
                @endif
            </div>
            <span class="badge {{ $plan->is_active ? 'badge-active' : 'badge-inactive' }}">
                {{ $plan->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <div class="card-body" style="padding:1.25rem 1.5rem;">

            {{-- Price + Duration --}}
            <div style="display:flex;align-items:baseline;gap:.5rem;margin-bottom:1rem;">
                <span style="font-family:'Cormorant Garamond',serif;font-size:2.5rem;font-weight:700;color:var(--text-primary);line-height:1;">
                    {{ $plan->isFree() ? 'Free' : '₹'.number_format($plan->price) }}
                </span>
                @if(!$plan->isFree())
                    <span style="font-size:.8rem;color:var(--text-muted);">
                        / {{ $plan->duration_days }} days
                        @if($plan->trial_days) &nbsp;·&nbsp; {{ $plan->trial_days }}-day trial @endif
                    </span>
                @endif
            </div>

            {{-- Feature list --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem .75rem;margin-bottom:1rem;">

                @php
                    $features = [
                        ['icon'=>'fa-eye',        'label'=>'Contact Views',   'val'=> $plan->contact_views ?? '∞'],
                        ['icon'=>'fa-heart',       'label'=>'Interests/Day',   'val'=> $plan->interests_per_day ?? '∞'],
                        ['icon'=>'fa-message',     'label'=>'Messages/Day',    'val'=> $plan->messages_per_day ?? '∞'],
                        ['icon'=>'fa-image',       'label'=>'Photo Gallery',   'val'=> $plan->photo_gallery_limit ?? '∞'],
                    ];
                    $toggles = [
                        ['icon'=>'fa-phone',           'label'=>'See Contact',    'val'=>$plan->can_see_contact],
                        ['icon'=>'fa-star',             'label'=>'Full Horoscope', 'val'=>$plan->can_see_full_horoscope],
                        ['icon'=>'fa-sparkles',         'label'=>'Highlighted',    'val'=>$plan->highlight_profile],
                        ['icon'=>'fa-magnifying-glass', 'label'=>'Priority Search','val'=>$plan->priority_in_search],
                        ['icon'=>'fa-whatsapp',         'label'=>'WhatsApp Help',  'val'=>$plan->whatsapp_support],
                        ['icon'=>'fa-user-tie',         'label'=>'RM Assistance',  'val'=>$plan->rm_assistance],
                    ];
                @endphp

                @foreach($features as $f)
                    <div style="display:flex;align-items:center;gap:.5rem;font-size:.78rem;">
                        <i class="fas {{ $f['icon'] }}" style="width:14px;color:var(--rose);"></i>
                        <span style="color:var(--text-muted);">{{ $f['label'] }}</span>
                        <strong style="margin-left:auto;color:var(--text-primary);">{{ $f['val'] }}</strong>
                    </div>
                @endforeach
            </div>

            {{-- Boolean toggles --}}
            <div style="display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:1.25rem;">
                @foreach($toggles as $t)
                    <span style="
                        display:inline-flex;align-items:center;gap:.3rem;
                        padding:.25rem .6rem;border-radius:20px;font-size:.68rem;font-weight:600;
                        background:{{ $t['val'] ? 'rgba(74,140,106,.12)' : 'rgba(160,128,112,.08)' }};
                        color:{{ $t['val'] ? 'var(--success)' : 'var(--text-muted)' }};">
                        <i class="fas {{ $t['val'] ? 'fa-check' : 'fa-xmark' }}"></i>
                        {{ $t['label'] }}
                    </span>
                @endforeach
            </div>

            {{-- Subscriber / Revenue row --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:1.25rem;">
                <div style="background:var(--bg-secondary);border-radius:10px;padding:.75rem;text-align:center;">
                    <div style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:700;color:var(--text-primary);">
                        {{ number_format($subs) }}
                    </div>
                    <div style="font-size:.7rem;color:var(--text-muted);margin-top:.1rem;">Active Subscribers</div>
                </div>
                <div style="background:var(--bg-secondary);border-radius:10px;padding:.75rem;text-align:center;">
                    <div style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:700;color:var(--gold-dark);">
                        ₹{{ number_format($revenue) }}
                    </div>
                    <div style="font-size:.7rem;color:var(--text-muted);margin-top:.1rem;">Revenue Generated</div>
                </div>
            </div>

            {{-- Actions --}}
            <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-pen"></i> Edit
                </a>

                <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" style="display:inline;"
                      onsubmit="return confirm('Delete plan \'{{ addslashes($plan->name) }}\'? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button class="btn btn-ghost btn-sm" style="color:var(--danger);">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>

                {{-- Quick toggle active --}}
                <form method="POST" action="{{ route('admin.plans.update', $plan) }}" style="margin-left:auto;">
                    @csrf @method('PUT')
                    <input type="hidden" name="name"          value="{{ $plan->name }}">
                    <input type="hidden" name="price"         value="{{ $plan->price }}">
                    <input type="hidden" name="currency"      value="{{ $plan->currency ?? 'INR' }}">
                    <input type="hidden" name="duration_days" value="{{ $plan->duration_days }}">
                    <input type="hidden" name="is_active"     value="{{ $plan->is_active ? 0 : 1 }}">
                    <input type="hidden" name="is_featured"   value="{{ (int)$plan->is_featured }}">
                    <button class="btn btn-sm {{ $plan->is_active ? 'btn-outline' : 'btn-rose' }}">
                        <i class="fas {{ $plan->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                        {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>

        </div>
    </div>
    @empty
        <div class="card" style="grid-column:1/-1;">
            <div class="card-body" style="text-align:center;padding:3rem;">
                <i class="fas fa-crown" style="font-size:2.5rem;color:var(--border);margin-bottom:1rem;display:block;"></i>
                <p style="color:var(--text-muted);margin-bottom:1rem;">No plans yet. Create your first subscription plan.</p>
                <a href="{{ route('admin.plans.create') }}" class="btn btn-rose">
                    <i class="fas fa-plus"></i> Create Plan
                </a>
            </div>
        </div>
    @endforelse

</div>

@endsection