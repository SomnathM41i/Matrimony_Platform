@extends('user.layouts.app')

@section('title', ($user->profile->full_name ?? $user->name) . ' — Profile')

@section('content')

@php
    $profile  = $user->profile;
    $pref     = $user->partnerPreference;
    $displayPhoto = $visiblePhotos->firstWhere('is_primary', true) ?? $visiblePhotos->first();
    $photoUrl = $displayPhoto?->url ?? asset('assets/images/default-user.png');
    $isSelf   = auth()->id() === $user->id;

    $fmt = fn($v) => $v ? ucwords(str_replace('_', ' ', $v)) : null;

    $heightDisplay = null;
    if ($profile->height_cm) {
        $inches = round($profile->height_cm / 2.54);
        $ft  = floor($inches / 12);
        $in  = $inches % 12;
        $heightDisplay = "{$ft}'{$in}\" ({$profile->height_cm} cm)";
    }

    // Interest state
    $sentStatus     = $interestSent?->status;       // null | pending | accepted | declined
    $receivedStatus = $interestReceived?->status;   // null | pending | accepted | declined
@endphp

<style>
/* ── reuse same design tokens as my-profile ─────────────────────────── */
.mp-page { background: var(--bg-light, #f7f4f0); min-height: 100vh; padding-bottom: 60px; }

.mp-hero {
    background: linear-gradient(135deg, #1a1033 0%, #2d1b4e 50%, #1a2744 100%);
    padding: 48px 0 80px;
    position: relative;
    overflow: hidden;
}
.mp-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 70% 40%, rgba(196,30,58,0.18), transparent 60%);
}
.mp-hero .container { position: relative; z-index: 1; }
.mp-hero-inner { display: flex; align-items: flex-end; gap: 32px; flex-wrap: wrap; }

.mp-photo-wrap { position: relative; flex-shrink: 0; }
.mp-photo {
    width: 130px; height: 130px; border-radius: 50%; object-fit: cover;
    border: 4px solid rgba(255,255,255,0.25); display: block;
}

.mp-hero-name {
    color: #fff;
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 2rem; font-weight: 600; margin: 0 0 4px; line-height: 1.2;
}
.mp-hero-meta { color: rgba(255,255,255,0.65); font-size: 0.88rem; margin: 0 0 16px; }
.mp-hero-meta span { margin-right: 16px; }
.mp-hero-badges { display: flex; gap: 10px; flex-wrap: wrap; }
.mp-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;
    border: 1px solid rgba(255,255,255,0.2); color: rgba(255,255,255,0.85);
    background: rgba(255,255,255,0.08);
}
.mp-badge.verified { background: rgba(34,197,94,0.15); border-color: rgba(34,197,94,0.4); color: #86efac; }
.mp-badge.premium  { background: rgba(212,160,23,0.15); border-color: rgba(212,160,23,0.4); color: #fcd34d; }

.mp-hero-actions {
    margin-left: auto; display: flex; gap: 10px;
    align-self: center; flex-wrap: wrap;
}
@media (max-width: 640px) {
    .mp-hero-actions { margin-left: 0; width: 100%; }
    .mp-hero-inner  { gap: 20px; }
    .mp-hero-name   { font-size: 1.5rem; }
}

/* Layout */
.mp-body {
    max-width: 1100px; margin: -48px auto 0; padding: 0 20px;
    display: grid; grid-template-columns: 1fr 340px; gap: 24px;
    position: relative; z-index: 10;
}
@media (max-width: 900px) { .mp-body { grid-template-columns: 1fr; margin-top: -24px; } }

/* Cards */
.mp-card {
    background: #fff; border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 20px;
}
.mp-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 24px 14px; border-bottom: 1px solid rgba(0,0,0,0.06);
}
.mp-card-title {
    font-size: 0.95rem; font-weight: 600; color: #1a1a2e;
    display: flex; align-items: center; gap: 8px; margin: 0;
}
.mp-card-title .icon {
    width: 28px; height: 28px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem; flex-shrink: 0;
}
.mp-card-body { padding: 20px 24px; }

.mp-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 24px; }
@media (max-width: 580px) { .mp-grid { grid-template-columns: 1fr; } }

.mp-item label {
    display: block; font-size: 0.72rem; color: #9ca3af;
    text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 3px; font-weight: 500;
}
.mp-item .value { font-size: 0.92rem; color: #1f2937; font-weight: 500; }
.mp-item .value.empty { color: #d1d5db; font-style: italic; font-weight: 400; }

.mp-about {
    font-size: 0.92rem; color: #374151; line-height: 1.75;
    background: #faf9f7; border-radius: 10px; padding: 16px 18px;
    border: 1px solid rgba(0,0,0,0.05); margin-bottom: 0;
}
.mp-tags { display: flex; flex-wrap: wrap; gap: 8px; }
.mp-tag {
    padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;
    background: #f3f0ff; color: #6d28d9; border: 1px solid #ede9fe;
}

/* Gallery */
.mp-gallery {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
    gap: 10px; padding: 16px 24px 20px;
}
.mp-gallery-item { aspect-ratio: 1; border-radius: 10px; overflow: hidden; }
.mp-gallery-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
.mp-no-photo { grid-column: 1/-1; text-align: center; padding: 24px; color: #9ca3af; font-size: 0.85rem; }

/* ── Action card (sidebar) ── */
.action-card { background: #fff; border-radius: 16px; border: 1px solid rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 20px; position: sticky; top: 20px; }
.action-card-body { padding: 20px; display: flex; flex-direction: column; gap: 12px; }

.pub-action-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 12px 16px; border-radius: 10px; font-size: 0.9rem;
    font-weight: 600; border: none; cursor: pointer; transition: all .2s;
    text-decoration: none; text-align: center;
}
.pub-action-btn.primary   { background: #7c3aed; color: #fff; }
.pub-action-btn.primary:hover { background: #6d28d9; }
.pub-action-btn.primary:disabled,
.pub-action-btn.primary.sent { background: #c4b5fd; color: #4c1d95; cursor: default; }
.pub-action-btn.outline   { background: transparent; color: #7c3aed; border: 2px solid #7c3aed; }
.pub-action-btn.outline:hover { background: #f3e8ff; }
.pub-action-btn.outline.shortlisted { background: #f3e8ff; }
.pub-action-btn.success   { background: #dcfce7; color: #166534; cursor: default; }
.pub-action-btn.warning   { background: #fef9c3; color: #854d0e; cursor: default; }
.pub-action-btn.danger    { background: #fee2e2; color: #991b1b; cursor: default; }
.pub-action-btn.ghost     { background: #f9fafb; color: #6b7280; border: 2px solid #e5e7eb; }
.pub-action-btn.ghost:hover { background: #f3f4f6; color: #374151; }

/* Interest received banner */
.interest-received-banner {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border: 1px solid #fbbf24; border-radius: 10px; padding: 12px 14px;
    font-size: 0.85rem; color: #92400e; display: flex; align-items: center; gap: 8px;
}

/* Divider */
.action-divider { border: none; border-top: 1px solid #f0edf8; margin: 0; }

/* Stats row */
.pub-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.pub-stat { text-align: center; background: #faf7ff; border-radius: 10px; padding: 12px 8px; }
.pub-stat .num { font-size: 1.2rem; font-weight: 700; color: #7c3aed; }
.pub-stat .lbl { font-size: 0.7rem; color: #9ca3af; margin-top: 2px; }
</style>

<div class="mp-page">

  {{-- ── HERO ── --}}
  <div class="mp-hero">
    <div class="container">

      {{-- Breadcrumb --}}
      <div class="breadcrumb" style="margin-bottom:20px;">
        <a href="{{ route('home') }}" style="color:rgba(255,255,255,0.5);">Home</a>
        <span style="color:rgba(255,255,255,0.3);">/</span>
        <a href="{{ route('user.matches.index') }}" style="color:rgba(255,255,255,0.5);">Matches</a>
        <span style="color:rgba(255,255,255,0.3);">/</span>
        <span style="color:rgba(255,255,255,0.7);">{{ $profile->first_name ?? $user->name }}</span>
      </div>

      <div class="mp-hero-inner">

        {{-- Photo --}}
        <div class="mp-photo-wrap">
          <img src="{{ $photoUrl }}" alt="{{ $user->name }}" class="mp-photo"
               onerror="this.src='{{ asset('assets/images/default-user.png') }}'">
        </div>

        {{-- Info --}}
        <div style="flex:1;min-width:0;">
          <h1 class="mp-hero-name">{{ $profile->full_name ?? $user->name }}</h1>
          <p class="mp-hero-meta">
            @if($user->date_of_birth)
              <span>{{ $user->age }} yrs</span>
            @endif
            @if($heightDisplay)
              <span>{{ $heightDisplay }}</span>
            @endif
            @if($profile->city?->name || $profile->state?->name)
              <span>{{ collect([$profile->city?->name, $profile->state?->name])->filter()->implode(', ') }}</span>
            @endif
            @if($profile->religion?->name)
              <span>{{ $profile->religion->name }}</span>
            @endif
          </p>
          <div class="mp-hero-badges">
            @if($user->email_verified_at)
              <span class="mp-badge verified">✓ Verified</span>
            @endif
            @if($user->isPremiumActive())
              <span class="mp-badge premium">★ Premium</span>
            @endif
            @if($user->last_login_at)
              <span class="mp-badge">
                Last active {{ $user->last_login_at->diffForHumans() }}
              </span>
            @endif
          </div>
        </div>

        {{-- Hero quick-actions (desktop) --}}
        @if(!$isSelf)
        <div class="mp-hero-actions">
          @if($sentStatus === 'accepted')
            <span class="btn btn-sm" style="background:rgba(34,197,94,0.2);color:#86efac;border:1px solid rgba(34,197,94,0.4);">
              ✅ Connected
            </span>
          @elseif($sentStatus === 'pending')
            <span class="btn btn-sm" style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.7);border:1px solid rgba(255,255,255,0.2);">
              ⏳ Interest Sent
            </span>
          @else
            <form method="POST" action="{{ route('user.interests.send', $user) }}">
              @csrf
              <button type="submit" class="btn btn-primary btn-sm">
                💌 Send Interest
              </button>
            </form>
          @endif
        </div>
        @endif

      </div>
    </div>
  </div>

  {{-- ── BODY ── --}}
  <div class="mp-body">

    {{-- ══ LEFT COLUMN ══ --}}
    <div>

      {{-- Flash --}}
      @foreach(['success','error','info'] as $t)
        @if(session($t))
          <div class="alert alert-{{ $t }}" style="margin-bottom:16px;">{{ session($t) }}</div>
        @endif
      @endforeach

      {{-- About Me --}}
      @if($profile->about_me)
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#f3f0ff;">💬</span> About
          </h3>
        </div>
        <div class="mp-card-body">
          <p class="mp-about">{{ $profile->about_me }}</p>
        </div>
      </div>
      @endif

      {{-- Basic Info --}}
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#fff7ed;">👤</span> Basic Information
          </h3>
        </div>
        <div class="mp-card-body">
          <div class="mp-grid">

            <div class="mp-item">
              <label>Marital Status</label>
              <span class="value {{ !$profile->marital_status ? 'empty' : '' }}">
                {{ $fmt($profile->marital_status) ?? '—' }}
              </span>
            </div>

            @if($profile->marital_status !== 'never_married' && $profile->no_of_children !== null)
            <div class="mp-item">
              <label>Children</label>
              <span class="value">{{ $profile->no_of_children }}</span>
            </div>
            @endif

            <div class="mp-item">
              <label>Height</label>
              <span class="value {{ !$heightDisplay ? 'empty' : '' }}">
                {{ $heightDisplay ?? '—' }}
              </span>
            </div>

            @if($profile->weight_kg)
            <div class="mp-item">
              <label>Weight</label>
              <span class="value">{{ $profile->weight_kg }} kg</span>
            </div>
            @endif

            <div class="mp-item">
              <label>Body Type</label>
              <span class="value {{ !$profile->body_type ? 'empty' : '' }}">
                {{ $fmt($profile->body_type) ?? '—' }}
              </span>
            </div>

            <div class="mp-item">
              <label>Complexion</label>
              <span class="value {{ !$profile->complexion ? 'empty' : '' }}">
                {{ $fmt($profile->complexion) ?? '—' }}
              </span>
            </div>

            <div class="mp-item">
              <label>Blood Group</label>
              <span class="value {{ !$profile->blood_group ? 'empty' : '' }}">
                {{ $profile->blood_group ?? '—' }}
              </span>
            </div>

            <div class="mp-item">
              <label>Diet</label>
              <span class="value {{ !$profile->diet ? 'empty' : '' }}">
                {{ $fmt($profile->diet) ?? '—' }}
              </span>
            </div>

            <div class="mp-item">
              <label>Smoking</label>
              <span class="value {{ !$profile->smoking ? 'empty' : '' }}">
                {{ $fmt($profile->smoking) ?? '—' }}
              </span>
            </div>

            <div class="mp-item">
              <label>Drinking</label>
              <span class="value {{ !$profile->drinking ? 'empty' : '' }}">
                {{ $fmt($profile->drinking) ?? '—' }}
              </span>
            </div>

          </div>
        </div>
      </div>

      {{-- Religion & Community --}}
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#fef3c7;">🛕</span> Religion & Community
          </h3>
        </div>
        <div class="mp-card-body">
          <div class="mp-grid">

            <div class="mp-item">
              <label>Religion</label>
              <span class="value {{ !$profile->religion ? 'empty' : '' }}">
                {{ $profile->religion?->name ?? '—' }}
              </span>
            </div>

            <div class="mp-item">
              <label>Caste</label>
              <span class="value {{ !$profile->caste ? 'empty' : '' }}">
                {{ $profile->caste?->name ?? '—' }}
              </span>
            </div>

            @if($profile->subCaste)
            <div class="mp-item">
              <label>Sub-caste</label>
              <span class="value">{{ $profile->subCaste->name }}</span>
            </div>
            @endif

            @if($profile->gotra)
            <div class="mp-item">
              <label>Gotra</label>
              <span class="value">{{ $profile->gotra->name }}</span>
            </div>
            @endif

            @if($profile->community)
            <div class="mp-item">
              <label>Community</label>
              <span class="value">{{ $profile->community->name }}</span>
            </div>
            @endif

            <div class="mp-item">
              <label>Mother Tongue</label>
              <span class="value {{ !$profile->motherTongue ? 'empty' : '' }}">
                {{ $profile->motherTongue?->name ?? '—' }}
              </span>
            </div>

          </div>

          @if(!empty($profile->languages_known))
          <div style="margin-top:16px;">
            <label style="font-size:0.72rem;color:#9ca3af;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;display:block;">
              Languages Known
            </label>
            <div class="mp-tags">
              @foreach($profile->languages_known as $lang)
                <span class="mp-tag">{{ is_object($lang) ? $lang->name : $lang }}</span>
              @endforeach
            </div>
          </div>
          @endif
        </div>
      </div>

      {{-- Horoscope --}}
      @if($profile->rashi || $profile->nakshatra || $profile->manglik_status || $profile->birth_time || $profile->birth_place)
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#f0f4ff;">🌙</span> Horoscope
          </h3>
        </div>
        <div class="mp-card-body">
          <div class="mp-grid">

            @if($profile->rashi)
            <div class="mp-item">
              <label>Rashi</label>
              <span class="value">{{ $profile->rashi->name }}</span>
            </div>
            @endif

            @if($profile->nakshatra)
            <div class="mp-item">
              <label>Nakshatra</label>
              <span class="value">{{ $profile->nakshatra->name }}</span>
            </div>
            @endif

            @if($profile->manglik_status)
            <div class="mp-item">
              <label>Manglik</label>
              <span class="value">{{ $fmt($profile->manglik_status) }}</span>
            </div>
            @endif

            @if($profile->birth_time)
            <div class="mp-item">
              <label>Birth Time</label>
              <span class="value">{{ $profile->birth_time }}</span>
            </div>
            @endif

            @if($profile->birth_place)
            <div class="mp-item">
              <label>Birth Place</label>
              <span class="value">{{ $profile->birth_place }}</span>
            </div>
            @endif

          </div>
        </div>
      </div>
      @endif

      {{-- Education & Career --}}
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#ecfdf5;">🎓</span> Education & Career
          </h3>
        </div>
        <div class="mp-card-body">
          <div class="mp-grid">

            <div class="mp-item">
              <label>Education</label>
              <span class="value {{ !$profile->educationLevel ? 'empty' : '' }}">
                {{ $profile->educationLevel?->name ?? '—' }}
              </span>
            </div>

            @if($profile->education_details)
            <div class="mp-item">
              <label>Specialisation</label>
              <span class="value">{{ $profile->education_details }}</span>
            </div>
            @endif

            <div class="mp-item">
              <label>Profession</label>
              <span class="value {{ !$profile->profession ? 'empty' : '' }}">
                {{ $profile->profession?->name ?? '—' }}
              </span>
            </div>

            @if($profile->company_name)
            <div class="mp-item">
              <label>Organisation</label>
              <span class="value">{{ $profile->company_name }}</span>
            </div>
            @endif

            <div class="mp-item">
              <label>Annual Income</label>
              <span class="value {{ !$profile->annualIncomeRange ? 'empty' : '' }}">
                {{ $profile->annualIncomeRange?->display_label ?? '—' }}
              </span>
            </div>

          </div>
        </div>
      </div>

      {{-- Location & Family --}}
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#fff1f2;">🏠</span> Location & Family
          </h3>
        </div>
        <div class="mp-card-body">
          <div class="mp-grid">

            <div class="mp-item">
              <label>Country</label>
              <span class="value {{ !$profile->country ? 'empty' : '' }}">
                {{ $profile->country?->name ?? '—' }}
              </span>
            </div>

            <div class="mp-item">
              <label>State</label>
              <span class="value {{ !$profile->state ? 'empty' : '' }}">
                {{ $profile->state?->name ?? '—' }}
              </span>
            </div>

            <div class="mp-item">
              <label>City</label>
              <span class="value {{ !$profile->city ? 'empty' : '' }}">
                {{ $profile->city?->name ?? '—' }}
              </span>
            </div>

            <div class="mp-item">
              <label>Residency</label>
              <span class="value {{ !$profile->residency_status ? 'empty' : '' }}">
                {{ $fmt($profile->residency_status) ?? '—' }}
              </span>
            </div>

            <div class="mp-item">
              <label>Family Type</label>
              <span class="value {{ !$profile->family_type ? 'empty' : '' }}">
                {{ $fmt($profile->family_type) ?? '—' }}
              </span>
            </div>

            @if($profile->family_status)
            <div class="mp-item">
              <label>Family Status</label>
              <span class="value">{{ $fmt($profile->family_status) }}</span>
            </div>
            @endif

            <div class="mp-item">
              <label>Brothers</label>
              <span class="value">{{ $profile->no_of_brothers ?? 0 }}</span>
            </div>

            <div class="mp-item">
              <label>Sisters</label>
              <span class="value">{{ $profile->no_of_sisters ?? 0 }}</span>
            </div>

          </div>
        </div>
      </div>

      {{-- Photos --}}
      @if($visiblePhotos->isNotEmpty())
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#f0f9ff;">📷</span> Photos
          </h3>
        </div>
        <div class="mp-gallery">
          @foreach($visiblePhotos as $photo)
            <div class="mp-gallery-item">
              <img src="{{ $photo->thumbnail_url ?? $photo->url }}" alt="Photo">
            </div>
          @endforeach
        </div>
      </div>
      @elseif(!$canViewPhotos)
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#f0f9ff;">📷</span> Photos
          </h3>
        </div>
        <div class="mp-gallery">
          <div class="mp-no-photo">Photos are private for this profile.</div>
        </div>
      </div>
      @endif

    </div>{{-- /left --}}

    {{-- ══ RIGHT SIDEBAR ══ --}}
    <div>

      {{-- ── Action Card ── --}}
      @if(!$isSelf)
      <div class="action-card">
        <div style="padding:18px 20px 14px;border-bottom:1px solid #f0edf8;">
          <h3 style="font-size:0.95rem;font-weight:600;color:#1a1033;margin:0;display:flex;align-items:center;gap:8px;">
            <span style="font-size:1.1rem;">💌</span> Connect with {{ $profile->first_name ?? $user->name }}
          </h3>
        </div>
        <div class="action-card-body">

          {{-- Incoming interest banner --}}
          @if($receivedStatus === 'pending')
            <div class="interest-received-banner">
              💛 This person has sent you an interest!
              <a href="{{ route('user.interests.received') }}" style="font-weight:700;color:#92400e;margin-left:auto;white-space:nowrap;">View →</a>
            </div>
          @endif

          {{-- Send / Cancel Interest --}}
          @if($sentStatus === 'accepted')
            <div class="pub-action-btn success">✅ Interest Accepted — You're Connected!</div>
          @elseif($sentStatus === 'pending')
            <div class="pub-action-btn warning">⏳ Interest Sent — Awaiting Response</div>
            <form method="POST" action="{{ route('user.interests.cancel', $interestSent) }}">
              @csrf
              @method('DELETE')
              <button type="submit" class="pub-action-btn ghost"
                      onclick="return confirm('Withdraw your interest?')" style="width:100%;">
                ✕ Withdraw Interest
              </button>
            </form>
          @elseif($sentStatus === 'declined')
            <div class="pub-action-btn danger">✕ Interest Declined</div>
          @else
            {{-- Show message textarea optionally --}}
            <form method="POST" action="{{ route('user.interests.send', $user) }}" id="interestForm">
              @csrf
              <textarea name="message" rows="3"
                placeholder="Write a personal note... (optional)"
                maxlength="500"
                style="width:100%;padding:10px 12px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.84rem;resize:none;outline:none;font-family:inherit;box-sizing:border-box;margin-bottom:10px;"
                onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#e5e7eb'"></textarea>
              <button type="submit" class="pub-action-btn primary" style="width:100%;">
                💌 Send Interest
              </button>
            </form>
          @endif

          <hr class="action-divider">

          {{-- Shortlist --}}
          <form method="POST" action="{{ route('user.shortlist.toggle', $user) }}">
            @csrf
            <button type="submit" class="pub-action-btn outline {{ $isShortlisted ? 'shortlisted' : '' }}" style="width:100%;">
              {{ $isShortlisted ? '★ Shortlisted' : '☆ Add to Shortlist' }}
            </button>
          </form>

          {{-- Message (only if connected) --}}
          @if($sentStatus === 'accepted' || $receivedStatus === 'accepted')
            <a href="{{ route('user.messages.index') }}" class="pub-action-btn primary" style="display:flex;margin-top:0;">
              💬 Send Message
            </a>
          @endif

        </div>
      </div>
      @else
      {{-- Self viewing their own public profile --}}
      <div class="action-card">
        <div class="action-card-body">
          <p style="font-size:0.85rem;color:#6b7280;text-align:center;margin:0 0 12px;">
            This is how others see your profile.
          </p>
          <a href="{{ route('user.profile.me') }}" class="pub-action-btn outline" style="display:flex;">
            👤 View My Profile Dashboard
          </a>
          <a href="{{ route('user.profile.edit') }}" class="pub-action-btn primary" style="display:flex;">
            ✏️ Edit Profile
          </a>
        </div>
      </div>
      @endif

      {{-- Contact Details --}}
      @if(!$isSelf)
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#ecfdf5;">📞</span> Contact Details
          </h3>
        </div>
        <div class="mp-card-body">
          @if($canViewContact)
            <div class="mp-item" style="margin-bottom:12px;">
              <label>Email</label>
              <span class="value">{{ $user->email }}</span>
            </div>
            <div class="mp-item">
              <label>Phone</label>
              <span class="value">{{ $user->phone ?? '—' }}</span>
            </div>
          @else
            <p style="font-size:0.85rem;color:#6b7280;margin:0;line-height:1.6;">
              Contact details are private or require a plan with contact access.
            </p>
          @endif
        </div>
      </div>
      @endif

      {{-- Quick Stats --}}
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#f0f4ff;">📊</span> Profile Stats
          </h3>
        </div>
        <div class="mp-card-body" style="padding:16px;">
          <div class="pub-stats">
            <div class="pub-stat">
              <div class="num">{{ $profile->completion_percentage ?? 0 }}%</div>
              <div class="lbl">Complete</div>
            </div>
            <div class="pub-stat">
              <div class="num">{{ $visiblePhotos->count() }}</div>
              <div class="lbl">Photos</div>
            </div>
            <div class="pub-stat">
              <div class="num">{{ $user->receivedInterests()->count() }}</div>
              <div class="lbl">Interests</div>
            </div>
            <div class="pub-stat">
              <div class="num">{{ $user->last_login_at ? $user->last_login_at->diffInDays() : '—' }}</div>
              <div class="lbl">Days since active</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Partner Preferences (brief) --}}
      @if($pref)
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#fdf2f8;">💑</span> Partner Preferences
          </h3>
        </div>
        <div class="mp-card-body">
          <div style="display:flex;flex-direction:column;gap:10px;">

            @if($pref->age_min && $pref->age_max)
            <div class="mp-item">
              <label>Age Range</label>
              <span class="value">{{ $pref->age_min }} – {{ $pref->age_max }} yrs</span>
            </div>
            @endif

            @if($pref->height_min_cm && $pref->height_max_cm)
            <div class="mp-item">
              <label>Height Range</label>
              <span class="value">{{ $pref->height_min_cm }} – {{ $pref->height_max_cm }} cm</span>
            </div>
            @endif

            @if(!empty($pref->marital_status))
            <div class="mp-item">
              <label>Marital Status</label>
              <div class="mp-tags" style="margin-top:4px;">
                @foreach($pref->marital_status as $ms)
                  <span class="mp-tag">{{ $fmt($ms) }}</span>
                @endforeach
              </div>
            </div>
            @endif

            @if($pref->about_partner)
            <div class="mp-item">
              <label>Ideal Partner</label>
              <p style="font-size:0.84rem;color:#374151;margin:4px 0 0;line-height:1.6;">
                {{ Str::limit($pref->about_partner, 120) }}
              </p>
            </div>
            @endif

          </div>
        </div>
      </div>
      @endif

    </div>{{-- /sidebar --}}

  </div>{{-- /mp-body --}}
</div>{{-- /mp-page --}}

@endsection
