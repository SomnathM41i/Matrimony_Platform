@extends('user.layouts.app')

@section('title', 'Packages & Pricing — ' . config('app.name'))

@section('content')

{{-- ══════════════════════════════════════════════════════════════
    PAGE HERO
══════════════════════════════════════════════════════════════ --}}
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a><span>/</span>
            <a href="{{ route('user.dashboard') }}">Dashboard</a><span>/</span>
            <span>Packages</span>
        </div>
        <h1>Flexible Packages</h1>
        <p>Choose the plan that best suits your needs. Start free, upgrade anytime without hassle.</p>
    </div>
</section>

<style>
/* ── Page wrapper ──────────────────────────────────────────────── */
.pkg-page { background: var(--bg-light, #f7f4f0); padding-bottom: 80px; }

/* ── Flash ─────────────────────────────────────────────────────── */
.pkg-flash { margin-bottom: 24px; }

/* ── Active-plan banner ────────────────────────────────────────── */
.pkg-active-banner {
    background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
    color: #fff;
    border-radius: 16px;
    padding: 22px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 40px;
    box-shadow: 0 6px 24px rgba(124,58,237,.3);
}
.pkg-active-banner h3 { margin: 0 0 4px; font-size: 1.05rem; font-weight: 700; }
.pkg-active-banner p  { margin: 0; font-size: 0.88rem; opacity: .88; }
.pkg-active-badge {
    background: rgba(255,255,255,.2);
    border: 1.5px solid rgba(255,255,255,.4);
    color: #fff;
    border-radius: 50px;
    padding: 5px 16px;
    font-size: 0.78rem;
    font-weight: 700;
    white-space: nowrap;
}

/* ── Grid ──────────────────────────────────────────────────────── */
.pkg-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    max-width: 980px;
    margin: 0 auto 60px;
}

/* ── Card ──────────────────────────────────────────────────────── */
.pkg-card {
    background: #fff;
    border-radius: 20px;
    padding: 32px 28px 28px;
    border: 2px solid transparent;
    box-shadow: 0 2px 16px rgba(0,0,0,.06);
    transition: transform .25s, box-shadow .25s, border-color .25s;
    position: relative;
    display: flex;
    flex-direction: column;
}
.pkg-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 36px rgba(124,58,237,.12);
    border-color: rgba(124,58,237,.2);
}
.pkg-card.featured {
    border-color: #7c3aed;
    box-shadow: 0 8px 36px rgba(124,58,237,.22);
}
.pkg-card.is-current {
    border-color: #22c55e;
    box-shadow: 0 8px 36px rgba(34,197,94,.18);
}

/* top ribbon */
.pkg-ribbon {
    position: absolute;
    top: -1px; right: 24px;
    padding: 6px 16px;
    border-radius: 0 0 12px 12px;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: .05em;
    text-transform: uppercase;
}
.pkg-ribbon.popular  { background: #7c3aed; color: #fff; }
.pkg-ribbon.current  { background: #22c55e; color: #fff; }
.pkg-ribbon.vip      { background: linear-gradient(90deg,#d4a017,#f59e0b); color: #1a1033; }

/* card body */
.pkg-name     { font-size: 1.1rem; font-weight: 700; color: var(--text, #1a1033); margin-bottom: 6px; }
.pkg-price    { font-size: 2.6rem; font-weight: 800; color: var(--primary, #7c3aed); line-height: 1; margin: 10px 0 2px; }
.pkg-price sup{ font-size: 1.1rem; font-weight: 600; vertical-align: top; margin-top: 10px; display: inline-block; }
.pkg-duration { font-size: 0.8rem; color: var(--text-muted, #6b7280); margin-bottom: 22px; }
.pkg-price.free { color: #22c55e; }

/* features list */
.pkg-features { list-style: none; padding: 0; margin: 0 0 28px; flex: 1; display: flex; flex-direction: column; gap: 9px; }
.pkg-features li { display: flex; align-items: flex-start; gap: 10px; font-size: 0.87rem; color: var(--text, #374151); }
.pkg-features li .ck  { color: #22c55e; font-size: 1rem; flex-shrink: 0; margin-top: 1px; }
.pkg-features li .cx  { color: #d1d5db; font-size: 1rem; flex-shrink: 0; margin-top: 1px; }
.pkg-features li.muted { color: #9ca3af; }

/* CTA button */
.pkg-cta {
    display: block;
    width: 100%;
    text-align: center;
    padding: 13px 20px;
    border-radius: 12px;
    font-size: 0.92rem;
    font-weight: 700;
    cursor: pointer;
    transition: opacity .2s, transform .15s;
    text-decoration: none;
    border: none;
}
.pkg-cta:hover          { opacity: .88; transform: translateY(-1px); }
.pkg-cta.outline        { border: 2px solid var(--primary, #7c3aed); color: var(--primary, #7c3aed); background: transparent; }
.pkg-cta.primary        { background: var(--primary, #7c3aed); color: #fff; }
.pkg-cta.gold           { background: linear-gradient(135deg, #d4a017, #f59e0b); color: #1a1033; }
.pkg-cta.disabled       { opacity: .55; cursor: not-allowed; pointer-events: none; background: #e5e7eb; color: #6b7280; }
.pkg-note { font-size: 0.75rem; color: var(--text-muted, #9ca3af); text-align: center; margin-top: 10px; }

/* ── Compare table ─────────────────────────────────────────────── */
.pkg-compare-wrap { overflow-x: auto; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,.07); margin-bottom: 60px; }
.pkg-compare { width: 100%; border-collapse: collapse; background: #fff; }
.pkg-compare th, .pkg-compare td { padding: 13px 18px; border-bottom: 1px solid #f3e8ff; font-size: 0.87rem; text-align: center; }
.pkg-compare th { background: #7c3aed; color: #fff; font-weight: 700; }
.pkg-compare th:first-child, .pkg-compare td:first-child { text-align: left; font-weight: 500; color: var(--text, #374151); }
.pkg-compare tbody tr:last-child td { border-bottom: none; font-weight: 700; }
.pkg-compare .yes { color: #22c55e; font-size: 1.1rem; }
.pkg-compare .no  { color: #d1d5db; font-size: 1.1rem; }
.pkg-compare tbody tr:hover td { background: #faf5ff; }

/* ── FAQ ────────────────────────────────────────────────────────── */
.pkg-faq-item { border: 1.5px solid #e5e7eb; border-radius: 12px; overflow: hidden; margin-bottom: 10px; }
.pkg-faq-q {
    width: 100%; background: #fff; border: none; cursor: pointer;
    display: flex; justify-content: space-between; align-items: center;
    padding: 16px 20px; font-size: 0.92rem; font-weight: 600; color: var(--text, #1a1033);
    transition: background .15s;
}
.pkg-faq-q:hover { background: #faf5ff; }
.pkg-faq-icon { font-size: 1.2rem; color: #7c3aed; transition: transform .25s; }
.pkg-faq-q[aria-expanded="true"] .pkg-faq-icon { transform: rotate(45deg); }
.pkg-faq-body { display: none; padding: 0 20px 16px; font-size: 0.87rem; color: #6b7280; line-height: 1.7; }

/* ── Refund policy ─────────────────────────────────────────────── */
.pkg-policy-item { display: flex; gap: 14px; align-items: flex-start; }
.pkg-policy-dot  { width: 8px; height: 8px; border-radius: 50%; background: var(--primary, #7c3aed); flex-shrink: 0; margin-top: 7px; }
</style>

<div class="pkg-page">
<div class="container section">

    {{-- ── Flash messages ─────────────────────────────────────────────── --}}
    <div class="pkg-flash">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">❌ {{ session('error') }}</div>
        @endif
    </div>

    {{-- ── Active subscription banner ──────────────────────────────────── --}}
    @if($activeSubscription && $activeSubscription->isValid())
        @php $ap = $activeSubscription->package; @endphp
        <div class="pkg-active-banner">
            <div>
                <h3>🎉 You're on the <strong>{{ $ap->name }}</strong></h3>
                <p>
                    Active until {{ $activeSubscription->expires_at->format('d M Y') }}
                    ({{ $activeSubscription->expires_at->diffForHumans() }})
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <span class="pkg-active-badge">✓ Active</span>
                <a href="{{ route('user.subscription.show') }}"
                   class="pkg-cta outline"
                   style="width:auto;padding:8px 20px;border-color:rgba(255,255,255,.6);color:#fff;">
                    Manage Subscription
                </a>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
        PRICING PLANS
    ══════════════════════════════════════════════════════════════ --}}
    <div class="section-header text-center" style="margin-bottom:36px;">
        <div class="badge">Pricing Plans</div>
        <h2>Choose Your Perfect Plan</h2>
        <p>All plans include community-focused features. Premium plans unlock more connections.</p>
        <div class="divider"></div>
    </div>

    <div class="pkg-grid">
        @foreach($packages as $pkg)
            @php
                $isCurrent = $activeSubscription?->isValid()
                    && $activeSubscription->subscription_package_id === $pkg->id;
                $isFree    = $pkg->isFree();
                $isVip     = $pkg->highlight_profile || $pkg->rm_assistance;

                // Duration label
                if ($isFree) {
                    $durationLabel = 'Free Forever';
                } elseif ($pkg->duration_days >= 360) {
                    $months = round($pkg->duration_days / 30);
                    $perMonth = number_format($pkg->price / $months);
                    $durationLabel = "Per {$months} Months (₹{$perMonth}/month)";
                } elseif ($pkg->duration_days >= 28) {
                    $months = round($pkg->duration_days / 30);
                    $perMonth = number_format($pkg->price / max($months,1));
                    $durationLabel = "Per {$months} Month" . ($months > 1 ? 's' : '') . " (₹{$perMonth}/month)";
                } else {
                    $durationLabel = "Per {$pkg->duration_days} Days";
                }

                // Feature rows: key → label
                $featureMap = [
                    'Unlimited Profile Views'      => !$isFree,
                    'Direct Messaging'             => $pkg->messages_limit > 0 || ($pkg->messages_limit == 0 && !$isFree),
                    'Contact Details Access'       => $pkg->can_see_contact,
                    'Advanced Search Filters'      => $pkg->priority_in_search,
                    'Priority Listing in Search'   => $pkg->priority_in_search,
                    'Profile Highlight Badge'      => $pkg->highlight_profile,
                    'Full Horoscope Matching'      => $pkg->can_see_full_horoscope,
                    'WhatsApp Support'             => $pkg->whatsapp_support,
                    'Relationship Manager'         => $pkg->rm_assistance,
                ];

                $photoLabel = match(true) {
                    $isFree                     => '1 Photo Upload',
                    $pkg->photo_gallery_limit <= 0  => 'Unlimited Photos',
                    default                     => "Up to {$pkg->photo_gallery_limit} Photos",
                };
            @endphp

            <div class="pkg-card {{ $pkg->is_featured ? 'featured' : '' }} {{ $isCurrent ? 'is-current' : '' }}">

                {{-- Ribbon --}}
                @if($isCurrent)
                    <div class="pkg-ribbon current">Your Plan</div>
                @elseif($pkg->is_featured)
                    <div class="pkg-ribbon popular">Most Popular</div>
                @elseif($isVip)
                    <div class="pkg-ribbon vip">VIP</div>
                @endif

                <div class="pkg-name">{{ $pkg->name }}</div>

                <div class="pkg-price {{ $isFree ? 'free' : '' }}">
                    @if(!$isFree)<sup>₹</sup>@endif
                    {{ $isFree ? 'Free' : number_format($pkg->price, 0) }}
                </div>
                <div class="pkg-duration">{{ $durationLabel }}</div>

                <ul class="pkg-features">
                    {{-- Always-on --}}
                    <li><span class="ck">✓</span> Create &amp; manage your profile</li>
                    <li><span class="ck">✓</span> Basic search filters</li>
                    <li><span class="ck">✓</span> Express interest</li>
                    <li><span class="ck">✓</span> {{ $photoLabel }}</li>

                    {{-- Dynamic features --}}
                    @foreach($featureMap as $label => $enabled)
                        <li class="{{ $enabled ? '' : 'muted' }}">
                            <span class="{{ $enabled ? 'ck' : 'cx' }}">{{ $enabled ? '✓' : '✗' }}</span>
                            {{ $label }}
                        </li>
                    @endforeach

                    {{-- Extra features from JSON --}}
                    @if($pkg->extra_features)
                        @foreach($pkg->extra_features as $extra)
                            <li><span class="ck">✓</span> {{ $extra }}</li>
                        @endforeach
                    @endif
                </ul>

                {{-- CTA button --}}
                @if($isCurrent)
                    <span class="pkg-cta disabled">✓ Current Plan</span>
                @elseif($isFree)
                    @guest
                        <a href="{{ route('user.register') }}" class="pkg-cta outline">Get Started Free</a>
                    @else
                        <span class="pkg-cta disabled">Basic (Default)</span>
                    @endguest
                @else
                    {{-- Phase 6: payment not yet implemented — link to show intent --}}
                    <button
                        class="pkg-cta {{ $isVip ? 'gold' : 'primary' }}"
                        data-plan-id="{{ $pkg->id }}"
                        data-plan-name="{{ $pkg->name }}"
                        onclick="handleBuyPackage(this)">
                        {{ $isVip ? '⭐ Buy ' . $pkg->name : 'Buy ' . $pkg->name . ' →' }}
                    </button>
                @endif

                <p class="pkg-note">
                    @if($isFree) No credit card required
                    @else Secure payment • Cancel anytime
                    @endif
                </p>
            </div>
        @endforeach
    </div>


    {{-- ══════════════════════════════════════════════════════════════
        COMPARISON TABLE
    ══════════════════════════════════════════════════════════════ --}}
    <div class="section-header text-center" style="margin-bottom:28px;">
        <div class="badge">Side by Side</div>
        <h2>Plan Comparison</h2>
        <p>Compare all features across plans to choose what's right for you.</p>
        <div class="divider"></div>
    </div>

    <div class="pkg-compare-wrap" style="margin-bottom:60px;">
        <table class="pkg-compare">
            <thead>
                <tr>
                    <th style="text-align:left;width:38%;">Feature</th>
                    @foreach($packages as $pkg)
                        <th>{{ $pkg->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Profile Creation</td>
                    @foreach($packages as $pkg)<td class="yes">✓</td>@endforeach
                </tr>
                <tr>
                    <td>Profile Views / Month</td>
                    @foreach($packages as $pkg)
                        <td>{{ $pkg->isFree() ? '10' : 'Unlimited' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td>Photo Upload</td>
                    @foreach($packages as $pkg)
                        <td>
                            @if($pkg->isFree()) 1 Photo
                            @elseif($pkg->photo_gallery_limit <= 0) Unlimited
                            @else Up to {{ $pkg->photo_gallery_limit }}
                            @endif
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td>Basic Search</td>
                    @foreach($packages as $pkg)<td class="yes">✓</td>@endforeach
                </tr>
                <tr>
                    <td>Advanced Filters</td>
                    @foreach($packages as $pkg)
                        <td class="{{ $pkg->priority_in_search ? 'yes' : 'no' }}">
                            {{ $pkg->priority_in_search ? '✓' : '✗' }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td>Express Interest</td>
                    @foreach($packages as $pkg)<td class="yes">✓</td>@endforeach
                </tr>
                <tr>
                    <td>Direct Messaging</td>
                    @foreach($packages as $pkg)
                        @php $hasMsg = !$pkg->isFree() && ($pkg->messages_limit == 0 || $pkg->messages_limit > 0); @endphp
                        <td class="{{ $hasMsg ? 'yes' : 'no' }}">
                            {{ $hasMsg ? ($pkg->messages_limit > 0 ? $pkg->messages_limit . ' msgs' : '✓') : '✗' }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td>Contact Details Access</td>
                    @foreach($packages as $pkg)
                        <td class="{{ $pkg->can_see_contact ? 'yes' : 'no' }}">
                            {{ $pkg->can_see_contact ? '✓' : '✗' }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td>Priority Listing</td>
                    @foreach($packages as $pkg)
                        <td class="{{ $pkg->priority_in_search ? 'yes' : 'no' }}">
                            {{ $pkg->priority_in_search ? '✓' : '✗' }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td>Profile Highlight</td>
                    @foreach($packages as $pkg)
                        <td class="{{ $pkg->highlight_profile ? 'yes' : 'no' }}">
                            {{ $pkg->highlight_profile ? '✓' : '✗' }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td>Horoscope Matching</td>
                    @foreach($packages as $pkg)
                        <td class="{{ $pkg->can_see_full_horoscope ? 'yes' : 'no' }}">
                            {{ $pkg->can_see_full_horoscope ? '✓' : '✗' }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td>Support</td>
                    @foreach($packages as $pkg)
                        <td>
                            @if($pkg->whatsapp_support) ✓ WhatsApp
                            @elseif(!$pkg->isFree()) Email Only
                            @else ✗
                            @endif
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td>Relationship Manager</td>
                    @foreach($packages as $pkg)
                        <td class="{{ $pkg->rm_assistance ? 'yes' : 'no' }}">
                            {{ $pkg->rm_assistance ? '✓' : '✗' }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td style="font-weight:700;color:var(--text);">Price</td>
                    @foreach($packages as $pkg)
                        <td style="font-weight:700;color:{{ $pkg->highlight_profile ? 'var(--gold,#d4a017)' : 'var(--primary)' }};">
                            {{ $pkg->isFree() ? 'Free' : '₹' . number_format($pkg->price, 0) }}
                            @if(!$pkg->isFree())
                                <br><small style="font-weight:400;font-size:0.75rem;">
                                    / {{ $pkg->duration_days >= 28 ? round($pkg->duration_days / 30) . ' months' : $pkg->duration_days . ' days' }}
                                </small>
                            @endif
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
        FAQ
    ══════════════════════════════════════════════════════════════ --}}
    <div class="section-header text-center" style="margin-bottom:28px;">
        <div class="badge">Help</div>
        <h2>Frequently Asked Questions</h2>
        <p>Have questions about our plans? We have answers.</p>
        <div class="divider"></div>
    </div>

    <div style="max-width:760px;margin:0 auto 60px;">
        @php
        $faqs = [
            ['q' => 'Can I upgrade my plan anytime?',
             'a' => 'Yes, you can upgrade at any time. Your new plan benefits activate immediately upon payment. The remaining days from your previous plan will not be refunded, but you will enjoy the upgraded features right away.'],
            ['q' => 'Is my personal information safe?',
             'a' => 'Absolutely. We take data privacy seriously. Your contact information is only shared with mutual matches and never sold to third parties. You can control your privacy settings from your account dashboard.'],
            ['q' => 'How are profiles verified?',
             'a' => 'Our team manually reviews each registration. We verify profiles through mobile OTP, email verification, and optional ID document submission. Verified profiles receive a trust badge visible to all members.'],
            ['q' => 'What payment methods are accepted?',
             'a' => 'We accept UPI, Net Banking, Debit/Credit Cards (Visa, Mastercard, Rupay), and popular wallets like Paytm and PhonePe. All payments are processed through a secure payment gateway.'],
            ['q' => 'Can I cancel my subscription?',
             'a' => 'You can cancel at any time. Your premium access continues until the end of your billing period. Prorated refunds are not offered except in special circumstances.'],
            ['q' => 'What if I don\'t find a match?',
             'a' => 'Our dedicated support team will help you optimise your profile and search criteria. VIP members get personalised assistance from a relationship manager. We continuously add new verified profiles to increase your chances.'],
        ];
        @endphp

        @foreach($faqs as $i => $faq)
            <div class="pkg-faq-item">
                <button class="pkg-faq-q" aria-expanded="false" aria-controls="faq-{{ $i }}">
                    {{ $faq['q'] }}
                    <span class="pkg-faq-icon" aria-hidden="true">+</span>
                </button>
                <div class="pkg-faq-body" id="faq-{{ $i }}">
                    {{ $faq['a'] }}
                </div>
            </div>
        @endforeach
    </div>


    {{-- ══════════════════════════════════════════════════════════════
        REFUND POLICY
    ══════════════════════════════════════════════════════════════ --}}
    <div class="section-header text-center" style="margin-bottom:28px;">
        <div class="badge">Policy</div>
        <h2>Refund Policy</h2>
        <div class="divider"></div>
    </div>

    <div style="max-width:760px;margin:0 auto;background:#fff;border-radius:16px;padding:36px;border:1px solid var(--border);display:flex;flex-direction:column;gap:18px;">
        @php
        $policies = [
            ['title' => '7-Day Refund Window',
             'body'  => 'If you are unsatisfied with your Premium or VIP subscription, you may request a full refund within 7 days of purchase by contacting our support team.'],
            ['title' => 'No Refund After 7 Days',
             'body'  => 'Refunds will not be issued after 7 days from the date of purchase, as services would have been partially or fully utilised.'],
            ['title' => 'Process',
             'body'  => 'Approved refunds will be credited to the original payment method within 5–7 business days.'],
            ['title' => 'Contact for Refund',
             'body'  => 'Email refund@' . str_replace(['https://', 'http://', 'www.'], '', config('app.url', 'matrimony.com')) . ' with your registered email and order ID.'],
        ];
        @endphp
        @foreach($policies as $policy)
            <div class="pkg-policy-item">
                <div class="pkg-policy-dot"></div>
                <p style="margin:0;font-size:0.9rem;color:var(--text-muted,#6b7280);line-height:1.7;">
                    <strong style="color:var(--text);">{{ $policy['title'] }}:</strong>
                    {{ $policy['body'] }}
                </p>
            </div>
        @endforeach
    </div>

</div><!-- /.container -->
</div><!-- /.pkg-page -->

{{-- ── Payment coming soon modal ──────────────────────────────────────── --}}
<div id="pkg-modal-overlay"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:20px;padding:36px 32px;max-width:420px;width:90%;text-align:center;box-shadow:0 16px 48px rgba(0,0,0,.2);">
        <div style="font-size:2.8rem;margin-bottom:12px;">🚀</div>
        <h3 id="pkg-modal-title" style="margin:0 0 10px;font-size:1.15rem;">Buy Plan</h3>
        <p style="font-size:0.9rem;color:#6b7280;margin:0 0 24px;line-height:1.6;">
            Online payment is coming soon! To subscribe now, please contact our support team and we will help you get set up.
        </p>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <a href="mailto:support@{{ str_replace(['https://','http://','www.'],'',config('app.url','matrimony.com')) }}"
               class="pkg-cta primary" style="text-decoration:none;">
                📧 Contact Support
            </a>
            <button onclick="closeModal()" class="pkg-cta outline">Close</button>
        </div>
    </div>
</div>

<script>
/* FAQ accordion */
document.querySelectorAll('.pkg-faq-q').forEach(btn => {
    btn.addEventListener('click', () => {
        const body     = document.getElementById(btn.getAttribute('aria-controls'));
        const expanded = btn.getAttribute('aria-expanded') === 'true';
        // close all
        document.querySelectorAll('.pkg-faq-q').forEach(b => {
            b.setAttribute('aria-expanded', 'false');
            const bd = document.getElementById(b.getAttribute('aria-controls'));
            if (bd) bd.style.display = 'none';
        });
        // toggle current
        if (!expanded) {
            btn.setAttribute('aria-expanded', 'true');
            body.style.display = 'block';
        }
    });
});

/* Buy package — Phase 6 stub */
function handleBuyPackage(btn) {
    const name = btn.getAttribute('data-plan-name');
    document.getElementById('pkg-modal-title').textContent = 'Buy ' + name;
    const overlay = document.getElementById('pkg-modal-overlay');
    overlay.style.display = 'flex';
}
function closeModal() {
    document.getElementById('pkg-modal-overlay').style.display = 'none';
}
document.getElementById('pkg-modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

@endsection