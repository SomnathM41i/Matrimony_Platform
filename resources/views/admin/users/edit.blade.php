@extends('admin.layouts.app')

@section('title','Edit User')

@section('content')

<div class="page-header">
    <div class="page-header-inner">
        <div>
            <div class="page-eyebrow">Members</div>
            <h1 class="page-title">Edit <em>User</em></h1>
            <p class="page-subtitle">Update user details</p>
        </div>
    </div>
</div>

<div class="card form-card">
    <div class="card-body">

        <h2 class="form-title">{{ $user->name }}</h2>
        <p class="form-sub">Modify user account information</p>

        {{-- ✅ SUCCESS --}}
        @if(session('success'))
            <div class="badge badge-active mb-2">
                {{ session('success') }}
            </div>
        @endif

        {{-- ✅ GLOBAL ERRORS --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-2">
                @foreach ($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update',$user) }}">
            @csrf
            @method('PUT')

            {{-- ROLE --}}
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role_id" class="form-control" required>
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- NAME --}}
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name"
                       value="{{ old('name',$user->name) }}"
                       class="form-control" required>
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email"
                       value="{{ old('email',$user->email) }}"
                       class="form-control" required>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- PHONE --}}
            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="text" name="phone"
                       value="{{ old('phone',$user->phone) }}"
                       class="form-control">
                @error('phone')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- GENDER --}}
            <div class="form-group">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-control">
                    <option value="">Select</option>
                    <option value="male" {{ old('gender',$user->gender)=='male'?'selected':'' }}>Male</option>
                    <option value="female" {{ old('gender',$user->gender)=='female'?'selected':'' }}>Female</option>
                    <option value="other" {{ old('gender',$user->gender)=='other'?'selected':'' }}>Other</option>
                </select>
                @error('gender')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- ACCOUNT STATUS --}}
            <div class="form-group">
                <label class="form-label">Account Status</label>
                <select name="account_status" class="form-control" required>
                    <option value="active" {{ old('account_status',$user->account_status)=='active'?'selected':'' }}>Active</option>
                    <option value="pending" {{ old('account_status',$user->account_status)=='pending'?'selected':'' }}>Pending</option>
                    <option value="suspended" {{ old('account_status',$user->account_status)=='suspended'?'selected':'' }}>Suspended</option>
                    <option value="banned" {{ old('account_status',$user->account_status)=='banned'?'selected':'' }}>Banned</option>
                </select>
                @error('account_status')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div class="form-group">
                <label class="form-label">New Password (Optional)</label>
                <input type="password" name="password" class="form-control">
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- CONFIRM PASSWORD --}}
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            {{-- ACTIONS --}}
            <div class="form-actions">

                <button type="submit" class="btn btn-rose">
                    <i class="fas fa-save"></i> Update User
                </button>

                <a href="{{ route('admin.users.show',$user) }}" class="btn btn-outline">
                    Cancel
                </a>

            </div>

        </form>

    </div>
</div>

@endsection