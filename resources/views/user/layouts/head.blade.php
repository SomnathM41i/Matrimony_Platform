<header class="header">
  <div class="container">
    <nav class="nav">

      {{-- LOGO --}}
      <a href="{{ route('home') }}" class="nav-logo">
        <img src="{{ asset('assets/images/logo.png') }}" alt="ExpressMatrimony" onerror="this.style.display='none'">
        <div>
          <span>Express </span>
          <span style="color:var(--accent)">Matrimony</span>
        </div>
      </a>

      {{-- DESKTOP NAV --}}
      <ul class="nav-links">
        <li><a href="{{ route('home') }}"     class="{{ request()->routeIs('home')     ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ route('about') }}"    class="{{ request()->routeIs('about')    ? 'active' : '' }}">About Us</a></li>
        <li><a href="{{ route('packages') }}" class="{{ request()->routeIs('packages') ? 'active' : '' }}">Packages</a></li>
        <li><a href="{{ route('contact') }}"  class="{{ request()->routeIs('contact')  ? 'active' : '' }}">Contact Us</a></li>
      </ul>

      {{-- RIGHT SIDE --}}
      <div class="nav-actions">

        @guest
          <a href="{{ route('user.login') }}"    class="btn btn-outline btn-sm">Login</a>
          <a href="{{ route('user.register') }}" class="btn btn-primary btn-sm">Register Free</a>
        @endguest

        @auth
          <div style="display:flex;align-items:center;gap:12px;">

            {{-- DASHBOARD LINK --}}
            <a href="{{ route('user.dashboard') }}" class="btn btn-outline btn-sm">Dashboard</a>

            {{-- USER AVATAR + DROPDOWN --}}
            <div style="position:relative;" id="userMenuWrap">

              <button type="button" id="userMenuToggle"
                      class="btn btn-primary btn-sm"
                      style="display:flex;align-items:center;gap:8px;"
                      aria-haspopup="true"
                      aria-expanded="false"
                      aria-controls="userMenu">

                {{--
                  BUG FIX: Auth::user()->primaryPhoto is a hasOne relationship.
                  Accessing ->url directly will throw if the relation returns null.
                  Use optional() or the null-safe operator with a fallback.
                --}}
                @php
                  $navPhotoUrl = Auth::user()->relationLoaded('primaryPhoto')
                    ? (Auth::user()->primaryPhoto?->url ?? asset('assets/images/default-user.png'))
                    : asset('assets/images/default-user.png');
                @endphp

                <img src="{{ $navPhotoUrl }}"
                     alt="{{ Auth::user()->name }}"
                     style="width:28px;height:28px;border-radius:50%;object-fit:cover;"
                     onerror="this.src='{{ asset('assets/images/default-user.png') }}'">

                <span>{{ Str::words(Auth::user()->name, 1, '') ?: 'Account' }}</span>
                <span style="font-size:0.6rem;opacity:0.7;" aria-hidden="true">▼</span>
              </button>

              {{-- DROPDOWN MENU --}}
              <div id="userMenu"
                   role="menu"
                   aria-labelledby="userMenuToggle"
                   style="
                     position:absolute;
                     right:0;
                     top:calc(100% + 8px);
                     background:var(--card-bg, #fff);
                     border:1px solid var(--border);
                     border-radius:12px;
                     box-shadow:0 8px 32px rgba(0,0,0,0.12);
                     padding:8px 0;
                     min-width:210px;
                     display:none;
                     z-index:1000;
                   ">

                {{-- Profile header inside dropdown --}}
                <div style="padding:12px 16px 10px;border-bottom:1px solid var(--border);margin-bottom:4px;">
                  <p style="margin:0;font-size:0.88rem;font-weight:600;color:var(--text);">
                    {{ Auth::user()->name }}
                  </p>
                  <p style="margin:0;font-size:0.75rem;color:var(--text-muted);">
                    {{ Auth::user()->email }}
                  </p>
                  @if(Auth::user()->profile)
                    <div style="margin-top:6px;height:4px;background:var(--border);border-radius:4px;overflow:hidden;">
                      <div style="height:100%;width:{{ Auth::user()->profile->completion_percentage ?? 0 }}%;background:var(--primary);border-radius:4px;"></div>
                    </div>
                    <p style="margin:3px 0 0;font-size:0.7rem;color:var(--text-muted);">
                      Profile {{ Auth::user()->profile->completion_percentage ?? 0 }}% complete
                    </p>
                  @endif
                </div>

                {{--
                  MY PROFILE → user.profile.me (ProfileController@myProfile)
                  Shows the full profile details page
                --}}
                <a href="{{ route('user.profile.me') }}" class="dropdown-item" role="menuitem">
                  👤 My Profile
                </a>

                {{--
                  EDIT PROFILE → user.profile.edit (ProfileController@editProfile)
                  Redirects to the first incomplete setup step
                --}}
                <a href="{{ route('user.profile.edit') }}" class="dropdown-item" role="menuitem">
                  ✏️ Edit Profile
                </a>

                <a href="{{ route('user.subscription.show') }}" class="dropdown-item" role="menuitem">
                  💎 My Plan
                </a>

                <a href="{{ route('user.settings.index') }}" class="dropdown-item" role="menuitem">
                  ⚙️ Settings
                </a>

                <hr style="margin:6px 0;border:none;border-top:1px solid var(--border);">

                <form method="POST" action="{{ route('user.logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item" role="menuitem"
                          style="width:100%;text-align:left;background:none;border:none;cursor:pointer;color:var(--danger,#dc2626);">
                    🚪 Logout
                  </button>
                </form>

              </div>
            </div>

          </div>
        @endauth

      </div>

      {{-- MOBILE HAMBURGER --}}
      <button class="hamburger" id="hamburger" aria-label="Open menu" aria-expanded="false" aria-controls="mobileMenu">
        <span></span><span></span><span></span>
      </button>

    </nav>
  </div>

  {{-- MOBILE MENU --}}
  <div class="mobile-menu" id="mobileMenu" aria-hidden="true">
    <ul>
      <li><a href="{{ route('home') }}">Home</a></li>
      <li><a href="{{ route('about') }}">About Us</a></li>
      <li><a href="{{ route('packages') }}">Packages</a></li>
      <li><a href="{{ route('contact') }}">Contact Us</a></li>
    </ul>

    <div class="mobile-actions">
      @guest
        <a href="{{ route('user.login') }}"    class="btn btn-outline btn-sm" style="flex:1;justify-content:center;">Login</a>
        <a href="{{ route('user.register') }}" class="btn btn-primary btn-sm"  style="flex:1;justify-content:center;">Register</a>
      @endguest

      @auth
        <a href="{{ route('user.profile.me') }}"   class="btn btn-outline btn-sm" style="flex:1;justify-content:center;">My Profile</a>
        <a href="{{ route('user.dashboard') }}"    class="btn btn-primary btn-sm"  style="flex:1;justify-content:center;">Dashboard</a>
        <form method="POST" action="{{ route('user.logout') }}" style="flex:1;">
          @csrf
          <button type="submit" class="btn btn-outline btn-sm" style="width:100%;">Logout</button>
        </form>
      @endauth
    </div>
  </div>
</header>


<script>
(function () {
  // ── User dropdown ──────────────────────────────────────────────────────────
  const toggle = document.getElementById('userMenuToggle');
  const menu   = document.getElementById('userMenu');

  if (toggle && menu) {
    // Open / close on button click
    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      const open = menu.style.display === 'block';
      menu.style.display = open ? 'none' : 'block';
      toggle.setAttribute('aria-expanded', String(!open));
    });

    // Close when clicking anywhere outside the whole wrap
    document.addEventListener('click', function (e) {
      if (!document.getElementById('userMenuWrap').contains(e.target)) {
        menu.style.display = 'none';
        toggle.setAttribute('aria-expanded', 'false');
      }
    });

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && menu.style.display === 'block') {
        menu.style.display = 'none';
        toggle.setAttribute('aria-expanded', 'false');
        toggle.focus();
      }
    });
  }

  // ── Mobile hamburger ───────────────────────────────────────────────────────
  const hamburger   = document.getElementById('hamburger');
  const mobileMenu  = document.getElementById('mobileMenu');

  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function () {
      const open = mobileMenu.classList.toggle('open');
      hamburger.setAttribute('aria-expanded', String(open));
      mobileMenu.setAttribute('aria-hidden', String(!open));
    });
  }
})();
</script>


<style>
.dropdown-item {
  display: block;
  padding: 9px 16px;
  font-size: 0.875rem;
  color: var(--text);
  text-decoration: none;
  transition: background 0.15s, color 0.15s;
  white-space: nowrap;
}
.dropdown-item:hover {
  background: var(--bg-light, #f5f5f5);
  color: var(--primary);
}
</style>