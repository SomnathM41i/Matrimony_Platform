<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description"
    content="BouddhMatrimony — Trusted matrimonial platform dedicated to the Bouddh (Buddhist) community. Find your perfect life partner with respect, tradition, and values.">
  <meta name="keywords" content="Bouddh matrimony, Buddhist matrimonial, Buddhist marriage, Ambedkarite matrimony">
  <meta property="og:title" content="BouddhMatrimony — Find Your Perfect Match">
  <meta property="og:description" content="Trusted Matrimonial Platform for the Bouddh Community">
  <title>BouddhMatrimony — Find Your Perfect Life Partner</title>
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
</head>

<body>

  <!-- Loader -->
  <div id="loader">
    <div class="loader-inner">
      <div class="loader-ring"></div>
      <div class="loader-text">BouddhMatrimony</div>
    </div>
  </div>

  <!-- Header -->
  <header class="header">
    <div class="container">
      <nav class="nav">
        <a href="index.html" class="nav-logo">
          <img src="assets/images/logo.png" alt="BouddhMatrimony" onerror="this.style.display='none'">
          <div><span>Bouddha</span><span style="color:var(--accent)">Matrimony</span></div>
        </a>
        <ul class="nav-links">
          <li><a href="index.html" class="active">Home</a></li>
          <li><a href="about.html">About Us</a></li>
          <li><a href="packages.html">Packages</a></li>
          <li><a href="contact.html">Contact Us</a></li>
        </ul>
        <div class="nav-actions">
          <a href="login.html" class="btn btn-outline btn-sm">Login</a>
          <a href="register.html" class="btn btn-primary btn-sm">Register Free</a>
        </div>
        <button class="hamburger" id="hamburger" aria-label="Menu">
          <span></span><span></span><span></span>
        </button>
      </nav>
    </div>
    <div class="mobile-menu" id="mobileMenu">
      <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="about.html">About Us</a></li>
        <li><a href="packages.html">Packages</a></li>
        <li><a href="contact.html">Contact Us</a></li>
      </ul>
      <div class="mobile-actions">
        <a href="login.html" class="btn btn-outline btn-sm" style="flex:1;justify-content:center">Login</a>
        <a href="register.html" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">Register</a>
      </div>
    </div>
  </header>

  <!-- Hero -->
  <section class="hero">
    <div class="hero-pattern"></div>
    <div class="hero-visual">
      <div class="hero-visual-circle"></div>
      <div class="hero-visual-circle"></div>
      <div class="hero-visual-circle"></div>
    </div>
    <div class="container" style="position:relative;z-index:2;">
      <div class="hero-content animate-fade-up">
        <div class="hero-badge">Trusted by 50,000+ Families</div>
        <h1>Find Your Perfect<br><span>Life Partner</span><br>in the Bouddh Community</h1>
        <p>Trusted Matrimonial Platform for the Bouddh Community. Connecting families with respect, tradition, and
          values rooted in Buddhist principles.</p>
        <div class="hero-actions">
          <a href="register.html" class="btn btn-primary btn-lg">Register Free 🙏</a>
          <a href="#profiles" class="btn btn-outline btn-lg"
            style="color:#fff;border-color:rgba(255,255,255,0.4)">Browse Profiles</a>
        </div>
      </div>
      <div class="hero-stats animate-fade-up animate-delay-2">
        <div>
          <div class="hero-stat-num">50K+</div>
          <div class="hero-stat-label">Members</div>
        </div>
        <div>
          <div class="hero-stat-num">12K+</div>
          <div class="hero-stat-label">Verified Profiles</div>
        </div>
        <div>
          <div class="hero-stat-num">3,200+</div>
          <div class="hero-stat-label">Marriages</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Quick Search -->
  <section style="background:var(--bg-light);padding:0 0 60px;">
    <div class="container">
      <div class="quick-search">
        <h3>Search for Your Match</h3>
        <form id="searchForm">
          <div class="search-grid">
            <div class="form-group" style="margin:0">
              <label class="form-label">Looking for</label>
              <select class="form-control">
                <option>Bride</option>
                <option>Groom</option>
              </select>
            </div>
            <div class="form-group" style="margin:0">
              <label class="form-label">Age Range</label>
              <select class="form-control">
                <option>21–25 years</option>
                <option>26–30 years</option>
                <option>31–35 years</option>
                <option>36–40 years</option>
                <option>41+ years</option>
              </select>
            </div>
            <div class="form-group" style="margin:0">
              <label class="form-label">Religion</label>
              <select class="form-control">
                <option selected>Bouddh (Buddhist)</option>
              </select>
            </div>
            <div class="form-group" style="margin:0">
              <label class="form-label">Location</label>
              <select class="form-control">
                <option value="">Any Location</option>
                <option>Maharashtra</option>
                <option>Madhya Pradesh</option>
                <option>Uttar Pradesh</option>
                <option>Karnataka</option>
                <option>Gujarat</option>
                <option>Delhi</option>
              </select>
            </div>
            <div class="form-group" style="margin:0">
              <label class="form-label">Education</label>
              <select class="form-control">
                <option value="">Any Education</option>
                <option>Graduate</option>
                <option>Post Graduate</option>
                <option>Doctorate</option>
                <option>Professional Degree</option>
              </select>
            </div>
            <div class="form-group" style="margin:0">
              <label class="form-label">&nbsp;</label>
              <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Search Profiles
                →</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- Features -->
  <section class="section" id="features">
    <div class="container">
      <div class="section-header text-center">
        <div class="badge">Why Choose Us</div>
        <h2>Built for the Bouddh Community</h2>
        <p>A dedicated platform with features designed to help you find a meaningful, values-aligned life partner.</p>
        <div class="divider"></div>
      </div>
      <div class="features-grid">
        <div class="feature-card reveal">
          <div class="feature-icon">✅</div>
          <h3>Verified Profiles</h3>
          <p>Every profile is manually verified to ensure authenticity and trustworthiness within the community.</p>
        </div>
        <div class="feature-card reveal">
          <div class="feature-icon">🔒</div>
          <h3>Secure Communication</h3>
          <p>End-to-end encrypted messaging ensures your conversations remain private and safe.</p>
        </div>
        <div class="feature-card reveal">
          <div class="feature-icon">🛡️</div>
          <h3>Privacy Protection</h3>
          <p>Control who can view your profile. Your personal information stays protected at all times.</p>
        </div>
        <div class="feature-card reveal">
          <div class="feature-icon">💡</div>
          <h3>Smart Matchmaking</h3>
          <p>Our algorithm suggests compatible matches based on values, background, and preferences.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works -->
  <section class="section hiw-section">
    <div class="container">
      <div class="section-header text-center">
        <div class="badge">Simple Process</div>
        <h2>How It Works</h2>
        <p>Find your life partner in just four simple steps — respectful, easy, and community-focused.</p>
        <div class="divider"></div>
      </div>
      <div class="steps-grid">
        <div class="step-card reveal">
          <div class="step-number">1</div>
          <h3>Register Your Profile</h3>
          <p>Create your free profile with personal, professional, and family details in just a few minutes.</p>
        </div>
        <div class="step-card reveal">
          <div class="step-number">2</div>
          <h3>Complete Your Details</h3>
          <p>Add hobbies, expectations, and upload photos to make your profile stand out to potential families.</p>
        </div>
        <div class="step-card reveal">
          <div class="step-number">3</div>
          <h3>Search Suitable Matches</h3>
          <p>Use advanced filters to search profiles by age, location, education, occupation, and more.</p>
        </div>
        <div class="step-card reveal">
          <div class="step-number">4</div>
          <h3>Connect with Families</h3>
          <p>Send interest, exchange contact details, and connect with families directly through our platform.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Trust Section -->
  <section class="trust-section">
    <div class="container">
      <div class="section-header text-center" style="margin-bottom:48px;">
        <div class="badge"
          style="background:rgba(255,255,255,0.1);color:var(--gold-light);border-color:rgba(255,255,255,0.2);">Community
          First</div>
        <h2 style="color:#fff;">Deeply Rooted in Bouddh Values</h2>
        <p style="color:rgba(255,255,255,0.65);">We are more than a matrimonial platform — we are a community bridge.
        </p>
        <div class="divider"></div>
      </div>
      <div class="trust-grid">
        <div class="trust-card">
          <div class="trust-icon">☸️</div>
          <h3>Buddhist Traditions</h3>
          <p>Our platform respects and honours the values of the Buddha Dhamma and Ambedkarite Buddhist movement.</p>
        </div>
        <div class="trust-card">
          <div class="trust-icon">🏠</div>
          <h3>Trusted by Families</h3>
          <p>Families across Maharashtra, MP, UP and beyond trust BouddhMatrimony for reliable match-finding.</p>
        </div>
        <div class="trust-card">
          <div class="trust-icon">🤝</div>
          <h3>Community Values</h3>
          <p>Focused on social equality, mutual respect, and shared cultural identity as our core principles.</p>
        </div>
        <div class="trust-card">
          <div class="trust-icon">💛</div>
          <h3>Cultural Compatibility</h3>
          <p>Match filters include community background, cultural preferences, and family values for best compatibility.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Success Stories -->
  <section class="section" id="profiles">
    <div class="container">
      <div class="section-header text-center">
        <div class="badge">Real Couples</div>
        <h2>Success Stories</h2>
        <p>Thousands of couples found their life partner through BouddhMatrimony. Here are some heartwarming stories.
        </p>
        <div class="divider"></div>
      </div>
      <div class="stories-grid">
        <div class="story-card reveal">
          <div class="story-img" style="font-size:3.5rem;">💑</div>
          <div class="story-body">
            <div class="story-names">Rahul & Priya Kamble</div>
            <div class="story-location">📍 Pune, Maharashtra • Married 2023</div>
            <div class="story-quote">Thanks to BouddhMatrimony, we found the perfect match within our community. The
              platform made everything so easy and trustworthy.</div>
          </div>
        </div>
        <div class="story-card reveal">
          <div class="story-img" style="font-size:3.5rem;background:linear-gradient(135deg,#FFF0F0,#E8F0FF);">💑</div>
          <div class="story-body">
            <div class="story-names">Amit & Sunita Meshram</div>
            <div class="story-location">📍 Nagpur, Maharashtra • Married 2024</div>
            <div class="story-quote">Our families connected beautifully through this platform. We deeply appreciate the
              focus on Buddhist values and community respect.</div>
          </div>
        </div>
        <div class="story-card reveal">
          <div class="story-img" style="font-size:3.5rem;background:linear-gradient(135deg,#FFF8E8,#FFF0F0);">💑</div>
          <div class="story-body">
            <div class="story-names">Suresh & Kavita Gaikwad</div>
            <div class="story-location">📍 Mumbai, Maharashtra • Married 2024</div>
            <div class="story-quote">The verified profiles gave us confidence. We met, our families liked each other,
              and now we are happily married. Jai Bhim!</div>
          </div>
        </div>
      </div>
      <div style="text-align:center;margin-top:40px;">
        <a href="register.html" class="btn btn-primary btn-lg">Share Your Story 💝</a>
      </div>
    </div>
  </section>

  <!-- Packages Preview -->
  <section class="section packages-section">
    <div class="container">
      <div class="section-header text-center">
        <div class="badge">Pricing Plans</div>
        <h2>Choose Your Plan</h2>
        <p>Flexible packages designed to suit every need. Start free, upgrade anytime.</p>
        <div class="divider"></div>
      </div>
      <div class="packages-grid">
        <div class="package-card reveal">
          <div class="package-name">Basic Plan</div>
          <div class="package-price"><sup>₹</sup>0</div>
          <div class="package-duration">Free Forever</div>
          <ul class="package-features">
            <li><span class="check">✓</span> Create your profile</li>
            <li><span class="check">✓</span> 10 profile views/month</li>
            <li><span class="check">✓</span> Basic search filters</li>
            <li><span class="cross">✗</span> Direct messaging</li>
            <li><span class="cross">✗</span> Contact details access</li>
          </ul>
          <a href="register.html" class="btn btn-outline btn-buy" data-plan="Basic"
            style="width:100%;justify-content:center">Get Started Free</a>
        </div>
        <div class="package-card featured reveal">
          <div class="package-badge">Popular</div>
          <div class="package-name">Premium Plan</div>
          <div class="package-price"><sup>₹</sup>999</div>
          <div class="package-duration">Per 3 Months</div>
          <ul class="package-features">
            <li><span class="check">✓</span> Unlimited profile views</li>
            <li><span class="check">✓</span> Direct messaging</li>
            <li><span class="check">✓</span> Contact details access</li>
            <li><span class="check">✓</span> Priority listing</li>
            <li><span class="check">✓</span> Advanced filters</li>
          </ul>
          <button class="btn btn-primary btn-buy" data-plan="Premium" style="width:100%;justify-content:center">Buy
            Premium →</button>
        </div>
        <div class="package-card reveal">
          <div class="package-name">VIP Plan</div>
          <div class="package-price"><sup>₹</sup>2499</div>
          <div class="package-duration">Per 6 Months</div>
          <ul class="package-features">
            <li><span class="check">✓</span> All Premium features</li>
            <li><span class="check">✓</span> Profile highlight</li>
            <li><span class="check">✓</span> Featured placement</li>
            <li><span class="check">✓</span> Dedicated support</li>
            <li><span class="check">✓</span> Horoscope matching</li>
          </ul>
          <button class="btn btn-gold btn-buy" data-plan="VIP" style="width:100%;justify-content:center">Buy VIP
            ⭐</button>
        </div>
      </div>
      <div style="text-align:center;margin-top:32px;">
        <a href="packages.html" class="btn btn-outline">View Full Comparison →</a>
      </div>
    </div>
  </section>

  <!-- Statistics -->
  <section class="stats-section">
    <div class="container">
      <div class="stats-grid">
        <div class="stat-item">
          <span class="stat-number" data-count="50000" data-suffix="+">0</span>
          <div class="stat-label">Total Members</div>
        </div>
        <div class="stat-item">
          <span class="stat-number" data-count="12500" data-suffix="+">0</span>
          <div class="stat-label">Verified Profiles</div>
        </div>
        <div class="stat-item">
          <span class="stat-number" data-count="3200" data-suffix="+">0</span>
          <div class="stat-label">Successful Marriages</div>
        </div>
        <div class="stat-item">
          <span class="stat-number" data-count="28">0</span>
          <div class="stat-label">States Covered</div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Banner -->
  <section class="section" style="background:var(--bg-light);">
    <div class="container text-center">
      <div class="badge">Join Today</div>
      <h2 style="margin-bottom:16px;">Begin Your Journey to a<br>Meaningful Union</h2>
      <p style="max-width:480px;margin:0 auto 32px;">Join thousands of Bouddh families who have trusted us to find their
        most important life companion.</p>
      <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
        <a href="register.html" class="btn btn-primary btn-lg">Register Free 🙏</a>
        <a href="about.html" class="btn btn-outline btn-lg">Learn More</a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <h3>🙏 BouddhMatrimony</h3>
          <p>Dedicated matrimonial platform for the Bouddh (Buddhist) community. Connecting hearts with respect,
            tradition, and shared values.</p>
          <div class="social-links">
            <a href="#" class="social-link" aria-label="Facebook">f</a>
            <a href="#" class="social-link" aria-label="Instagram">📷</a>
            <a href="#" class="social-link" aria-label="WhatsApp">💬</a>
            <a href="#" class="social-link" aria-label="YouTube">▶</a>
          </div>
        </div>
        <div class="footer-col">
          <h4>Quick Links</h4>
          <ul class="footer-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="about.html">About Us</a></li>
            <li><a href="packages.html">Packages</a></li>
            <li><a href="contact.html">Contact Us</a></li>
            <li><a href="register.html">Register Free</a></li>
            <li><a href="login.html">Login</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Community</h4>
          <ul class="footer-links">
            <li><a href="#">Success Stories</a></li>
            <li><a href="#">Browse Profiles</a></li>
            <li><a href="#">Search Profiles</a></li>
            <li><a href="#">Community Blog</a></li>
            <li><a href="#">Bouddh Events</a></li>
          </ul>
        </div>
        <div class="footer-col footer-contact">
          <h4>Contact Us</h4>
          <p>📍 Office: Nagpur, Maharashtra, India</p>
          <p>📧 info@bouddhmatrimony.com</p>
          <p>📞 +91 98765 43210</p>
          <p>⏰ Mon–Sat: 9AM – 6PM</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© 2024 BouddhMatrimony. All rights reserved. Jai Bhim 🙏</p>
        <ul class="footer-bottom-links">
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms & Conditions</a></li>
          <li><a href="#">Refund Policy</a></li>
        </ul>
      </div>
    </div>
  </footer>

  <button id="scrollTop" aria-label="Scroll to top">↑</button>

  <script src="assets/js/script.js"></script>
</body>

</html>