@extends('admin.layouts.app')

@section('title','Settings')

@section('content')

<div class="page-header">
    <div class="page-header-inner">
        <div>
            <div class="page-eyebrow">System</div>
            <h1 class="page-title">Settings <em>Panel</em></h1>
        </div>
    </div>
</div>

<div class="grid-3">

@foreach($groups as $group)
    <a href="{{ route('admin.settings.group',$group) }}" class="card">
        <div class="card-body">
            <h3>{{ ucfirst($group) }}</h3>
            <p class="user-cell-sub">Manage {{ $group }} settings</p>
        </div>
    </a>
@endforeach

</div>

@endsection