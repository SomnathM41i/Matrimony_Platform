@extends('user.layouts.app')

@section('title', "Matrimony - Find Your Perfect Life Partner")

@section('content')

<!-- Page Hero -->
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb"><a href="index.html">Home</a><span>›</span><span>Contact Us</span></nav>
    <h1>Get In Touch</h1>
    <p>We're here to help. Reach out to our team for any questions, support, or assistance.</p>
  </div>
</section>

<!-- Contact Section -->
<section class="section">
  <div class="container">
    <div class="contact-grid">
      <!-- Contact Info -->
      <div class="contact-info">
        <div class="badge">Reach Us</div>
        <h2 style="margin-bottom:16px;">We'd Love to<br>Hear From You</h2>
        <p style="margin-bottom:32px;">Our dedicated team is ready to assist you with profile help, account queries, and anything else you need on your matrimonial journey.</p>

        <div class="contact-item">
          <div class="contact-icon">📍</div>
          <div class="contact-text">
            <h4>Office Address</h4>
            <p>BouddhMatrimony Pvt. Ltd.<br>Civil Lines, Nagpur – 440001<br>Maharashtra, India</p>
          </div>
        </div>

        <div class="contact-item">
          <div class="contact-icon">📧</div>
          <div class="contact-text">
            <h4>Email Address</h4>
            <p>info@bouddhmatrimony.com<br>support@bouddhmatrimony.com</p>
          </div>
        </div>

        <div class="contact-item">
          <div class="contact-icon">📞</div>
          <div class="contact-text">
            <h4>Phone Number</h4>
            <p>+91 98765 43210<br>+91 87654 32109</p>
          </div>
        </div>

        <div class="contact-item">
          <div class="contact-icon">⏰</div>
          <div class="contact-text">
            <h4>Business Hours</h4>
            <p>Monday – Saturday<br>9:00 AM to 6:00 PM IST</p>
          </div>
        </div>

        <!-- Map Placeholder -->
        <div class="map-placeholder">
          <span>🗺️</span>
          <span>Map — Nagpur, Maharashtra, India</span>
        </div>

        <!-- Social Links -->
        <div style="margin-top:24px;">
          <p style="font-weight:600;color:var(--text);font-size:0.9rem;margin-bottom:12px;">Connect With Us</p>
          <div class="social-links">
            <a href="#" class="social-link" style="background:var(--bg-light);color:var(--text);">f</a>
            <a href="#" class="social-link" style="background:var(--bg-light);color:var(--text);">📷</a>
            <a href="#" class="social-link" style="background:var(--bg-light);color:var(--text);">💬</a>
            <a href="#" class="social-link" style="background:var(--bg-light);color:var(--text);">▶</a>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="contact-form-card">
        <div class="badge">Send Message</div>
        <h3 style="margin-bottom:8px;">Write to Us</h3>
        <p style="margin-bottom:28px;font-size:0.9rem;">Fill the form below and we'll respond within 24 hours.</p>

        <form id="contactForm">
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Full Name *</label>
              <input type="text" class="form-control" name="name" placeholder="Your full name" required>
            </div>
            <div class="form-group">
              <label class="form-label">Email Address *</label>
              <input type="email" class="form-control" name="email" placeholder="your@email.com" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Mobile Number *</label>
              <input type="tel" class="form-control" name="mobile" placeholder="+91 XXXXX XXXXX" required>
            </div>
            <div class="form-group">
              <label class="form-label">Subject *</label>
              <select class="form-control" name="subject" required>
                <option value="">Select subject</option>
                <option>General Inquiry</option>
                <option>Profile Help</option>
                <option>Payment Issue</option>
                <option>Technical Support</option>
                <option>Report a Profile</option>
                <option>Plan Upgrade</option>
                <option>Other</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Message *</label>
            <textarea class="form-control" name="message" rows="5" placeholder="Describe your query or message here..." required style="resize:vertical;min-height:120px;"></textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:1rem;">
            Send Message 🙏
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Quick Help -->
<section class="section" style="background:var(--bg-light);">
  <div class="container">
    <div class="section-header text-center">
      <div class="badge">Quick Help</div>
      <h2>How Can We Help?</h2>
      <div class="divider"></div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:24px;">
      <div style="text-align:center;padding:32px 20px;background:#fff;border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:2.2rem;margin-bottom:12px;">👤</div>
        <h4 style="margin-bottom:8px;font-family:var(--font-body);font-weight:600;">Profile Help</h4>
        <p style="font-size:0.88rem;">Need help creating or editing your profile? Our team will guide you step by step.</p>
      </div>
      <div style="text-align:center;padding:32px 20px;background:#fff;border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:2.2rem;margin-bottom:12px;">💳</div>
        <h4 style="margin-bottom:8px;font-family:var(--font-body);font-weight:600;">Payment Support</h4>
        <p style="font-size:0.88rem;">Payment or subscription issues? We'll resolve them within 24 business hours.</p>
      </div>
      <div style="text-align:center;padding:32px 20px;background:#fff;border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:2.2rem;margin-bottom:12px;">🔍</div>
        <h4 style="margin-bottom:8px;font-family:var(--font-body);font-weight:600;">Matchmaking Help</h4>
        <p style="font-size:0.88rem;">Not finding the right match? Our relationship advisors will assist you personally.</p>
      </div>
      <div style="text-align:center;padding:32px 20px;background:#fff;border-radius:var(--radius-md);border:1px solid var(--border);">
        <div style="font-size:2.2rem;margin-bottom:12px;">🛡️</div>
        <h4 style="margin-bottom:8px;font-family:var(--font-body);font-weight:600;">Safety & Reports</h4>
        <p style="font-size:0.88rem;">Report any suspicious or inappropriate profiles. We take all safety concerns seriously.</p>
      </div>
    </div>
  </div>
</section>

@endsection