@extends('admin.layouts.app')

@section('title', ucfirst($group))

@section('content')

<div class="card form-card">
    <div class="card-body">

        <h2 class="form-title">{{ ucfirst($group) }} Settings</h2>

        <form method="POST" action="{{ route('admin.settings.update',$group) }}">
            @csrf
            @method('PUT')

            @foreach($settings as $key => $setting)
                <div class="form-group">
                    <label class="form-label">{{ $key }}</label>

                    <input type="text"
                           name="{{ $key }}"
                           value="{{ $setting->value }}"
                           class="form-control">
                </div>
            @endforeach

            <button class="btn btn-rose">
                Save Settings
            </button>

        </form>

    </div>
</div>

@endsection