@extends('user.layouts.app')

@section('title', "Matrimony - Find Your Perfect Life Partner")

@section('content')

<section class="auth-section">
  <div style="width:100%;max-width:480px;">
    
    <div class="auth-card">
      <div class="auth-logo">
        <img src="{{ asset('assets/images/logo.png') }}" alt="BouddhMatrimony" onerror="this.style.display='none'">
        <h2>Welcome Back 🙏</h2>
        <p>Login to your Matrimony account</p>
      </div>

      {{-- SUCCESS MESSAGE --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      {{-- LOGIN FORM --}}
      <form method="POST" action="{{ route('user.login') }}">
        @csrf

        {{-- EMAIL --}}
        <div class="form-group">
          <label class="form-label">Email *</label>
          <input type="email"
                 class="form-control @error('email') is-invalid @enderror"
                 name="email"
                 value="{{ old('email') }}"
                 placeholder="Enter email"
                 required
                 autocomplete="username">

          @error('email')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        {{-- PASSWORD --}}
        <div class="form-group">
          <label class="form-label">Password *</label>
          <div style="position:relative;display:flex;align-items:center;">
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password"
                   placeholder="Enter your password"
                   required
                   autocomplete="current-password"
                   style="padding-right:52px;">

              <button type="button" class="toggle-pass"
                onclick="togglePassword(this)"
                style="position:absolute; right:14px; z-index:10; background:none; border:none; cursor:pointer; font-size:1.1rem;">
                👁️
              </button>
          </div>

          @error('password')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        {{-- REMEMBER --}}
        <div class="remember-row">
          <label class="checkbox-label">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            Remember me
          </label>

          <a href="{{ route('user.password.request') }}"
             style="font-size:0.9rem;color:var(--primary);font-weight:500;">
             Forgot Password?
          </a>
        </div>

        {{-- SUBMIT --}}
        <button type="submit" class="btn btn-primary"
          style="width:100%;justify-content:center;font-size:1rem;padding:15px;">
          Login to Account →
        </button>
      </form>

      {{-- FOOTER --}}
      <div class="auth-footer">
        Don't have an account?
        <a href="{{ route('user.register') }}">Register Free 🙏</a>
      </div>
    </div>

    {{-- TRUST BADGES --}}
    <div style="display:flex;justify-content:center;gap:24px;margin-top:28px;flex-wrap:wrap;">
      <div style="display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--text-muted);">
        <span style="color:var(--primary);">🔒</span> Secure Login
      </div>
      <div style="display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--text-muted);">
        <span style="color:var(--primary);">✅</span> Verified Platform
      </div>
      <div style="display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--text-muted);">
        <span style="color:var(--primary);">🛡️</span> Privacy Protected
      </div>
    </div>

  </div>
</section>


@endsection