  <header class="header">
    <div class="container">
      <nav class="nav">
        <a href="{{ route('home') }}" class="nav-logo">
          <img src="assets/images/logo.png" alt="BouddhMatrimony" onerror="this.style.display='none'">
          <div><span>Express </span><span style="color:var(--accent)"> Matrimony</span></div>
        </a>
        <ul class="nav-links">
          <li><a href="{{ route('home') }}" class="active">Home</a></li>
          <li><a href="{{ route('about') }}">About Us</a></li>
          <li><a href="{{ route('packages') }}">Packages</a></li>
          <li><a href="{{ route('contact') }}">Contact Us</a></li>
        </ul>
        <div class="nav-actions">
          <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Login</a>
          <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register Free</a>
        </div>
        <button class="hamburger" id="hamburger" aria-label="Menu">
          <span></span><span></span><span></span>
        </button>
      </nav>
    </div>
    <div class="mobile-menu" id="mobileMenu">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('about') }}">About Us</a></li>
        <li><a href="{{ route('packages') }}">Packages</a></li>
        <li><a href="{{ route('contact') }}">Contact Us</a></li>
      </ul>
      <div class="mobile-actions">
        <a href="{{ route('login') }}" class="btn btn-outline btn-sm" style="flex:1;justify-content:center">Login</a>
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">Register</a>
      </div>
    </div>
  </header>