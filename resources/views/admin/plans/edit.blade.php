@extends('admin.layouts.app')

@section('title', 'Edit Plan — ' . $plan->name)

@section('content')

<div class="page-header">
    <div class="page-header-inner">
        <div>
            <div class="page-eyebrow">Business › Plans</div>
            <h1 class="page-title">Edit <em>{{ $plan->name }}</em></h1>
            <p class="page-subtitle">
                {{ $plan->subscriptions()->where('is_active', true)->count() }} active subscriber(s) on this plan
            </p>
        </div>
        <div style="display:flex;gap:.75rem;">
            <span class="badge {{ $plan->is_active ? 'badge-active' : 'badge-inactive' }}" style="padding:.4rem 1rem;font-size:.8rem;">
                {{ $plan->is_active ? 'Active' : 'Inactive' }}
            </span>
            @if($plan->is_featured)
                <span class="badge badge-premium" style="padding:.4rem 1rem;font-size:.8rem;">⭐ Featured</span>
            @endif
        </div>
    </div>
</div>

@if($errors->any())
    <div style="margin-bottom:1.5rem;padding:1rem 1.25rem;border-radius:12px;
                background:rgba(184,76,76,.1);border:1px solid rgba(184,76,76,.3);
                color:var(--danger);font-size:.83rem;">
        <strong><i class="fas fa-circle-exclamation"></i> Please fix the following errors:</strong>
        <ul style="margin:.5rem 0 0 1.25rem;">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

@include('admin.plans._form')

@endsection