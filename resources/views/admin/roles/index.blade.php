@extends('admin.layouts.app')

@section('title','Roles')

@section('content')

<div class="page-header">
    <div class="page-header-inner">
        <div>
            <div class="page-eyebrow">Access Control</div>
            <h1 class="page-title">Roles <em>Management</em></h1>
        </div>

        <a href="{{ route('admin.roles.create') }}" class="btn btn-rose">
            <i class="fas fa-plus"></i> Add Role
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Role</th>
                    <th>Users</th>
                    <th>Permissions</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                @foreach($roles as $role)
                    <tr>

                        <td>
                            <strong>{{ $role->display_name }}</strong>
                            <div class="user-cell-sub">{{ $role->name }}</div>
                        </td>

                        <td>{{ $role->users_count }}</td>

                        <td>
                            <span class="badge badge-gold">
                                {{ $role->permissions->count() }} permissions
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('admin.roles.edit',$role) }}" class="btn btn-ghost">
                                <i class="fas fa-pen"></i>
                            </a>

                            <form method="POST" action="{{ route('admin.roles.destroy',$role) }}" style="display:inline;">
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

    </div>
</div>

@endsection