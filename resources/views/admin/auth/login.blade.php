@extends('admin.layouts.app')

@section('title', 'Admin Login')

@section('content')

<div class="auth-wrapper">

    <div class="card form-card auth-card">

        <div class="card-body">

            <h2 class="form-title">Welcome Back 👋</h2>
            <p class="form-sub">Login to your admin panel</p>

            <!-- SUCCESS -->
            @if(session('success'))
                <div class="badge badge-active" style="margin-bottom:10px;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ERROR -->
            @if($errors->any())
                <div class="form-error" style="margin-bottom:10px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email"
                           class="form-control"
                           value="{{ old('email') }}"
                           placeholder="Enter your email">
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                           class="form-control"
                           placeholder="Enter your password">
                </div>

                <!-- Remember -->
                <div class="form-group" style="display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember" style="font-size:.8rem;">Remember me</label>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-rose">
                        Login
                    </button>

                    <a href="{{ route('admin.password.request') }}" style="font-size:.75rem;">
                        Forgot password?
                    </a>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection