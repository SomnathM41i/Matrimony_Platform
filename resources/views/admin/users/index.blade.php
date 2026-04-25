@extends('admin.layouts.app')

@section('title','Users')

@section('content')

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
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
                <option value="banned">Banned</option>
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
                                <div class="user-cell-avatar">
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
                                <span class="badge badge-premium">Premium</span>
                            @else
                                <span class="badge badge-free">Free</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.users.show',$user) }}" class="btn btn-ghost">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('admin.users.edit',$user) }}" class="btn btn-ghost">
                                <i class="fas fa-pen"></i>
                            </a>

                            <form action="{{ route('admin.users.destroy',$user) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-ghost">
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

@endsection