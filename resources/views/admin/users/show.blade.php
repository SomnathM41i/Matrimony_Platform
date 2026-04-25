@extends('admin.layouts.app')

@section('title','User Details')

@section('content')

<div class="card">
    <div class="card-body">

        <div class="user-cell" style="margin-bottom:1rem;">
            <div class="user-cell-avatar">
                {{ strtoupper(substr($user->name,0,2)) }}
            </div>

            <div>
                <h3>{{ $user->name }}</h3>
                <div class="user-cell-sub">{{ $user->email }}</div>
            </div>
        </div>

        <div class="grid-3">

            <div class="pill">
                <i class="fas fa-user"></i>
                Role: <strong>{{ $user->role?->name }}</strong>
            </div>

            <div class="pill">
                <i class="fas fa-check"></i>
                Status: <strong>{{ $user->account_status }}</strong>
            </div>

            <div class="pill">
                <i class="fas fa-crown"></i>
                {{ $user->is_premium ? 'Premium' : 'Free' }}
            </div>

        </div>

        <a href="{{ route('admin.users.edit',$user) }}" class="btn btn-rose">
            Edit User
        </a>

    </div>
</div>

@endsection