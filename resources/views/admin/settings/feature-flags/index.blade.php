@extends('admin.layouts.app')

@section('title','Feature Flags')

@section('content')

<div class="page-header">
    <div class="page-header-inner">
        <div>
            <div class="page-eyebrow">System</div>
            <h1 class="page-title">Feature <em>Flags</em></h1>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <table>
            <thead>
            <tr>
                <th>Feature</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            @foreach($flags as $flag)
                <tr>

                    <td>
                        <strong>{{ $flag->label }}</strong>
                        <div class="user-cell-sub">{{ $flag->key }}</div>
                    </td>

                    <td>
                        @if($flag->is_enabled)
                            <span class="badge badge-active">Enabled</span>
                        @else
                            <span class="badge badge-inactive">Disabled</span>
                        @endif
                    </td>

                    <td>

                        <form method="POST" action="{{ route('admin.feature-flags.toggle',$flag) }}">
                            @csrf
                            <button class="btn btn-ghost">
                                Toggle
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.feature-flags.destroy',$flag) }}">
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

@endsection