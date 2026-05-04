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

  @yield('content')

    </main>

  <!-- Footer -->
  @include('user.layouts.foot')

  <script src="{{ asset('assets/js/script.js') }}"></script>

</body>
</html>