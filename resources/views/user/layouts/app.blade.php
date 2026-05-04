<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta name="description"
    content="Find your perfect life partner on a trusted matrimonial platform designed for everyone. Safe, secure, and smart matchmaking.">
  <meta name="keywords"
    content="matrimony, matchmaking, shaadi, marriage, life partner, bride, groom, online matrimony">

  <meta property="og:title" content="Matrimony — Find Your Perfect Match">
  <meta property="og:description" content="A trusted matrimonial platform for everyone">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <title>@yield('title')</title>
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

  {{-- Push impersonation bar height so page content is not hidden under it --}}
  @if(session('impersonating'))
  <style>
    body { padding-bottom: 64px; }

    /* ── Impersonation bar ─────────────────────────────────────── */
    #impersonation-bar {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 9999;
      height: 64px;
      background: linear-gradient(90deg, #b45309 0%, #d97706 50%, #b45309 100%);
      background-size: 200% 100%;
      animation: imp-shimmer 4s linear infinite;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 0 24px;
      box-shadow: 0 -4px 24px rgba(180, 83, 9, 0.45);
      border-top: 2px solid rgba(255,255,255,0.2);
    }

    @keyframes imp-shimmer {
      0%   { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }

    /* Left — admin identity */
    .imp-left {
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 0;
      flex: 1;
    }

    .imp-badge {
      flex-shrink: 0;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: rgba(255,255,255,0.22);
      border: 1.5px solid rgba(255,255,255,0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
    }

    .imp-text {
      display: flex;
      flex-direction: column;
      min-width: 0;
    }

    .imp-title {
      font-size: 0.78rem;
      font-weight: 800;
      color: #fff;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      opacity: 0.85;
      line-height: 1;
      margin-bottom: 3px;
    }

    .imp-subtitle {
      font-size: 0.85rem;
      color: #fff;
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .imp-subtitle strong {
      font-weight: 700;
    }

    /* Pulse dot */
    .imp-dot {
      flex-shrink: 0;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #fef08a;
      box-shadow: 0 0 0 0 rgba(254,240,138,0.7);
      animation: imp-pulse 1.8s ease-in-out infinite;
    }

    @keyframes imp-pulse {
      0%   { box-shadow: 0 0 0 0 rgba(254,240,138,0.7); }
      70%  { box-shadow: 0 0 0 7px rgba(254,240,138,0); }
      100% { box-shadow: 0 0 0 0 rgba(254,240,138,0); }
    }

    /* Right — return button */
    .imp-return-btn {
      flex-shrink: 0;
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 8px 18px;
      background: #fff;
      color: #92400e;
      font-size: 0.82rem;
      font-weight: 700;
      border-radius: 50px;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: background 0.15s, transform 0.15s, box-shadow 0.15s;
      box-shadow: 0 2px 10px rgba(0,0,0,0.15);
      white-space: nowrap;
    }

    .imp-return-btn:hover {
      background: #fef3c7;
      transform: translateY(-1px);
      box-shadow: 0 4px 16px rgba(0,0,0,0.18);
    }

    .imp-return-btn:active {
      transform: translateY(0);
    }

    .imp-return-icon {
      font-size: 0.85rem;
    }

    /* Mobile adjustments */
    @media (max-width: 560px) {
      #impersonation-bar {
        padding: 0 14px;
        gap: 10px;
      }
      .imp-subtitle { font-size: 0.78rem; }
      .imp-return-btn { padding: 7px 13px; font-size: 0.76rem; }
      .imp-dot { display: none; }
    }
  </style>
  @endif
</head>

<body>

  <!-- Loader -->
  <div id="loader">
    <div class="loader-inner">
      <div class="loader-ring"></div>
      <div class="loader-text">Matrimony</div>
    </div>
  </div>

  <!-- Header -->
  @include('user.layouts.head')

  <main>
    @yield('content')
  </main>

  <!-- Footer -->
  @include('user.layouts.foot')

  @if(session('impersonating'))
    <div id="impersonation-bar" role="status" aria-live="polite" aria-label="Admin impersonation active">

      {{-- Left: who is being impersonated and by whom --}}
      <div class="imp-left">
        <div class="imp-dot" aria-hidden="true"></div>
        <div class="imp-badge" aria-hidden="true">👤</div>
        <div class="imp-text">
          <span class="imp-title">Admin Preview Mode</span>
          <span class="imp-subtitle">
            Viewing as <strong>{{ auth()->user()->name }}</strong>
            &nbsp;·&nbsp;
            Admin: <strong>{{ session('impersonator_name') }}</strong>
          </span>
        </div>
      </div>

      {{-- Right: one-click escape back to admin panel --}}
      <a href="{{ route('admin.users.stop-impersonation') }}"
         class="imp-return-btn"
         title="End impersonation and return to admin panel">
        <span class="imp-return-icon" aria-hidden="true">✕</span>
        Return to Admin Panel
      </a>

    </div>
  @endif

  <script src="{{ asset('assets/js/script.js') }}"></script>

</body>
</html>