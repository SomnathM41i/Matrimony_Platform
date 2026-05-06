@extends('user.layouts.app')

@section('title', "Express Matrimony - Find Your Perfect Life Partner")

@section('content')

{{-- =====================================================
     EXTRA PAGE-LEVEL STYLES
     (All --variables, .btn, .card, .form-control etc.
      come from the main app.css you already have.)
====================================================== --}}
<style>

/* ── HERO ───────────────────────────────────────────── */
.hero {
    position: relative;
    min-height: 92vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: #0D0810;
}

.hero-bg-img {
    position: absolute; inset: 0;
    background-image: url('{{ asset('assets/images/home.png') }}');
    background-size: cover;
    background-position: center 30%;
}
.hero-bg-img::after {
    content: '';
    position: absolute; inset: 0;
    background:
        linear-gradient(to right,
            rgba(13,8,16,.96) 0%,
            rgba(13, 8, 16, 0) 33%,
            rgba(13,8,16,.38) 100%),
        linear-gradient(to top,
            rgba(139,0,0,.40) 0%,
            transparent 55%);
}

/* Decorative mandala SVG ring */
.hero-mandala {
    position: absolute;
    right: -60px; top: 50%;
    transform: translateY(-50%);
    width: 560px; height: 560px;
    opacity: .07;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='100' cy='100' r='98' fill='none' stroke='%23D4A017' stroke-width='1'/%3E%3Ccircle cx='100' cy='100' r='78' fill='none' stroke='%23D4A017' stroke-width='.5'/%3E%3Ccircle cx='100' cy='100' r='58' fill='none' stroke='%23C41E3A' stroke-width='1'/%3E%3Ccircle cx='100' cy='100' r='38' fill='none' stroke='%23D4A017' stroke-width='.5'/%3E%3Cpath d='M100 2 L102 98 L198 100 L102 102 L100 198 L98 102 L2 100 L98 98Z' fill='%23D4A017' opacity='.55'/%3E%3C/svg%3E");
    background-size: contain;
    background-repeat: no-repeat;
    pointer-events: none;
}

.hero-floral { position:absolute; z-index:1; opacity:.16; pointer-events:none; font-size:3.6rem; }
.hero-floral.f1 { top:9%;  right:7%;  }
.hero-floral.f2 { bottom:16%; right:22%; font-size:2.2rem; }
.hero-floral.f3 { top:58%; right:4%;  font-size:1.9rem; }

.hero > .container { position:relative; z-index:2; }

/* ── SEARCH SECTION ─────────────────────────────────── */
.search-outer {
    background: var(--bg-light);
    padding: 72px 0 60px;
    position: relative; z-index: 10;
}

/* ── FEATURES — tall image cards ────────────────────── */
.feat-img-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
}

.feat-img-card {
    border-radius: 18px;
    overflow: hidden;
    aspect-ratio: 3/4;
    position: relative;
    box-shadow: 0 8px 32px rgba(196,30,58,.14);
}
.feat-img-card img {
    width:100%; height:100%;
    object-fit: cover;
    transition: transform .55s ease;
}
.feat-img-card:hover img { transform: scale(1.07); }

.feat-img-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top,
        rgba(139,0,0,.93) 0%,
        rgba(26,10,10,.28) 55%,
        transparent 100%);
    display: flex; flex-direction: column;
    justify-content: flex-end;
    padding: 26px 22px;
}
.feat-img-icon {
    width:50px; height:50px;
    background: rgba(212,160,23,.22);
    border: 1.5px solid rgba(212,160,23,.55);
    border-radius: 50%;
    display:flex; align-items:center; justify-content:center;
    font-size:1.5rem;
    margin-bottom:10px;
    backdrop-filter: blur(4px);
}
.feat-img-overlay h3 { color:#fff; font-size:1.15rem; margin-bottom:6px; }
.feat-img-overlay p  { color:rgba(255,255,255,.72); font-size:.82rem; line-height:1.55; }

/* ── HOW IT WORKS — circular photos ─────────────────── */
.hiw-section { background: linear-gradient(135deg, var(--bg-light), #FFF0F0); }

.steps-visual-grid {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 24px;
    position: relative;
}
.steps-visual-grid::before {
    content:'';
    position:absolute;
    top:88px; left:13%; right:13%;
    height:2px;
    background: linear-gradient(to right,
        transparent, var(--gold) 20%, var(--primary) 50%, var(--gold) 80%, transparent);
    z-index:0;
}

.step-visual-card { text-align:center; position:relative; z-index:1; padding:0 12px; }

.step-circle-wrap {
    width:170px; height:170px;
    border-radius:50%; overflow:hidden;
    border:4px solid var(--gold);
    margin:0 auto 18px;
    box-shadow: 0 8px 32px rgba(196,30,58,.2);
    position:relative;
}
.step-circle-wrap img { width:100%; height:100%; object-fit:cover; }

.step-badge {
    position:absolute; top:-4px; right:-4px;
    width:36px; height:36px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color:#fff;
    border-radius:50%;
    border:2px solid #fff;
    display:flex; align-items:center; justify-content:center;
    font-family: var(--font-display);
    font-size:.95rem; font-weight:700;
    box-shadow: 0 4px 12px rgba(196,30,58,.4);
}

.step-visual-card h3 { color:var(--text); font-size:1.1rem; margin-bottom:8px; }
.step-visual-card p  { font-size:.87rem; color:var(--text-muted); line-height:1.6; }

/* ── SUCCESS STORIES ─────────────────────────────────── */
.stories-grid {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 28px;
}

.story-card {
    background:#fff;
    border-radius:18px; overflow:hidden;
    box-shadow: 0 4px 24px rgba(196,30,58,.08);
    border:1px solid var(--border);
    transition: all .3s;
}
.story-card:hover { transform:translateY(-6px); box-shadow: 0 16px 40px rgba(196,30,58,.16); }

.story-photo { position:relative; height:240px; overflow:hidden; }
.story-photo img { width:100%; height:100%; object-fit:cover; transition:transform .5s; }
.story-card:hover .story-photo img { transform:scale(1.06); }

.story-ribbon {
    position:absolute; bottom:14px; left:14px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color:#fff;
    font-size:.7rem; font-weight:700;
    letter-spacing:.07em; text-transform:uppercase;
    padding:5px 13px; border-radius:50px;
}

.story-body { padding:22px 24px; text-align:left; }
.story-names {
    font-family: var(--font-display);
    font-size:1.15rem; font-weight:600;
    color:var(--text); margin-bottom:4px;
}
.story-location { font-size:.78rem; color:var(--primary); font-weight:600; margin-bottom:12px; }
.story-quote { font-size:.88rem; color:var(--text-muted); font-style:italic; line-height:1.7; }
.story-quote::before {
    content:'\201C';
    color:var(--gold);
    font-size:1.5rem;
    font-family: var(--font-display);
    line-height:.8;
}

/* ── PLANS ────────────────────────────────────────────── */
.packages-section { background: var(--bg-light); }

.packages-grid {
    display:grid;
    grid-template-columns: repeat(3,1fr);
    gap:28px;
    align-items:start;
}

.package-card {
    background:#fff; border-radius:20px;
    overflow:hidden;
    border:2px solid var(--border);
    transition:all .3s; position:relative;
}
.package-card:hover { transform:translateY(-6px); box-shadow: 0 20px 60px rgba(196,30,58,.16); }
.package-card.featured {
    border-color:var(--primary);
    box-shadow: 0 8px 32px rgba(196,30,58,.2);
    transform:scale(1.02);
}
.package-card.featured:hover { transform:scale(1.02) translateY(-6px); }

.pkg-img { height:190px; overflow:hidden; position:relative; }
.pkg-img img { width:100%; height:100%; object-fit:cover; transition:transform .5s; }
.package-card:hover .pkg-img img { transform:scale(1.06); }

.package-badge {
    position:absolute; top:14px; right:-26px;
    background:linear-gradient(135deg, var(--gold), var(--gold-light));
    color:var(--text);
    font-size:.68rem; font-weight:700;
    letter-spacing:.08em; text-transform:uppercase;
    padding:5px 38px;
    transform:rotate(45deg);
    z-index:2;
}

.pkg-body { padding:26px 28px 30px; text-align:center; }
.package-name { font-size:.75rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--primary); margin-bottom:6px; }
.package-price { font-family:var(--font-display); font-size:2.4rem; font-weight:700; color:var(--text); line-height:1; margin-bottom:4px; }
.package-price sup { font-size:1.1rem; vertical-align:super; }
.package-duration { font-size:.82rem; color:var(--text-muted); margin-bottom:22px; }

.package-features { list-style:none; text-align:left; margin-bottom:26px; }
.package-features li {
    display:flex; align-items:center; gap:10px;
    padding:8px 0;
    font-size:.88rem; color:var(--text-muted);
    border-bottom:1px solid var(--bg-soft);
}
.package-features li:last-child { border-bottom:none; }
.check { color:var(--primary); font-weight:700; flex-shrink:0; }
.cross { color:var(--text-light); flex-shrink:0; }

/* ── GALLERY STRIP ────────────────────────────────────── */
.gallery-strip {
    display:flex; height:260px; overflow:hidden;
}
.gallery-strip img {
    flex:1; object-fit:cover;
    filter:saturate(.8) brightness(.9);
    transition:flex .5s ease, filter .4s;
    cursor:pointer;
}
.gallery-strip img:hover {
    flex:4;
    filter:saturate(1.15) brightness(1.02);
}

/* ── CTA SECTION ─────────────────────────────────────── */
.cta-section {
    position:relative; overflow:hidden;
    min-height:400px; display:flex; align-items:center;
}
.cta-bg {
    position:absolute; inset:0;
    background-image: url('{{ asset('assets/images/card8.jpg') }}');
    background-size:cover; background-position:center;
}
.cta-bg::after {
    content:''; position:absolute; inset:0;
    background:linear-gradient(135deg,
        rgba(139,0,0,.88) 0%,
        rgba(0, 0, 0, 0) 50%);
}
.cta-content {
    position:relative; z-index:1;
    text-align:center; width:100%;
    padding:80px 24px; color:#fff;
}
.cta-content h2 { color:#fff; margin-bottom:14px; }
.cta-content p  { color:rgba(255,255,255,.78); max-width:520px; margin:0 auto 34px; font-size:1.05rem; line-height:1.8; }

/* ── RESPONSIVE ──────────────────────────────────────── */
@media(max-width:1024px){
    .feat-img-grid  { grid-template-columns:repeat(2,1fr); }
    .packages-grid  { grid-template-columns:repeat(2,1fr); }
}
@media(max-width:768px){
    .steps-visual-grid { grid-template-columns:repeat(2,1fr); gap:32px; }
    .steps-visual-grid::before { display:none; }
    .stories-grid  { grid-template-columns:1fr; }
    .packages-grid { grid-template-columns:1fr; }
    .package-card.featured { transform:none; }
    .gallery-strip { height:160px; }
}
@media(max-width:480px){
    .feat-img-grid { grid-template-columns:1fr 1fr; }
    .steps-visual-grid { grid-template-columns:1fr; }
    .hero-floral { display:none; }
    .hero-actions { flex-direction:column; }
}
</style>

{{-- ═══════════════════════════════════════════════════
     HERO — Indian bride background + golden text
════════════════════════════════════════════════════ --}}
<section class="hero">
    <div class="hero-bg-img"></div>
    <div class="hero-mandala"></div>

    <span class="hero-floral f1">🌸</span>
    <span class="hero-floral f2">🏵️</span>
    <span class="hero-floral f3">✨</span>

    <div class="container">
        <div class="hero-content animate-fade-up">
            <div class="hero-badge">🙏 Trusted by Thousands of Indian Families</div>

            <h1>
                Find Your Perfect<br>
                <span>Life Partner</span>
            </h1>

            <p>
                A modern matrimonial platform rooted in Indian values —
                connecting individuals and families with trust, compatibility,
                and meaningful relationships across every community.
            </p>

            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Register Free</a>
                <a href="#profiles" class="btn btn-gold btn-lg">Browse Profiles</a>
            </div>

            <div class="hero-stats">
                <div>
                    <div class="hero-stat-num">50K+</div>
                    <div class="hero-stat-label">Members</div>
                </div>
                <div>
                    <div class="hero-stat-num">12K+</div>
                    <div class="hero-stat-label">Verified Profiles</div>
                </div>
                <div>
                    <div class="hero-stat-num">3,000+</div>
                    <div class="hero-stat-label">Successful Weddings</div>
                </div>
                <div>
                    <div class="hero-stat-num">20+</div>
                    <div class="hero-stat-label">Communities</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     QUICK SEARCH
════════════════════════════════════════════════════ --}}
<section class="search-outer">
    <div class="container">
        <div class="quick-search">
            <h3>Search Your Match</h3>

            <form id="searchForm">
                <div class="search-grid">
                    <div class="form-group">
                        <label class="form-label">Looking For</label>
                        <select class="form-control">
                            <option>Bride</option>
                            <option>Groom</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Age Range</label>
                        <select class="form-control">
                            <option>21–25</option>
                            <option>26–30</option>
                            <option>31–35</option>
                            <option>36–40</option>
                            <option>Any</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Religion</label>
                        <select class="form-control">
                            <option value="">Any</option>
                            <option>Hindu</option>
                            <option>Muslim</option>
                            <option>Christian</option>
                            <option>Sikh</option>
                            <option>Jain</option>
                            <option>Buddhist</option>
                            <option>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mother Tongue</label>
                        <select class="form-control">
                            <option>Any</option>
                            <option>Hindi</option>
                            <option>Marathi</option>
                            <option>Bengali</option>
                            <option>Tamil</option>
                            <option>Telugu</option>
                            <option>Gujarati</option>
                            <option>Punjabi</option>
                            <option>Kannada</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">State</label>
                        <select class="form-control">
                            <option>Any</option>
                            <option>Maharashtra</option>
                            <option>Delhi NCR</option>
                            <option>Karnataka</option>
                            <option>Gujarat</option>
                            <option>West Bengal</option>
                            <option>Tamil Nadu</option>
                            <option>Punjab</option>
                            <option>Rajasthan</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" style="height:52px; border-radius:10px;">
                        Search Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     FEATURES — tall Indian image cards
════════════════════════════════════════════════════ --}}
<section class="section" style="background:#fff;">
    <div class="container text-center">
        <div class="section-header">
            <div class="badge">Why Choose Us</div>
            <h2>Everything You Need</h2>
            <p>Find your perfect match with trust and confidence</p>
            <div class="divider"></div>
        </div>

        <div class="feat-img-grid">

            <div class="feat-img-card">
                <img src="{{ asset('assets/images/card1.jpg') }}"
                     alt="Verified Indian bride" loading="lazy">
                <div class="feat-img-overlay">
                    <div class="feat-img-icon">✅</div>
                    <h3>Verified Profiles</h3>
                    <p>Every profile is manually verified for authenticity and family trust.</p>
                </div>
            </div>

            <div class="feat-img-card">
                <img src="{{ asset('assets/images/card2.webp') }}"
                     alt="Secure platform" loading="lazy">
                <div class="feat-img-overlay">
                    <div class="feat-img-icon">🔒</div>
                    <h3>100% Secure</h3>
                    <p>Your data and private conversations are fully protected at all times.</p>
                </div>
            </div>

            <div class="feat-img-card">
                <img src="{{ asset('assets/images/card3.jpg') }}"
                     alt="Smart matching" loading="lazy">
                <div class="feat-img-overlay">
                    <div class="feat-img-icon">💡</div>
                    <h3>Smart Matching</h3>
                    <p>AI-driven suggestions tuned to Indian community preferences.</p>
                </div>
            </div>

            <div class="feat-img-card">
                <img src="{{ asset('assets/images/card4.jpg') }}"
                     alt="Easy communication" loading="lazy">
                <div class="feat-img-overlay">
                    <div class="feat-img-icon">💬</div>
                    <h3>Easy Communication</h3>
                    <p>Connect, chat and build meaningful bonds safely with families.</p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     HOW IT WORKS — circular Indian photos + gold line
════════════════════════════════════════════════════ --}}
<section class="section hiw-section">
    <div class="container text-center">
        <div class="section-header">
            <div class="badge">Simple Process</div>
            <h2>How It Works</h2>
            <p>4 easy steps to find your life partner</p>
            <div class="divider"></div>
        </div>

        <div class="steps-visual-grid">

            <div class="step-visual-card">
                <div class="step-circle-wrap">
                    <img src="{{ asset('assets/images/card5.jpeg') }}"
                         alt="Register" loading="lazy">
                    <div class="step-badge">1</div>
                </div>
                <h3>Register Free</h3>
                <p>Create your profile in just a few minutes — no cost to join.</p>
            </div>

            <div class="step-visual-card">
                <div class="step-circle-wrap">
                    <img src="{{ asset('assets/images/card4.jpg') }}"
                         alt="Complete Profile" loading="lazy">
                    <div class="step-badge">2</div>
                </div>
                <h3>Complete Profile</h3>
                <p>Add photos, horoscope, and family details to attract better matches.</p>
            </div>

            <div class="step-visual-card">
                <div class="step-circle-wrap">
                    <img src="{{ asset('assets/images/card3.jpg') }}"
                         alt="Search Connect" loading="lazy">
                    <div class="step-badge">3</div>
                </div>
                <h3>Search & Connect</h3>
                <p>Browse verified profiles by community, language, and location.</p>
            </div>

            <div class="step-visual-card">
                <div class="step-circle-wrap">
                    <img src="{{ asset('assets/images/card2.webp') }}"
                         alt="Meet Marry" loading="lazy">
                    <div class="step-badge">4</div>
                </div>
                <h3>Meet & Marry</h3>
                <p>Build trust with families and take your sacred next step together.</p>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     STATS STRIP
════════════════════════════════════════════════════ --}}
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-number">50,000+</span>
                <span class="stat-label">Registered Members</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">12,000+</span>
                <span class="stat-label">Verified Profiles</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">3,000+</span>
                <span class="stat-label">Successful Weddings</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">20+</span>
                <span class="stat-label">Communities Served</span>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     INTERACTIVE PHOTO GALLERY STRIP (hover to expand)
════════════════════════════════════════════════════ }}
<div class="gallery-strip" aria-label="Indian wedding moments">
    <img src="{{ asset('assets/images/card5.jpeg') }}"  alt="Indian bride"       loading="lazy">
    <img src="{{ asset('assets/images/card4.jpg') }}"  alt="Wedding ceremony"  loading="lazy">
    <img src="{{ asset('assets/images/card3.jpg') }}"  alt="Happy couple"      loading="lazy">
    <img src="{{ asset('assets/images/card2.webp') }}"  alt="Indian wedding"    loading="lazy">
    <img src="{{ asset('assets/images/card1.jpg') }}"  alt="Celebration"       loading="lazy">
</div>

{{-- ═══════════════════════════════════════════════════
     SUCCESS STORIES — Indian couples
════════════════════════════════════════════════════ --}}
<section class="section" id="profiles">
    <div class="container">
        <div class="section-header text-center">
            <div class="badge">Real Stories</div>
            <h2>Success Stories</h2>
            <p>Thousands of Indian families have found their perfect match with us</p>
            <div class="divider"></div>
        </div>

        <div class="stories-grid">

            <div class="story-card">
                <div class="story-photo">
                    <img src="{{ asset('assets/images/card5.jpeg') }}"
                         alt="Rahul & Priya" loading="lazy">
                    <span class="story-ribbon">Mumbai ❤️</span>
                </div>
                <div class="story-body">
                    <div class="story-names">Rahul & Priya</div>
                    <div class="story-location">📍 Mumbai, Maharashtra</div>
                    <p class="story-quote">We found each other within 2 months. Our families approved instantly. Thank you Express Matrimony!</p>
                </div>
            </div>

            <div class="story-card">
                <div class="story-photo">
                    <img src="{{ asset('assets/images/card2.webp') }}"
                         alt="Amit & Sunita" loading="lazy">
                    <span class="story-ribbon">Delhi ❤️</span>
                </div>
                <div class="story-body">
                    <div class="story-names">Amit & Sunita</div>
                    <div class="story-location">📍 New Delhi</div>
                    <p class="story-quote">Best decision ever. Very genuine profiles and a smooth family-friendly process throughout.</p>
                </div>
            </div>

            <div class="story-card">
                <div class="story-photo">
                    <img src="{{ asset('assets/images/card1.jpg') }}"
                         alt="Suresh & Kavita" loading="lazy">
                    <span class="story-ribbon">Bangalore ❤️</span>
                </div>
                <div class="story-body">
                    <div class="story-names">Suresh & Kavita</div>
                    <div class="story-location">📍 Bangalore, Karnataka</div>
                    <p class="story-quote">Our families loved the platform. The verification process gave everyone real peace of mind.</p>
                </div>
            </div>

        </div>
    </div>
</section>



{{-- ═══════════════════════════════════════════════════
     TRUST CARDS (dark section from existing CSS)
════════════════════════════════════════════════════ --}}
<section class="trust-section">
    <div class="container">
        <div class="section-header text-center" style="margin-bottom:48px;">
            <div class="badge" style="background:rgba(212,160,23,.15); color:var(--gold-light); border-color:rgba(212,160,23,.3);">Our Promise</div>
            <h2 style="color:#fff;">Why Families Trust Us</h2>
            <div class="divider"></div>
        </div>

        <div class="trust-grid">
            <div class="trust-card">
                <div class="trust-icon">🕉️</div>
                <h3>All Communities</h3>
                <p>Serving Hindu, Muslim, Christian, Sikh, Jain, Buddhist and every Indian community with equal respect.</p>
            </div>
            <div class="trust-card">
                <div class="trust-icon">👨‍👩‍👧‍👦</div>
                <h3>Family Values</h3>
                <p>We understand the importance of family approval and provide tools for parents to participate.</p>
            </div>
            <div class="trust-card">
                <div class="trust-icon">🔐</div>
                <h3>Privacy First</h3>
                <p>Control who sees your photos and contact details. Your privacy is respected at every step.</p>
            </div>
            <div class="trust-card">
                <div class="trust-icon">⭐</div>
                <h3>Genuine Profiles</h3>
                <p>Aadhaar-linked checks, phone OTP and manual review ensure only real, trustworthy members.</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     FINAL CTA — Indian wedding full bleed
════════════════════════════════════════════════════ --}}
<section class="cta-section">
    <div class="cta-bg"></div>
    <div class="cta-content">
        <div class="badge" style="background:rgba(212,160,23,.2); color:var(--gold-light); border-color:rgba(212,160,23,.35); margin-bottom:20px;">
            Begin Your Journey
        </div>
        <h2>Ready to Find Your Soulmate?</h2>
        <p>
            Join thousands of happy Indian families who found their perfect match
            with trust and love through Express Matrimony.
        </p>
        <a href="{{ route('register') }}" class="btn btn-gold btn-lg">Register Free Now 🙏</a>
    </div>
</section>

@endsection