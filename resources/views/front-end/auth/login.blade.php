@extends('front-end.layout.app')

@section('title', "Matrimony - Find Your Perfect Life Partner")

@section('content')

<!-- Auth Section -->
<section class="auth-section">
  <div style="width:100%;max-width:480px;">
    <!-- Auth Card -->
    <div class="auth-card">
      <div class="auth-logo">
        <img src="assets/images/logo.png" alt="BouddhMatrimony" onerror="this.style.display='none'">
        <h2>Welcome Back 🙏</h2>
        <p>Login to your BouddhMatrimony account</p>
      </div>

      <form id="loginForm">
        <div class="form-group">
          <label class="form-label">Email or Mobile Number *</label>
          <input type="text" class="form-control" name="email" placeholder="Enter email or mobile" required autocomplete="username">
        </div>

        <div class="form-group">
          <label class="form-label">Password *</label>
          <div style="position:relative;display:flex;align-items:center;">
            <input type="password" class="form-control" name="password" placeholder="Enter your password" required autocomplete="current-password" style="padding-right:52px;">
            <button type="button" class="toggle-pass" style="position:absolute;right:14px;background:none;border:none;cursor:pointer;font-size:1.1rem;padding:4px;">👁️</button>
          </div>
        </div>

        <div class="remember-row">
          <label class="checkbox-label">
            <input type="checkbox" name="remember">
            Remember me
          </label>
          <a href="#" style="font-size:0.9rem;color:var(--primary);font-weight:500;">Forgot Password?</a>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:1rem;padding:15px;">
          Login to Account →
        </button>
      </form>

      <div style="display:flex;align-items:center;gap:12px;margin:24px 0;">
        <div style="flex:1;height:1px;background:var(--border);"></div>
        <span style="font-size:0.82rem;color:var(--text-light);">OR</span>
        <div style="flex:1;height:1px;background:var(--border);"></div>
      </div>

      <!-- Social Login -->
      <div style="display:flex;gap:12px;">
        <button type="button" onclick="showToast('Google login coming soon!','info')" style="flex:1;display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;border:1.5px solid var(--border);border-radius:var(--radius-sm);background:#fff;cursor:pointer;font-size:0.9rem;font-weight:500;transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
          <span>G</span> Google
        </button>
        <button type="button" onclick="showToast('Facebook login coming soon!','info')" style="flex:1;display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;border:1.5px solid var(--border);border-radius:var(--radius-sm);background:#fff;cursor:pointer;font-size:0.9rem;font-weight:500;transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
          <span style="color:#1877f2;">f</span> Facebook
        </button>
      </div>

      <div class="auth-footer">
        Don't have an account? <a href="register.html">Register Free 🙏</a>
      </div>
    </div>

    <!-- Trust badges -->
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
