@extends('user.layouts.app')
@section('title', ($user->profile->full_name ?? $user->name) . ' — My Profile')

@section('content')

{{-- ════════════════════════════════════════════════════════════════════
     MY PROFILE PAGE
     Variables available:
       $user            → App\Models\User (with all relationships loaded)
       $user->profile   → App\Models\UserProfile
       $user->photos    → Collection<ProfilePhoto>
       $user->primaryPhoto → ProfilePhoto|null
       $user->partnerPreference → PartnerPreference|null
       $completedStep   → int (1–7, from ProfileSetupService)
════════════════════════════════════════════════════════════════════ --}}

@php
    $profile  = $user->profile;
    $pref     = $user->partnerPreference;
    $photoUrl = $user->primaryPhoto?->url ?? asset('assets/images/default-user.png');

    // Helper: format enum values nicely
    $fmt = fn($v) => $v ? ucwords(str_replace('_', ' ', $v)) : null;

    // Height display
    $heightDisplay = null;
    if ($profile->height_cm) {
        $inches = round($profile->height_cm / 2.54);
        $ft = floor($inches / 12);
        $in = $inches % 12;
        $heightDisplay = "{$ft}'{$in}\" ({$profile->height_cm} cm)";
    }

    // Completion colour
    $pct = $profile->completion_percentage ?? 0;
    $pctColor = $pct >= 80 ? '#22c55e' : ($pct >= 50 ? '#f59e0b' : '#ef4444');
@endphp

<style>
/* ── Page shell ── */
.mp-page { background: var(--bg-light, #f7f4f0); min-height: 100vh; padding-bottom: 60px; }

/* ── Hero banner ── */
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
    background: radial-gradient(ellipse at 70% 40%, rgba(196,30,58,0.2), transparent 60%);
}
.mp-hero .container { position: relative; z-index: 1; }
.mp-hero-inner { display: flex; align-items: flex-end; gap: 32px; flex-wrap: wrap; }

/* Photo ring */
.mp-photo-wrap {
    position: relative;
    flex-shrink: 0;
}
.mp-photo {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,0.25);
    display: block;
}
.mp-photo-ring {
    position: absolute;
    inset: -6px;
    border-radius: 50%;
    background: conic-gradient(
        #d4a017 calc({{ $pct }}% * 3.6deg / 1deg * 1%),
        rgba(255,255,255,0.12) 0%
    );
    /* fallback: always visible ring */
    background: conic-gradient(#d4a017 {{ $pct * 3.6 }}deg, rgba(255,255,255,0.12) 0deg);
    z-index: -1;
    border-radius: 50%;
}
.mp-photo-pct {
    position: absolute;
    bottom: 4px;
    right: 4px;
    background: #d4a017;
    color: #1a1033;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 20px;
    border: 2px solid #1a2744;
}

/* Hero text */
.mp-hero-name {
    color: #fff;
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 2rem;
    font-weight: 600;
    margin: 0 0 4px;
    line-height: 1.2;
}
.mp-hero-meta {
    color: rgba(255,255,255,0.65);
    font-size: 0.88rem;
    margin: 0 0 16px;
}
.mp-hero-meta span { margin-right: 16px; }
.mp-hero-badges { display: flex; gap: 10px; flex-wrap: wrap; }
.mp-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 500;
    border: 1px solid rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.85);
    background: rgba(255,255,255,0.08);
}
.mp-badge.verified { background: rgba(34,197,94,0.15); border-color: rgba(34,197,94,0.4); color: #86efac; }
.mp-badge.premium  { background: rgba(212,160,23,0.15); border-color: rgba(212,160,23,0.4); color: #fcd34d; }

/* Hero actions */
.mp-hero-actions {
    margin-left: auto;
    display: flex;
    gap: 10px;
    align-self: center;
    flex-wrap: wrap;
}
@media (max-width: 640px) {
    .mp-hero-actions { margin-left: 0; width: 100%; }
    .mp-hero-inner { gap: 20px; }
    .mp-hero-name { font-size: 1.5rem; }
}

/* ── Main layout ── */
.mp-body {
    max-width: 1100px;
    margin: -48px auto 0;
    padding: 0 20px;
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 24px;
    position: relative;
    z-index: 10;
}
@media (max-width: 900px) {
    .mp-body { grid-template-columns: 1fr; margin-top: -24px; }
}

/* ── Cards ── */
.mp-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 20px;
}
.mp-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px 14px;
    border-bottom: 1px solid rgba(0,0,0,0.06);
}
.mp-card-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1a1a2e;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}
.mp-card-title .icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
}
.mp-edit-link {
    font-size: 0.8rem;
    color: #7c3aed;
    text-decoration: none;
    font-weight: 500;
    padding: 4px 10px;
    border-radius: 8px;
    border: 1px solid rgba(124,58,237,0.25);
    transition: all 0.2s;
}
.mp-edit-link:hover { background: rgba(124,58,237,0.06); }
.mp-card-body { padding: 20px 24px; }

/* ── Grid rows ── */
.mp-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px 24px;
}
@media (max-width: 580px) { .mp-grid { grid-template-columns: 1fr; } }
.mp-item label {
    display: block;
    font-size: 0.72rem;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 3px;
    font-weight: 500;
}
.mp-item .value {
    font-size: 0.92rem;
    color: #1f2937;
    font-weight: 500;
}
.mp-item .value.empty { color: #d1d5db; font-style: italic; font-weight: 400; }

/* About me */
.mp-about {
    font-size: 0.92rem;
    color: #374151;
    line-height: 1.75;
    background: #faf9f7;
    border-radius: 10px;
    padding: 16px 18px;
    border: 1px solid rgba(0,0,0,0.05);
    margin-bottom: 0;
}

/* Tags (languages, etc.) */
.mp-tags { display: flex; flex-wrap: wrap; gap: 8px; }
.mp-tag {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 500;
    background: #f3f0ff;
    color: #6d28d9;
    border: 1px solid #ede9fe;
}

/* Photo gallery */
.mp-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
    gap: 10px;
    padding: 16px 24px 20px;
}
.mp-gallery-item {
    aspect-ratio: 1;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
    border: 2px solid transparent;
}
.mp-gallery-item.primary { border-color: #d4a017; }
.mp-gallery-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
.mp-gallery-primary-badge {
    position: absolute;
    bottom: 4px;
    left: 50%;
    transform: translateX(-50%);
    background: #d4a017;
    color: #1a1033;
    font-size: 9px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    white-space: nowrap;
}
.mp-no-photo {
    grid-column: 1/-1;
    text-align: center;
    padding: 24px;
    color: #9ca3af;
    font-size: 0.85rem;
}

/* Completion card */
.mp-completion-bar-wrap {
    height: 8px;
    background: #f3f4f6;
    border-radius: 10px;
    overflow: hidden;
    margin: 10px 0 8px;
}
.mp-completion-bar-fill {
    height: 100%;
    border-radius: 10px;
    background: {{ $pctColor }};
    width: {{ $pct }}%;
    transition: width 0.6s ease;
}
.mp-completion-steps { display: flex; flex-direction: column; gap: 8px; margin-top: 16px; }
.mp-completion-step {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.82rem;
    padding: 8px 12px;
    border-radius: 8px;
    background: #fef9ee;
    border: 1px solid #fde68a;
    color: #92400e;
}
.mp-completion-step a {
    font-size: 0.78rem;
    color: #d97706;
    font-weight: 600;
    text-decoration: none;
}

/* Partner pref pills */
.mp-pref-row { margin-bottom: 14px; }
.mp-pref-row label {
    font-size: 0.72rem;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    display: block;
    margin-bottom: 5px;
}
.mp-pref-pills { display: flex; flex-wrap: wrap; gap: 6px; }
.mp-pref-pill {
    padding: 3px 10px;
    border-radius: 16px;
    font-size: 0.76rem;
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

/* Sidebar stats */
.mp-stat-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    padding: 16px 24px 20px;
}
.mp-stat {
    background: #faf9f7;
    border-radius: 10px;
    padding: 12px;
    text-align: center;
    border: 1px solid rgba(0,0,0,0.05);
}
.mp-stat .num { font-size: 1.4rem; font-weight: 700; color: #1a1a2e; line-height: 1; }
.mp-stat .lbl { font-size: 0.7rem; color: #9ca3af; margin-top: 4px; }

/* Privacy badges */
.mp-privacy-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
    font-size: 0.85rem;
}
.mp-privacy-item:last-child { border-bottom: none; }
.mp-privacy-item .lbl { color: #6b7280; }
.mp-privacy-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}
</style>

<div class="mp-page">

  {{-- ── HERO ── --}}
  <div class="mp-hero">
    <div class="container">
      <div class="mp-hero-inner">

        {{-- Photo --}}
        <div class="mp-photo-wrap">
          <div class="mp-photo-ring"></div>
          <img src="{{ $photoUrl }}" alt="{{ $user->name }}" class="mp-photo">
          <span class="mp-photo-pct">{{ $pct }}%</span>
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
              <span class="mp-badge verified">✓ Email Verified</span>
            @endif
            @if($user->isPremiumActive())
              <span class="mp-badge premium">★ Premium</span>
            @endif
            @if($profile->completion_percentage >= 100)
              <span class="mp-badge verified">✓ Profile Complete</span>
            @else
              <span class="mp-badge" style="background:rgba(212,160,23,0.12);border-color:rgba(212,160,23,0.35);color:#fde68a;">
                {{ $pct }}% Complete
              </span>
            @endif
          </div>
        </div>

        {{-- Actions --}}
        <div class="mp-hero-actions">
          <a href="{{ route('user.profile.edit') }}" class="btn btn-primary btn-sm">
            ✏️ Edit Profile
          </a>
          <a href="{{ route('user.profile.setup.show', 7) }}" class="btn btn-outline btn-sm"
             style="border-color:rgba(255,255,255,0.35);color:rgba(255,255,255,0.8);">
            📷 Manage Photos
          </a>
        </div>

      </div>
    </div>
  </div>

  {{-- ── BODY ── --}}
  <div class="mp-body">

    {{-- LEFT COLUMN --}}
    <div>

      {{-- About Me --}}
      @if($profile->about_me)
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#f3f0ff;">💬</span> About Me
          </h3>
          <a href="{{ route('user.profile.setup.show', 1) }}" class="mp-edit-link">Edit</a>
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
          <a href="{{ route('user.profile.setup.show', 1) }}" class="mp-edit-link">Edit</a>
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

            @if($profile->is_differently_abled)
            <div class="mp-item">
              <label>Differently Abled</label>
              <span class="value">Yes</span>
            </div>
            @endif

          </div>
        </div>
      </div>

      {{-- Religion & Community --}}
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#fef3c7;">🛕</span> Religion & Community
          </h3>
          <a href="{{ route('user.profile.setup.show', 2) }}" class="mp-edit-link">Edit</a>
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
              {{-- languages_known stores IDs; display names if loaded, else IDs --}}
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
          <a href="{{ route('user.profile.setup.show', 3) }}" class="mp-edit-link">Edit</a>
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
          <a href="{{ route('user.profile.setup.show', 4) }}" class="mp-edit-link">Edit</a>
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
          <a href="{{ route('user.profile.setup.show', 5) }}" class="mp-edit-link">Edit</a>
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

            @if($profile->area)
            <div class="mp-item">
              <label>Area</label>
              <span class="value">{{ $profile->area->name }}</span>
            </div>
            @endif

            <div class="mp-item">
              <label>Residency Status</label>
              <span class="value {{ !$profile->residency_status ? 'empty' : '' }}">
                {{ $fmt($profile->residency_status) ?? '—' }}
              </span>
            </div>

            @if($profile->citizenship)
            <div class="mp-item">
              <label>Citizenship</label>
              <span class="value">{{ $profile->citizenship }}</span>
            </div>
            @endif

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

            @if($profile->father_occupation)
            <div class="mp-item">
              <label>Father's Occupation</label>
              <span class="value">{{ $profile->father_occupation }}</span>
            </div>
            @endif

            @if($profile->mother_occupation)
            <div class="mp-item">
              <label>Mother's Occupation</label>
              <span class="value">{{ $profile->mother_occupation }}</span>
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

      {{-- Partner Preferences --}}
      @if($pref)
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#fdf2f8;">💑</span> Partner Preferences
          </h3>
          <a href="{{ route('user.profile.setup.show', 6) }}" class="mp-edit-link">Edit</a>
        </div>
        <div class="mp-card-body">

          <div class="mp-grid" style="margin-bottom:16px;">
            <div class="mp-item">
              <label>Age Range</label>
              <span class="value">{{ $pref->age_min }} – {{ $pref->age_max }} yrs</span>
            </div>

            @if($pref->height_min_cm && $pref->height_max_cm)
            <div class="mp-item">
              <label>Height Range</label>
              <span class="value">{{ $pref->height_min_cm }} – {{ $pref->height_max_cm }} cm</span>
            </div>
            @endif

            @if($pref->residency_status_pref)
            <div class="mp-item">
              <label>Residency</label>
              <span class="value">{{ $fmt($pref->residency_status_pref) }}</span>
            </div>
            @endif

            @if($pref->manglik_pref)
            <div class="mp-item">
              <label>Manglik</label>
              <span class="value">{{ $fmt($pref->manglik_pref) }}</span>
            </div>
            @endif
          </div>

          @if(!empty($pref->marital_status))
          <div class="mp-pref-row">
            <label>Marital Status</label>
            <div class="mp-pref-pills">
              @foreach($pref->marital_status as $s)
                <span class="mp-pref-pill">{{ $fmt($s) }}</span>
              @endforeach
            </div>
          </div>
          @endif

          @if(!empty($pref->diet))
          <div class="mp-pref-row">
            <label>Diet</label>
            <div class="mp-pref-pills">
              @foreach($pref->diet as $d)
                <span class="mp-pref-pill">{{ $fmt($d) }}</span>
              @endforeach
            </div>
          </div>
          @endif

          @if($pref->about_partner)
          <div style="margin-top:12px;">
            <label style="font-size:0.72rem;color:#9ca3af;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;display:block;">
              About Ideal Partner
            </label>
            <p class="mp-about" style="margin:0;">{{ $pref->about_partner }}</p>
          </div>
          @endif

        </div>
      </div>
      @else
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#fdf2f8;">💑</span> Partner Preferences
          </h3>
        </div>
        <div class="mp-card-body" style="text-align:center;padding:28px;">
          <p style="color:#9ca3af;font-size:0.9rem;margin:0 0 16px;">You haven't set your partner preferences yet.</p>
          <a href="{{ route('user.profile.setup.show', 6) }}" class="btn btn-primary btn-sm">Set Preferences</a>
        </div>
      </div>
      @endif

      {{-- Photos --}}
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#f0f9ff;">📷</span> Photos
          </h3>
          <a href="{{ route('user.profile.setup.show', 7) }}" class="mp-edit-link">Manage</a>
        </div>
        @if($user->photos->isNotEmpty())
          <div class="mp-gallery">
            @foreach($user->photos as $photo)
              <div class="mp-gallery-item {{ $photo->is_primary ? 'primary' : '' }}">
                <img src="{{ $photo->thumbnail_url ?? $photo->url }}" alt="Photo">
                @if($photo->is_primary)
                  <span class="mp-gallery-primary-badge">Primary</span>
                @endif
                @if(!$photo->is_approved)
                  <span style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,0.6);color:#fff;font-size:8px;padding:2px 5px;border-radius:6px;">Pending</span>
                @endif
              </div>
            @endforeach
          </div>
        @else
          <div class="mp-gallery">
            <div class="mp-no-photo">
              <div style="font-size:2rem;margin-bottom:8px;">📷</div>
              <p style="margin:0 0 12px;">No photos uploaded yet.</p>
              <a href="{{ route('user.profile.setup.show', 7) }}" class="btn btn-primary btn-sm">Upload Photos</a>
            </div>
          </div>
        @endif
      </div>

    </div>{{-- /left column --}}

    {{-- RIGHT COLUMN (sidebar) --}}
    <div>

      {{-- Completion card --}}
      <div class="mp-card" style="position:sticky;top:20px;">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#fef9ee;">⚡</span> Profile Strength
          </h3>
        </div>
        <div class="mp-card-body">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
            <span style="font-size:0.85rem;color:#374151;">Completion</span>
            <span style="font-size:0.9rem;font-weight:700;color:{{ $pctColor }};">{{ $pct }}%</span>
          </div>
          <div class="mp-completion-bar-wrap">
            <div class="mp-completion-bar-fill"></div>
          </div>
          <p style="font-size:0.78rem;color:#9ca3af;margin:6px 0 0;">
            @if($pct >= 100)
              Your profile is 100% complete. 🎉
            @elseif($pct >= 80)
              Almost there! A complete profile gets 5× more responses.
            @else
              Complete your profile to attract better matches.
            @endif
          </p>

          {{-- Missing steps --}}
          @php
            $missingStepMap = [
              1 => ['label' => 'Basic Information', 'check' => !$profile->height_cm],
              2 => ['label' => 'Religion & Community', 'check' => !$profile->religion_id],
              4 => ['label' => 'Education & Career', 'check' => !$profile->education_level_id],
              5 => ['label' => 'Location & Family', 'check' => !$profile->country_id],
              6 => ['label' => 'Partner Preferences', 'check' => !$pref],
              7 => ['label' => 'Upload Photos', 'check' => $user->photos->isEmpty()],
            ];
            $missing = array_filter($missingStepMap, fn($s) => $s['check']);
          @endphp

          @if(!empty($missing))
          <div class="mp-completion-steps">
            @foreach($missing as $stepNum => $info)
              <div class="mp-completion-step">
                <span>{{ $info['label'] }}</span>
                <a href="{{ route('user.profile.setup.show', $stepNum) }}">Fill →</a>
              </div>
            @endforeach
          </div>
          @endif
        </div>
      </div>

      {{-- Account details --}}
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#f0f4ff;">🔐</span> Account
          </h3>
        </div>
        <div class="mp-card-body" style="padding:14px 24px;">
          <div class="mp-privacy-item">
            <span class="lbl">Email</span>
            <span style="font-size:0.82rem;font-weight:500;color:#374151;">{{ $user->email }}</span>
          </div>
          <div class="mp-privacy-item">
            <span class="lbl">Phone</span>
            <span style="font-size:0.82rem;color:#374151;">{{ $user->phone ?? '—' }}</span>
          </div>
          <div class="mp-privacy-item">
            <span class="lbl">Gender</span>
            <span style="font-size:0.82rem;color:#374151;">{{ ucfirst($user->gender ?? '—') }}</span>
          </div>
          <div class="mp-privacy-item">
            <span class="lbl">Date of Birth</span>
            <span style="font-size:0.82rem;color:#374151;">{{ $user->date_of_birth?->format('d M Y') ?? '—' }}</span>
          </div>
          @if($user->activeSubscription)
          <div class="mp-privacy-item">
            <span class="lbl">Plan</span>
            <span class="mp-privacy-badge" style="background:#fef9c3;color:#854d0e;border-color:#fde68a;">Premium</span>
          </div>
          @endif
        </div>
      </div>

      {{-- Privacy settings --}}
      @if($profile->photo_privacy || $profile->contact_privacy || $profile->profile_visibility)
      <div class="mp-card">
        <div class="mp-card-header">
          <h3 class="mp-card-title">
            <span class="icon" style="background:#f0fdf4;">🛡️</span> Privacy
          </h3>
          <a href="{{ route('user.profile.setup.show', 7) }}" class="mp-edit-link">Edit</a>
        </div>
        <div class="mp-card-body" style="padding:14px 24px;">
          @if($profile->photo_privacy)
          <div class="mp-privacy-item">
            <span class="lbl">Photos visible to</span>
            <span class="mp-privacy-badge">{{ $fmt($profile->photo_privacy) }}</span>
          </div>
          @endif
          @if($profile->contact_privacy)
          <div class="mp-privacy-item">
            <span class="lbl">Contact visible to</span>
            <span class="mp-privacy-badge">{{ $fmt($profile->contact_privacy) }}</span>
          </div>
          @endif
          @if($profile->profile_visibility)
          <div class="mp-privacy-item">
            <span class="lbl">Profile visible to</span>
            <span class="mp-privacy-badge">{{ $fmt($profile->profile_visibility) }}</span>
          </div>
          @endif
        </div>
      </div>
      @endif

    </div>{{-- /right column --}}

  </div>{{-- /mp-body --}}

</div>{{-- /mp-page --}}

@endsection
