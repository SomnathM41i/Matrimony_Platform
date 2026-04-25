@extends('admin.layouts.app')

@section('title','Edit Role')

@section('content')

<div class="card form-card">
    <div class="card-body">

        <h2 class="form-title">{{ $role->display_name }}</h2>

        <form method="POST" action="{{ route('admin.roles.update',$role) }}">
            @csrf
            @method('PUT')

            <input type="text" name="display_name"
                   value="{{ $role->display_name }}"
                   class="form-control">

            <textarea name="description" class="form-control">
                {{ $role->description }}
            </textarea>

            <div class="divider"></div>

            <h4>Permissions</h4>

            @foreach($permissions as $group => $perms)
                <div style="margin-bottom:10px;">
                    <strong>{{ ucfirst($group) }}</strong>

                    @foreach($perms as $perm)
                        <div>
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $perm->id }}"
                                   {{ in_array($perm->id,$rolePermIds) ? 'checked' : '' }}>
                            {{ $perm->display_name }}
                        </div>
                    @endforeach
                </div>
            @endforeach

            <button class="btn btn-rose">
                Update Role
            </button>

        </form>

    </div>
</div>

@endsection