@extends('admin.layouts.app')

@section('title', 'Admin Setup')

@section('content')
<div class="card form-card">

    <div class="card-body">

        <h2 class="form-title">Create Super Admin 👑</h2>
        <p class="form-sub">Setup your first admin account</p>

        <form method="POST" action="{{ route('admin.register.post') }}">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter your name">
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Enter email">
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label class="form-label">Phone (Optional)</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Enter phone number">
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password">
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-rose">
                    Create Admin
                </button>
            </div>

        </form>

    </div>
</div>
@endsection