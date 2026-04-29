@extends('user.layouts.app')

@section('title', "Matrimony - Find Your Perfect Life Partner")

@section('content')

<!-- Page Hero -->
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb"><a href="index.html">Home</a><span>›</span><span>About Us</span></nav>
    <h1>About BouddhMatrimony</h1>
    <p>Dedicated to connecting Bouddh community families with respect, trust, and shared values.</p>
  </div>
</section>

<!-- Introduction -->
<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;" class="about-intro-grid">
      <div>
        <div class="badge">Our Story</div>
        <h2 style="margin-bottom:20px;">Who We Are</h2>
        <p style="margin-bottom:20px;font-size:1.05rem;">BouddhMatrimony is a dedicated matrimonial platform designed exclusively for the Bouddh community. Our mission is to help individuals and families find compatible life partners within shared cultural and spiritual values.</p>
        <p style="margin-bottom:28px;">We understand the importance of community, cultural compatibility, and shared belief systems in building a lasting marriage. That is why every feature on our platform is thoughtfully designed with the Bouddh community's unique values in mind.</p>
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
        <div style="font-size:5rem;margin-bottom:16px;">☸️</div>
        <h3 style="color:var(--gold-light);margin-bottom:12px;font-size:1.4rem;">॥ भवतु सब्ब मंगलम् ॥</h3>
        <p style="color:rgba(255,255,255,0.7);font-size:0.95rem;font-style:italic;line-height:1.8;">"May all beings be happy and prosperous. May all find their perfect companions in life."</p>
        <div style="margin-top:28px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.15);">
          <div style="font-size:0.85rem;color:rgba(255,255,255,0.5);">Founded with love for the</div>
          <div style="font-family:var(--font-display);font-size:1.1rem;color:#fff;margin-top:4px;">Bouddh Community</div>
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
        <p style="font-size:1.02rem;line-height:1.9;">To provide a trusted, respectful, and community-focused matrimonial platform exclusively for the Bouddh community — where families can find compatible life partners with shared values, cultural identity, and Buddhist principles.</p>
      </div>
      <div style="background:#fff;border-radius:var(--radius-md);padding:48px;border:1px solid var(--border);border-top:4px solid var(--accent);">
        <div style="font-size:2.5rem;margin-bottom:16px;">🌟</div>
        <h3 style="color:var(--accent);margin-bottom:16px;font-size:1.4rem;">Our Vision</h3>
        <p style="font-size:1.02rem;line-height:1.9;">To become the most trusted and widely used matrimonial platform for the Bouddh community across India and the world, strengthening community bonds and fostering meaningful, value-aligned unions for generations to come.</p>
      </div>
    </div>
  </div>
</section>

<!-- Why Choose Us -->
<section class="section">
  <div class="container">
    <div class="section-header text-center">
      <div class="badge">Our Strengths</div>
      <h2>Why Choose BouddhMatrimony?</h2>
      <p>We offer a platform that truly understands and serves the needs of our community.</p>
      <div class="divider"></div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
      <div style="display:flex;gap:16px;padding:24px;background:var(--bg-light);border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:1.8rem;flex-shrink:0;">🏘️</div>
        <div>
          <h4 style="font-size:1rem;margin-bottom:6px;font-family:var(--font-body);font-weight:600;">Community-Focused Platform</h4>
          <p style="font-size:0.88rem;">Built exclusively for the Bouddh community with deep understanding of our culture and values.</p>
        </div>
      </div>
      <div style="display:flex;gap:16px;padding:24px;background:var(--bg-light);border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:1.8rem;flex-shrink:0;">✅</div>
        <div>
          <h4 style="font-size:1rem;margin-bottom:6px;font-family:var(--font-body);font-weight:600;">Verified Profiles</h4>
          <p style="font-size:0.88rem;">Every profile undergoes manual verification to ensure authenticity and safety for all members.</p>
        </div>
      </div>
      <div style="display:flex;gap:16px;padding:24px;background:var(--bg-light);border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:1.8rem;flex-shrink:0;">🔐</div>
        <div>
          <h4 style="font-size:1rem;margin-bottom:6px;font-family:var(--font-body);font-weight:600;">Secure & Private</h4>
          <p style="font-size:0.88rem;">Your data and communications are protected with industry-standard encryption technology.</p>
        </div>
      </div>
      <div style="display:flex;gap:16px;padding:24px;background:var(--bg-light);border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:1.8rem;flex-shrink:0;">💡</div>
        <div>
          <h4 style="font-size:1rem;margin-bottom:6px;font-family:var(--font-body);font-weight:600;">Smart Matchmaking</h4>
          <p style="font-size:0.88rem;">Intelligent suggestions based on compatibility scores, preferences, and community background.</p>
        </div>
      </div>
      <div style="display:flex;gap:16px;padding:24px;background:var(--bg-light);border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:1.8rem;flex-shrink:0;">📱</div>
        <div>
          <h4 style="font-size:1rem;margin-bottom:6px;font-family:var(--font-body);font-weight:600;">Mobile Friendly</h4>
          <p style="font-size:0.88rem;">Access the platform seamlessly from any device — mobile, tablet, or desktop.</p>
        </div>
      </div>
      <div style="display:flex;gap:16px;padding:24px;background:var(--bg-light);border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:1.8rem;flex-shrink:0;">🤝</div>
        <div>
          <h4 style="font-size:1rem;margin-bottom:6px;font-family:var(--font-body);font-weight:600;">Dedicated Support</h4>
          <p style="font-size:0.88rem;">Our support team understands the community and is always ready to assist you in finding your match.</p>
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
      <p>Principles inspired by the Buddha's teachings that guide everything we do.</p>
      <div class="divider"></div>
    </div>
    <div class="values-grid">
      <div class="value-card">
        <div class="value-icon">🕊️</div>
        <h3>Trust</h3>
        <p>We build trust through transparency, verified profiles, and honest communication at every step.</p>
      </div>
      <div class="value-card">
        <div class="value-icon">🙏</div>
        <h3>Respect</h3>
        <p>Every member deserves dignity and respect. We foster a safe, respectful environment for all families.</p>
      </div>
      <div class="value-card">
        <div class="value-icon">🌸</div>
        <h3>Community</h3>
        <p>We exist to serve and strengthen the Bouddh community through meaningful connections.</p>
      </div>
      <div class="value-card">
        <div class="value-icon">🔍</div>
        <h3>Transparency</h3>
        <p>No hidden terms, no fake profiles. What you see is what you get — honest and clear always.</p>
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
      <p>A passionate team dedicated to serving the Bouddh community with love and purpose.</p>
      <div class="divider"></div>
    </div>
    <div class="team-grid">
      <div class="team-card">
        <div class="team-avatar">👨‍💼</div>
        <div class="team-name">Rajesh Kamble</div>
        <div class="team-role">Founder & CEO</div>
        <p style="font-size:0.85rem;margin-top:10px;">Passionate about community empowerment and using technology to strengthen Bouddh family values.</p>
      </div>
      <div class="team-card">
        <div class="team-avatar">👩‍💼</div>
        <div class="team-name">Sunita Meshram</div>
        <div class="team-role">Co-Founder & COO</div>
        <p style="font-size:0.85rem;margin-top:10px;">Dedicated to building a trustworthy platform that upholds Buddhist principles of equality and compassion.</p>
      </div>
      <div class="team-card">
        <div class="team-avatar">👨‍💻</div>
        <div class="team-name">Amol Gaikwad</div>
        <div class="team-role">Head of Technology</div>
        <p style="font-size:0.85rem;margin-top:10px;">Ensures BouddhMatrimony is secure, fast, and user-friendly for all community members.</p>
      </div>
      <div class="team-card">
        <div class="team-avatar">👩‍🎨</div>
        <div class="team-name">Pooja Rathod</div>
        <div class="team-role">Community Manager</div>
        <p style="font-size:0.85rem;margin-top:10px;">Manages member relationships and ensures every family's experience is warm and supportive.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="trust-section">
  <div class="container text-center" style="position:relative;z-index:1;">
    <div class="badge" style="background:rgba(255,255,255,0.1);color:var(--gold-light);border-color:rgba(255,255,255,0.2);">Join Our Family</div>
    <h2 style="color:#fff;margin-bottom:16px;">Be Part of Our Growing Community</h2>
    <p style="color:rgba(255,255,255,0.65);max-width:480px;margin:0 auto 32px;">Register today and join thousands of Bouddh families finding meaningful life partners through our trusted platform.</p>
    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
      <a href="register.html" class="btn btn-gold btn-lg">Register Free 🙏</a>
      <a href="contact.html" class="btn btn-outline btn-lg" style="color:#fff;border-color:rgba(255,255,255,0.4)">Contact Us</a>
    </div>
  </div>
</section>

@endsection
