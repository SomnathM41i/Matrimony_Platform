@extends('admin.layouts.app')

@section('title', 'Settings - Social')

@section('content')
<div class="page-header">
    <div class="page-header-inner">
        <div>
            <div class="page-eyebrow">System</div>
            <h1 class="page-title">Social <em>Settings</em></h1>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            <p class="text-muted">Settings for this group will be managed here.</p>
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>
@endsection
