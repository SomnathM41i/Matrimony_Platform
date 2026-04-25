@extends('admin.layouts.app')

@section('title','Create Role')

@section('content')

<div class="card form-card">
    <div class="card-body">

        <h2 class="form-title">Create Role</h2>

        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf

            <input type="text" name="name" class="form-control" placeholder="Role Name">
            <input type="text" name="display_name" class="form-control" placeholder="Display Name">
            <textarea name="description" class="form-control" placeholder="Description"></textarea>

            <div class="divider"></div>

            <h4>Permissions</h4>

            @foreach($permissions as $group => $perms)
                <div style="margin-bottom:10px;">
                    <strong>{{ ucfirst($group) }}</strong>

                    @foreach($perms as $perm)
                        <div>
                            <input type="checkbox" name="permissions[]" value="{{ $perm->id }}">
                            {{ $perm->display_name }}
                        </div>
                    @endforeach
                </div>
            @endforeach

            <button class="btn btn-rose">Create Role</button>

        </form>

    </div>
</div>

@endsection