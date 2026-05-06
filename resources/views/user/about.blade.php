@extends('user.layouts.app')

@section('title', "Express Matrimony - Find Your Perfect Life Partner")

@section('content')
<!-- Page Hero -->
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb"><a href="{{ route('home') }}">Home</a><span>›</span><span>About Us</span></nav>
    <h1>About Express Matrimony</h1>
    <p>Dedicated to connecting hearts with trust, tradition, and shared values.</p>
  </div>
</section>

<!-- Introduction -->
<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;" class="about-intro-grid">
      <div>
        <div class="badge">Our Story</div>
        <h2 style="margin-bottom:20px;">Who We Are</h2>
        <p style="margin-bottom:20px;font-size:1.05rem;">Express Matrimony is a modern, trusted matrimonial platform dedicated to helping individuals and families find their perfect life partner.</p>
        <p style="margin-bottom:28px;">We believe marriage is a beautiful journey that thrives on compatibility, respect, trust, and shared values. Our platform makes this journey simple, safe, and meaningful.</p>
        
        <div style="display:flex;gap:32px;">
          <div>
            <div style="font-family:var(--font-display);font-size:2rem;font-weight:700;color:var(--primary);">50K+</div>
            <div style="font-size:0.85rem;color:var(--text-muted);">Registered Members</div>
          </div>
          <div>
            <div style="font-family:var(--font-display);font-size:2rem;font-weight:700;color:var(--primary);">3200+</div>
            <div style="font-size:0.85rem;color:var(--text-muted);">Marriages Completed</div>
          </div>
          <div>
            <div style="font-family:var(--font-display);font-size:2rem;font-weight:700;color:var(--primary);">10+</div>
            <div style="font-size:0.85rem;color:var(--text-muted);">Years of Trust</div>
          </div>
        </div>
      </div>
      
      <div style="background:linear-gradient(135deg,var(--primary-dark),#1A1A2E);border-radius:var(--radius-lg);padding:48px;color:#fff;text-align:center;">
        <div style="font-size:5rem;margin-bottom:16px;">💕</div>
        <h3 style="color:var(--gold-light);margin-bottom:12px;font-size:1.4rem;">Find Your Forever</h3>
        <p style="color:rgba(255,255,255,0.8);font-size:0.97rem;line-height:1.8;">"Connecting souls with love, trust, and compatibility."</p>
        <div style="margin-top:28px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.15);">
          <div style="font-size:0.85rem;color:rgba(255,255,255,0.5);">Founded with love for</div>
          <div style="font-family:var(--font-display);font-size:1.1rem;color:#fff;margin-top:4px;">Every Indian Family</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Mission & Vision -->
<section class="section" style="background:var(--bg-light);">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;" class="mv-grid">
      <div style="background:#fff;border-radius:var(--radius-md);padding:48px;border:1px solid var(--border);border-top:4px solid var(--primary);">
        <div style="font-size:2.5rem;margin-bottom:16px;">🎯</div>
        <h3 style="color:var(--primary);margin-bottom:16px;font-size:1.4rem;">Our Mission</h3>
        <p style="font-size:1.02rem;line-height:1.9;">To provide a safe, trusted, and user-friendly matrimonial platform where individuals and families can find compatible life partners with ease and confidence.</p>
      </div>
      <div style="background:#fff;border-radius:var(--radius-md);padding:48px;border:1px solid var(--border);border-top:4px solid var(--accent);">
        <div style="font-size:2.5rem;margin-bottom:16px;">🌟</div>
        <h3 style="color:var(--accent);margin-bottom:16px;font-size:1.4rem;">Our Vision</h3>
        <p style="font-size:1.02rem;line-height:1.9;">To become India's most preferred matrimonial platform, known for successful matches, transparency, and bringing happiness to millions of families.</p>
      </div>
    </div>
  </div>
</section>

<!-- Why Choose Us -->
<section class="section">
  <div class="container">
    <div class="section-header text-center">
      <div class="badge">Our Strengths</div>
      <h2>Why Choose Express Matrimony?</h2>
      <p>We offer a platform that truly understands and serves the needs of modern Indian families.</p>
      <div class="divider"></div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
      <div style="display:flex;gap:16px;padding:24px;background:var(--bg-light);border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:1.8rem;flex-shrink:0;">🏘️</div>
        <div>
          <h4 style="font-size:1rem;margin-bottom:6px;font-family:var(--font-body);font-weight:600;">Community Focused</h4>
          <p style="font-size:0.88rem;">Built with deep understanding of Indian culture, traditions, and family values.</p>
        </div>
      </div>
      <div style="display:flex;gap:16px;padding:24px;background:var(--bg-light);border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:1.8rem;flex-shrink:0;">✅</div>
        <div>
          <h4 style="font-size:1rem;margin-bottom:6px;font-family:var(--font-body);font-weight:600;">Verified Profiles</h4>
          <p style="font-size:0.88rem;">Every profile is manually verified for authenticity and safety.</p>
        </div>
      </div>
      <div style="display:flex;gap:16px;padding:24px;background:var(--bg-light);border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:1.8rem;flex-shrink:0;">🔐</div>
        <div>
          <h4 style="font-size:1rem;margin-bottom:6px;font-family:var(--font-body);font-weight:600;">Secure & Private</h4>
          <p style="font-size:0.88rem;">Advanced security and privacy protection for all members.</p>
        </div>
      </div>
      <div style="display:flex;gap:16px;padding:24px;background:var(--bg-light);border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:1.8rem;flex-shrink:0;">💡</div>
        <div>
          <h4 style="font-size:1rem;margin-bottom:6px;font-family:var(--font-body);font-weight:600;">Smart Matchmaking</h4>
          <p style="font-size:0.88rem;">Intelligent matching based on your preferences and compatibility.</p>
        </div>
      </div>
      <div style="display:flex;gap:16px;padding:24px;background:var(--bg-light);border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:1.8rem;flex-shrink:0;">📱</div>
        <div>
          <h4 style="font-size:1rem;margin-bottom:6px;font-family:var(--font-body);font-weight:600;">Mobile Friendly</h4>
          <p style="font-size:0.88rem;">Seamless experience on mobile, tablet, and desktop.</p>
        </div>
      </div>
      <div style="display:flex;gap:16px;padding:24px;background:var(--bg-light);border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:1.8rem;flex-shrink:0;">🤝</div>
        <div>
          <h4 style="font-size:1rem;margin-bottom:6px;font-family:var(--font-body);font-weight:600;">Dedicated Support</h4>
          <p style="font-size:0.88rem;">Our team is always here to assist you throughout your journey.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Values -->
<section class="section" style="background:var(--bg-light);">
  <div class="container">
    <div class="section-header text-center">
      <div class="badge">What We Stand For</div>
      <h2>Our Core Values</h2>
      <p>Principles that guide everything we do.</p>
      <div class="divider"></div>
    </div>
    <div class="values-grid">
      <div class="value-card">
        <div class="value-icon">🕊️</div>
        <h3>Trust</h3>
        <p>We build trust through transparency, verified profiles, and honest communication.</p>
      </div>
      <div class="value-card">
        <div class="value-icon">🙏</div>
        <h3>Respect</h3>
        <p>Every member and family deserves dignity and respect on our platform.</p>
      </div>
      <div class="value-card">
        <div class="value-icon">🌸</div>
        <h3>Compatibility</h3>
        <p>We help you find partners who match your values, culture, and aspirations.</p>
      </div>
      <div class="value-card">
        <div class="value-icon">🔍</div>
        <h3>Transparency</h3>
        <p>Clear processes, no fake profiles, and complete honesty at every step.</p>
      </div>
    </div>
  </div>
</section>

<!-- Team -->
<section class="section">
  <div class="container">
    <div class="section-header text-center">
      <div class="badge">The People Behind</div>
      <h2>Meet Our Team</h2>
      <p>A passionate team dedicated to helping you find your perfect match.</p>
      <div class="divider"></div>
    </div>
    <div class="team-grid">
      <div class="team-card">
        <div class="team-avatar">👨‍💼</div>
        <div class="team-name">Rajesh Kamble</div>
        <div class="team-role">Founder & CEO</div>
        <p style="font-size:0.85rem;margin-top:10px;">Passionate about using technology to create meaningful connections and strengthen families.</p>
      </div>
      <div class="team-card">
        <div class="team-avatar">👩‍💼</div>
        <div class="team-name">Sunita Meshram</div>
        <div class="team-role">Co-Founder & COO</div>
        <p style="font-size:0.85rem;margin-top:10px;">Committed to building a safe and trustworthy platform for Indian families.</p>
      </div>
      <div class="team-card">
        <div class="team-avatar">👨‍💻</div>
        <div class="team-name">Amol Gaikwad</div>
        <div class="team-role">Head of Technology</div>
        <p style="font-size:0.85rem;margin-top:10px;">Ensures Express Matrimony is secure, fast, and easy to use.</p>
      </div>
      <div class="team-card">
        <div class="team-avatar">👩‍🎨</div>
        <div class="team-name">Pooja Rathod</div>
        <div class="team-role">Community Manager</div>
        <p style="font-size:0.85rem;margin-top:10px;">Works closely with members to ensure a warm and supportive experience.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="trust-section">
  <div class="container text-center" style="position:relative;z-index:1;">
    <div class="badge" style="background:rgba(255,255,255,0.1);color:var(--gold-light);border-color:rgba(255,255,255,0.2);">Join Our Family</div>
    <h2 style="color:#fff;margin-bottom:16px;">Be Part of Our Growing Community</h2>
    <p style="color:rgba(255,255,255,0.65);max-width:480px;margin:0 auto 32px;">Register today and take the first step towards finding your perfect life partner.</p>
    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
      <a href="{{ route('register') }}" class="btn btn-gold btn-lg">Register Free</a>
      <a href="{{ route('contact') }}" class="btn btn-outline btn-lg" style="color:#fff;border-color:rgba(255,255,255,0.4)">Contact Us</a>
    </div>
  </div>
</section>
@endsection