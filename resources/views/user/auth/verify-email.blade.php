@extends('user.layouts.app')

@section('title', 'Verify Your Email')

@section('content')

<div class="auth-section">
    <div class="auth-card text-center">

        <div class="verify-icon" style="font-size:48px;color:var(--primary);margin-bottom:16px;">
            <i class="fas fa-envelope-circle-check"></i>
        </div>

        <h3 style="margin-bottom:10px;">Verify Your Email</h3>

        <p style="margin-bottom:18px;">
            We've sent a verification link to:
        </p>

        <div class="badge" style="margin-bottom:18px;">
            {{ auth()->user()->email }}
        </div>

        <p style="margin-bottom:24px;">
            Click the link in your email to verify your account and unlock all features.
            If you don’t see it, check your spam folder.
        </p>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success" style="text-align:left;margin-bottom:20px;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Actions --}}
        <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:20px;">

            {{-- Resend --}}
            <form method="POST" action="{{ route('user.verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-paper-plane"></i> Resend Verification Email
                </button>
            </form>

            {{-- SKIP BUTTON (NEW) --}}
            <a href="{{ route('user.dashboard') }}" class="btn btn-outline btn-sm w-100">
                <i class="fas fa-forward"></i> Skip for Now & Go to Dashboard
            </a>

        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('user.logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;cursor:pointer;font-size:.8rem;color:var(--text-muted);">
                <i class="fas fa-right-from-bracket"></i> Use a different account
            </button>
        </form>

    </div>
</div>

@endsection