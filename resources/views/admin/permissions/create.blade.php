@extends('admin.layouts.app')

@section('title','Create Permission')

@section('content')

<div class="card form-card">
    <div class="card-body">

        <h2 class="form-title">Create Permission</h2>
        <p class="form-sub">Add new permission</p>

        <form method="POST" action="{{ route('admin.permissions.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Permission Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. manage_users">
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Display Name</label>
                <input type="text" name="display_name" class="form-control" placeholder="Manage Users">
                @error('display_name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Group</label>
                <input list="groups" name="group" class="form-control" placeholder="e.g. users">
                <datalist id="groups">
                    @foreach($groups as $group)
                        <option value="{{ $group }}">
                    @endforeach
                </datalist>
            </div>

            <button class="btn btn-rose">Create Permission</button>

        </form>

    </div>
</div>

@endsection