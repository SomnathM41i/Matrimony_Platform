@extends('admin.layouts.app')

@section('title','Permissions')

@section('content')

<div class="page-header">
    <div class="page-header-inner">
        <div>
            <div class="page-eyebrow">Access Control</div>
            <h1 class="page-title">Permissions <em>List</em></h1>
            <p class="page-subtitle">Manage system permissions</p>
        </div>

        <a href="{{ route('admin.permissions.create') }}" class="btn btn-rose">
            <i class="fas fa-plus"></i> Add Permission
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">

        @foreach($permissions as $group => $perms)

            <div class="section-sep">
                <div class="section-sep-line"></div>
                <div class="section-sep-label">{{ $group ?? 'General' }}</div>
                <div class="section-sep-line"></div>
            </div>

            <div class="grid-3">

                @foreach($perms as $perm)
                    <div class="pill">
                        <i class="fas fa-key"></i>

                        <div style="flex:1;">
                            <strong>{{ $perm->display_name }}</strong>
                            <div class="user-cell-sub">{{ $perm->name }}</div>
                        </div>

                        <span class="badge badge-gold">
                            {{ $perm->roles_count }}
                        </span>

                        <a href="{{ route('admin.permissions.edit',$perm) }}" class="btn btn-ghost">
                            <i class="fas fa-pen"></i>
                        </a>

                        <form method="POST" action="{{ route('admin.permissions.destroy',$perm) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-ghost">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                    </div>
                @endforeach

            </div>

        @endforeach

    </div>
</div>

@endsection