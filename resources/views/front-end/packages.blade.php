@extends('front-end.layout.app')

@section('title', "Matrimony - Find Your Perfect Life Partner")

@section('content')
<!-- Page Hero -->
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumb"><a href="index.html">Home</a><span>›</span><span>Packages</span></nav>
    <h1>Flexible Packages</h1>
    <p>Choose the plan that best suits your needs. Start free, upgrade anytime without hassle.</p>
  </div>
</section>

<!-- Packages -->
<section class="section packages-section">
  <div class="container">
    <div class="section-header text-center">
      <div class="badge">Pricing Plans</div>
      <h2>Choose Your Perfect Plan</h2>
      <p>All plans come with our community-focused features. Premium plans unlock more connections.</p>
      <div class="divider"></div>
    </div>
    <div class="packages-grid" style="max-width:960px;margin:0 auto;">
      <!-- Basic -->
      <div class="package-card reveal">
        <div class="package-name">Basic Plan</div>
        <div class="package-price"><sup>₹</sup>0</div>
        <div class="package-duration">Free Forever</div>
        <ul class="package-features">
          <li><span class="check">✓</span> Create your profile</li>
          <li><span class="check">✓</span> 10 profile views per month</li>
          <li><span class="check">✓</span> Basic search filters</li>
          <li><span class="check">✓</span> Photo upload (1 photo)</li>
          <li><span class="check">✓</span> Express interest</li>
          <li><span class="cross">✗</span> Direct messaging</li>
          <li><span class="cross">✗</span> Contact details access</li>
          <li><span class="cross">✗</span> Advanced filters</li>
          <li><span class="cross">✗</span> Priority listing</li>
          <li><span class="cross">✗</span> Dedicated support</li>
        </ul>
        <a href="register.html" class="btn btn-outline btn-buy" data-plan="Basic" style="width:100%;justify-content:center">Get Started Free</a>
        <p style="font-size:0.78rem;color:var(--text-light);text-align:center;margin-top:12px;">No credit card required</p>
      </div>

      <!-- Premium -->
      <div class="package-card featured reveal">
        <div class="package-badge">Most Popular</div>
        <div class="package-name">Premium Plan</div>
        <div class="package-price"><sup>₹</sup>999</div>
        <div class="package-duration">Per 3 Months (₹333/month)</div>
        <ul class="package-features">
          <li><span class="check">✓</span> Unlimited profile views</li>
          <li><span class="check">✓</span> Direct messaging</li>
          <li><span class="check">✓</span> Contact details access</li>
          <li><span class="check">✓</span> Upload up to 5 photos</li>
          <li><span class="check">✓</span> Priority listing in search</li>
          <li><span class="check">✓</span> Advanced search filters</li>
          <li><span class="check">✓</span> Express interest + notifications</li>
          <li><span class="check">✓</span> Email support</li>
          <li><span class="cross">✗</span> Profile highlight</li>
          <li><span class="cross">✗</span> Dedicated relationship manager</li>
        </ul>
        <button class="btn btn-primary btn-buy" data-plan="Premium" style="width:100%;justify-content:center">Buy Premium →</button>
        <p style="font-size:0.78rem;color:var(--text-light);text-align:center;margin-top:12px;">Secure payment • Cancel anytime</p>
      </div>

      <!-- VIP -->
      <div class="package-card reveal">
        <div class="package-name">VIP Plan</div>
        <div class="package-price"><sup>₹</sup>2499</div>
        <div class="package-duration">Per 6 Months (₹417/month)</div>
        <ul class="package-features">
          <li><span class="check">✓</span> All Premium features</li>
          <li><span class="check">✓</span> Profile highlight badge</li>
          <li><span class="check">✓</span> Featured placement (top results)</li>
          <li><span class="check">✓</span> Upload unlimited photos</li>
          <li><span class="check">✓</span> Dedicated relationship manager</li>
          <li><span class="check">✓</span> Horoscope & kundali matching</li>
          <li><span class="check">✓</span> WhatsApp support</li>
          <li><span class="check">✓</span> Profile boost (weekly)</li>
          <li><span class="check">✓</span> Verified badge on profile</li>
          <li><span class="check">✓</span> Exclusive VIP community access</li>
        </ul>
        <button class="btn btn-gold btn-buy" data-plan="VIP" style="width:100%;justify-content:center">Buy VIP ⭐</button>
        <p style="font-size:0.78rem;color:var(--text-light);text-align:center;margin-top:12px;">Best value for serious seekers</p>
      </div>
    </div>
  </div>
</section>

<!-- Comparison Table -->
<section class="section" style="background:var(--bg-light);">
  <div class="container">
    <div class="section-header text-center">
      <div class="badge">Side by Side</div>
      <h2>Plan Comparison</h2>
      <p>Compare all features across plans to choose what's right for you.</p>
      <div class="divider"></div>
    </div>
    <div style="overflow-x:auto;border-radius:var(--radius-md);box-shadow:var(--shadow-sm);">
      <table class="compare-table">
        <thead>
          <tr>
            <th style="width:40%;">Feature</th>
            <th>Basic</th>
            <th>Premium</th>
            <th>VIP</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Profile Creation</td><td class="yes">✓</td><td class="yes">✓</td><td class="yes">✓</td></tr>
          <tr><td>Profile Views / Month</td><td>10</td><td>Unlimited</td><td>Unlimited</td></tr>
          <tr><td>Photo Upload</td><td>1 Photo</td><td>5 Photos</td><td>Unlimited</td></tr>
          <tr><td>Basic Search</td><td class="yes">✓</td><td class="yes">✓</td><td class="yes">✓</td></tr>
          <tr><td>Advanced Filters</td><td class="no">✗</td><td class="yes">✓</td><td class="yes">✓</td></tr>
          <tr><td>Express Interest</td><td class="yes">✓</td><td class="yes">✓</td><td class="yes">✓</td></tr>
          <tr><td>Direct Messaging</td><td class="no">✗</td><td class="yes">✓</td><td class="yes">✓</td></tr>
          <tr><td>Contact Details Access</td><td class="no">✗</td><td class="yes">✓</td><td class="yes">✓</td></tr>
          <tr><td>Priority Listing</td><td class="no">✗</td><td class="yes">✓</td><td class="yes">✓</td></tr>
          <tr><td>Profile Highlight</td><td class="no">✗</td><td class="no">✗</td><td class="yes">✓</td></tr>
          <tr><td>Featured Placement</td><td class="no">✗</td><td class="no">✗</td><td class="yes">✓</td></tr>
          <tr><td>Horoscope Matching</td><td class="no">✗</td><td class="no">✗</td><td class="yes">✓</td></tr>
          <tr><td>Dedicated Support</td><td class="no">✗</td><td>Email Only</td><td class="yes">✓ WhatsApp</td></tr>
          <tr><td>Profile Boost</td><td class="no">✗</td><td class="no">✗</td><td class="yes">Weekly</td></tr>
          <tr><td>Verified Badge</td><td class="no">✗</td><td class="no">✗</td><td class="yes">✓</td></tr>
          <tr>
            <td style="font-weight:700;color:var(--text);">Price</td>
            <td style="font-weight:700;color:var(--primary);">Free</td>
            <td style="font-weight:700;color:var(--primary);">₹999 / 3 months</td>
            <td style="font-weight:700;color:var(--gold);">₹2499 / 6 months</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="section">
  <div class="container" style="max-width:760px;">
    <div class="section-header text-center">
      <div class="badge">Help</div>
      <h2>Frequently Asked Questions</h2>
      <p>Have questions about our plans? We have answers.</p>
      <div class="divider"></div>
    </div>
    <div>
      <div class="faq-item">
        <button class="faq-question">Can I upgrade my plan anytime? <span class="faq-icon">+</span></button>
        <div class="faq-answer"><div class="faq-answer-inner">Yes, you can upgrade your plan at any time. Your new plan benefits will activate immediately upon payment. The remaining days from your previous plan will not be refunded but you will enjoy the upgraded features right away.</div></div>
      </div>
      <div class="faq-item">
        <button class="faq-question">Is my personal information safe? <span class="faq-icon">+</span></button>
        <div class="faq-answer"><div class="faq-answer-inner">Absolutely. We take data privacy seriously. Your contact information is only shared with mutual matches and never sold to third parties. You can control your privacy settings from your account dashboard.</div></div>
      </div>
      <div class="faq-item">
        <button class="faq-question">How are profiles verified? <span class="faq-icon">+</span></button>
        <div class="faq-answer"><div class="faq-answer-inner">Our team manually reviews each registration. We verify profiles through mobile OTP, email verification, and optional ID document submission. Verified profiles receive a trust badge visible to all members.</div></div>
      </div>
      <div class="faq-item">
        <button class="faq-question">What payment methods are accepted? <span class="faq-icon">+</span></button>
        <div class="faq-answer"><div class="faq-answer-inner">We accept UPI, Net Banking, Debit/Credit Cards (Visa, Mastercard, Rupay), and popular wallets like Paytm and PhonePe. All payments are processed through a secure payment gateway.</div></div>
      </div>
      <div class="faq-item">
        <button class="faq-question">Can I cancel my subscription? <span class="faq-icon">+</span></button>
        <div class="faq-answer"><div class="faq-answer-inner">You can cancel your subscription at any time. Your premium access will continue until the end of your current billing period. We do not offer prorated refunds for cancellations mid-period except in special circumstances.</div></div>
      </div>
      <div class="faq-item">
        <button class="faq-question">What if I don't find a match? <span class="faq-icon">+</span></button>
        <div class="faq-answer"><div class="faq-answer-inner">Our dedicated support team will help you optimize your profile and search criteria. VIP members get personalized assistance from a relationship manager. We continuously add new verified profiles to increase your chances of finding the right match.</div></div>
      </div>
    </div>
  </div>
</section>

<!-- Refund Policy -->
<section class="section" style="background:var(--bg-light);">
  <div class="container" style="max-width:760px;">
    <div class="section-header text-center">
      <div class="badge">Policy</div>
      <h2>Refund Policy</h2>
      <div class="divider"></div>
    </div>
    <div style="background:#fff;border-radius:var(--radius-md);padding:40px;border:1px solid var(--border);">
      <div style="display:flex;flex-direction:column;gap:20px;">
        <div style="display:flex;gap:16px;">
          <div style="width:8px;height:8px;border-radius:50%;background:var(--primary);flex-shrink:0;margin-top:8px;"></div>
          <p><strong style="color:var(--text);">7-Day Refund Window:</strong> If you are unsatisfied with your Premium or VIP subscription, you may request a full refund within 7 days of purchase by contacting our support team.</p>
        </div>
        <div style="display:flex;gap:16px;">
          <div style="width:8px;height:8px;border-radius:50%;background:var(--primary);flex-shrink:0;margin-top:8px;"></div>
          <p><strong style="color:var(--text);">No Refund After 7 Days:</strong> Refunds will not be issued after 7 days from the date of purchase, as services would have been partially or fully utilized.</p>
        </div>
        <div style="display:flex;gap:16px;">
          <div style="width:8px;height:8px;border-radius:50%;background:var(--primary);flex-shrink:0;margin-top:8px;"></div>
          <p><strong style="color:var(--text);">Process:</strong> Approved refunds will be credited to the original payment method within 5–7 business days.</p>
        </div>
        <div style="display:flex;gap:16px;">
          <div style="width:8px;height:8px;border-radius:50%;background:var(--primary);flex-shrink:0;margin-top:8px;"></div>
          <p><strong style="color:var(--text);">Contact for Refund:</strong> Email refund@bouddhmatrimony.com with your registered email and order ID.</p>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
