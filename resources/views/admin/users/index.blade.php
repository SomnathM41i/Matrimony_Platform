@extends('admin.layouts.app')

@section('title','Users')

@section('content')

<style>
    /* Impersonation floating bar */
    .impersonate-bar {
        position: fixed; bottom: 0; inset-inline: 0; z-index: 9998;
        background: linear-gradient(135deg, var(--rose), var(--rose-deep));
        color: #fff;
        display: flex; align-items: center; justify-content: space-between;
        padding: .75rem 2rem;
        box-shadow: 0 -4px 24px rgba(200,113,90,.35);
        font-size: .82rem; font-weight: 500;
        gap: 1rem;
    }
    .impersonate-bar strong { font-weight: 700; }
    .impersonate-bar a {
        display: inline-flex; align-items: center; gap: .375rem;
        background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.3);
        color: #fff; padding: .4rem .875rem; border-radius: 50px;
        font-size: .78rem; font-weight: 600; white-space: nowrap;
        transition: all .2s ease;
    }
    .impersonate-bar a:hover { background: rgba(255,255,255,.3); }
</style>

<div class="page-header">
    <div class="page-header-inner">
        <div>
            <div class="page-eyebrow">Members</div>
            <h1 class="page-title">Users <em>Management</em></h1>
            <p class="page-subtitle">Manage all users and accounts</p>
        </div>

        <a href="{{ route('admin.users.create') }}" class="btn btn-rose">
            <i class="fas fa-plus"></i> Add User
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <!-- FILTER -->
        <form method="GET" class="grid-3">

            <input type="text" name="search" class="form-control"
                   placeholder="Search name, email, phone..."
                   value="{{ request('search') }}">

            <select name="role" class="form-control">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role')==$role->name?'selected':'' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="active"    {{ request('status')=='active'    ?'selected':'' }}>Active</option>
                <option value="suspended" {{ request('status')=='suspended' ?'selected':'' }}>Suspended</option>
                <option value="banned"    {{ request('status')=='banned'    ?'selected':'' }}>Banned</option>
            </select>

            <button class="btn btn-rose">Apply</button>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>User</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Premium</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach($users as $user)
                    <tr>

                        <td>
                            <div class="user-cell">
                                <div class="user-cell-avatar" style="background:var(--rose-grad);">
                                    {{ strtoupper(substr($user->name,0,2)) }}
                                </div>
                                <div>
                                    <div class="user-cell-name">{{ $user->name }}</div>
                                    <div class="user-cell-sub">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="badge badge-{{ $user->account_status }}">
                                {{ ucfirst($user->account_status) }}
                            </span>
                        </td>

                        <td>{{ $user->role?->name }}</td>

                        <td>
                            @if($user->is_premium)
                                <span class="badge badge-premium"><i class="fas fa-crown"></i>&nbsp;Premium</span>
                            @else
                                <span class="badge badge-free">Free</span>
                            @endif
                        </td>

                        <td>
                            {{-- View --}}
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="btn btn-ghost" title="View">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="btn btn-ghost" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>

                            {{-- Assign Plan --}}
                            <a href="{{ route('admin.users.assign-plan', $user) }}"
                               class="btn btn-gold btn-sm" title="Assign Plan"
                               style="gap:.35rem;">
                                <i class="fas fa-crown"></i> Plan
                            </a>

                            {{-- Login as User --}}
                            @if($user->account_status === 'active' && !$user->isSuperAdmin())
                                <form method="POST"
                                      action="{{ route('admin.users.login-as', $user) }}"
                                      style="display:inline;"
                                      onsubmit="return confirm('Log in as {{ addslashes($user->name) }}?')">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-outline btn-sm" title="Login as User"
                                            style="gap:.35rem;">
                                        <i class="fas fa-right-to-bracket"></i> Login
                                    </button>
                                </form>
                            @endif

                            {{-- Delete --}}
                            <form action="{{ route('admin.users.destroy', $user) }}"
                                  method="POST" style="display:inline;"
                                  onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-ghost" title="Delete"
                                        style="color:var(--danger);">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}

    </div>
</div>

{{-- Impersonation bar (visible on FRONTEND layout, but shown here too for awareness) --}}
@if(session('impersonating'))
<!-- <div class="impersonate-bar">
    <span>
        <i class="fas fa-user-secret"></i>&nbsp;
        Browsing as <strong>{{ auth()->user()->name }}</strong>
        &nbsp;·&nbsp; Impersonated by <strong>{{ session('impersonator_name') }}</strong>
    </span>
    <a href="{{ route('admin.users.stop-impersonation') }}">
        <i class="fas fa-arrow-right-from-bracket"></i>
        Return to Admin Panel
    </a>
</div> -->
@endif

@endsection