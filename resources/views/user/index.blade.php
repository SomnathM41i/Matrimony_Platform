@extends('user.layouts.app')

@section('title', "Matrimony - Find Your Perfect Life Partner")

@section('content')
<!-- HERO -->
  <section class="hero">
    <div class="hero-pattern"></div>

    <div class="container">
      <div class="hero-content">
        <div class="hero-badge">Trusted by Thousands of Families</div>

        <h1>
          Find Your Perfect <br>
          <span>Life Partner</span>
        </h1>

        <p>
          A modern matrimonial platform connecting individuals and families with trust,
          compatibility, and meaningful relationships.
        </p>

        <div class="hero-actions">
          <a href="register.html" class="btn btn-primary btn-lg">Register Free</a>
          <a href="#profiles" class="btn btn-outline btn-lg">Browse Profiles</a>
        </div>
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
          <div class="hero-stat-label">Successful Matches</div>
        </div>
      </div>
    </div>
  </section>

  <!-- SEARCH -->
  <section style="background:#f9fafb;padding:60px 0;">
    <div class="container">
      <div class="quick-search">
        <h3>Search Your Match</h3>

        <form>
          <div class="search-grid">

            <div class="form-group">
              <label>Looking for</label>
              <select class="form-control">
                <option>Bride</option>
                <option>Groom</option>
              </select>
            </div>

            <div class="form-group">
              <label>Age</label>
              <select class="form-control">
                <option>21–25</option>
                <option>26–30</option>
                <option>31–35</option>
                <option>36–40</option>
              </select>
            </div>

            <div class="form-group">
              <label>Religion</label>
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
              <label>Location</label>
              <select class="form-control">
                <option>Any</option>
                <option>Maharashtra</option>
                <option>Delhi</option>
                <option>Karnataka</option>
                <option>Gujarat</option>
              </select>
            </div>

            <div class="form-group">
              <label>Education</label>
              <select class="form-control">
                <option>Any</option>
                <option>Graduate</option>
                <option>Post Graduate</option>
                <option>Professional</option>
              </select>
            </div>

            <button class="btn btn-primary">Search</button>

          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- FEATURES -->
  <section class="section">
    <div class="container text-center">
      <h2>Why Choose Us</h2>
      <p>Everything you need to find the right partner</p>

      <div class="features-grid">

        <div class="feature-card">
          <h3>Verified Profiles</h3>
          <p>Profiles are verified to ensure authenticity and trust.</p>
        </div>

        <div class="feature-card">
          <h3>Secure Platform</h3>
          <p>Your data and conversations are fully protected.</p>
        </div>

        <div class="feature-card">
          <h3>Smart Matching</h3>
          <p>Get matches based on preferences and compatibility.</p>
        </div>

        <div class="feature-card">
          <h3>Easy Communication</h3>
          <p>Connect easily with potential matches.</p>
        </div>

      </div>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="section">
    <div class="container text-center">

      <h2>How It Works</h2>

      <div class="steps-grid">

        <div class="step-card">
          <h3>1. Register</h3>
          <p>Create your profile in minutes.</p>
        </div>

        <div class="step-card">
          <h3>2. Complete Profile</h3>
          <p>Add details to improve matches.</p>
        </div>

        <div class="step-card">
          <h3>3. Search</h3>
          <p>Find profiles that match your preferences.</p>
        </div>

        <div class="step-card">
          <h3>4. Connect</h3>
          <p>Start conversations and build relationships.</p>
        </div>

      </div>
    </div>
  </section>

  <!-- STORIES -->
  <section class="section" id="profiles">
    <div class="container text-center">

      <h2>Success Stories</h2>

      <div class="stories-grid">

        <div class="story-card">
          <h3>Rahul & Priya</h3>
          <p>We found each other through this platform. Smooth and trustworthy experience.</p>
        </div>

        <div class="story-card">
          <h3>Amit & Sunita</h3>
          <p>Our families connected easily. Highly recommended!</p>
        </div>

        <div class="story-card">
          <h3>Suresh & Kavita</h3>
          <p>Simple process and genuine profiles made it successful.</p>
        </div>

      </div>

    </div>
  </section>

  <!-- PRICING -->
  <section class="section">
    <div class="container text-center">

      <h2>Plans</h2>

      <div class="packages-grid">

        <div class="package-card">
          <h3>Free</h3>
          <p>Basic access</p>
        </div>

        <div class="package-card featured">
          <h3>Premium</h3>
          <p>Full features</p>
        </div>

        <div class="package-card">
          <h3>VIP</h3>
          <p>Priority support</p>
        </div>

      </div>

    </div>
  </section>

  <!-- CTA -->
  <section class="section text-center">
    <div class="container">

      <h2>Start Your Journey Today</h2>

      <p>Join thousands who found their life partner here.</p>

      <a href="register.html" class="btn btn-primary btn-lg">Register Now</a>

    </div>
  </section>
@endsection