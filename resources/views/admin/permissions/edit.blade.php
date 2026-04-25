@extends('admin.layouts.app')

@section('title','Edit Permission')

@section('content')

<div class="card form-card">
    <div class="card-body">

        <h2 class="form-title">{{ $permission->display_name }}</h2>
        <p class="form-sub">Update permission details</p>

        <form method="POST" action="{{ route('admin.permissions.update',$permission) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Display Name</label>
                <input type="text" name="display_name"
                       value="{{ old('display_name',$permission->display_name) }}"
                       class="form-control">
                @error('display_name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Group</label>
                <input list="groups" name="group"
                       value="{{ old('group',$permission->group) }}"
                       class="form-control">

                <datalist id="groups">
                    @foreach($groups as $group)
                        <option value="{{ $group }}">
                    @endforeach
                </datalist>
            </div>

            <div class="form-actions">

                <button class="btn btn-rose">
                    <i class="fas fa-save"></i> Update
                </button>

                <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline">
                    Cancel
                </a>

            </div>

        </form>

    </div>
</div>

@endsection