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
{{--
    ============================================================
    SNIPPET — add this banner to your FRONTEND layout (e.g.
    resources/views/layouts/app.blade.php) so the admin sees
    the impersonation notice while browsing as the user.
    ============================================================
--}}
 
@if(session('impersonating'))
    <div class="fixed bottom-0 inset-x-0 z-50 bg-amber-500 text-white text-sm font-medium
                flex items-center justify-between px-6 py-3 shadow-lg">
        <span>
            👤 You are browsing as <strong>{{ auth()->user()->name }}</strong>
            (impersonated by <strong>{{ session('impersonator_name') }}</strong>)
        </span>
        <a href="{{ route('admin.users.stop-impersonation') }}"
           class="ml-4 bg-white text-amber-700 font-semibold text-xs px-3 py-1 rounded-full hover:bg-amber-100 transition">
            ✕ Return to Admin Panel
        </a>
    </div>
@endif
  @yield('content')

    </main>

  <!-- Footer -->
  @include('user.layouts.foot')

  <script src="{{ asset('assets/js/script.js') }}"></script>

</body>
</html>