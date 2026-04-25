@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- PAGE HEADER -->
<div class="page-header">
    <div class="page-header-inner">
        <div>
            <div class="page-eyebrow">Admin Panel</div>
            <h1 class="page-title">Dashboard <em>Overview</em></h1>
            <p class="page-subtitle">Welcome back {{ auth()->user()->name }} 😌</p>
        </div>
        <div class="date-chip">
            <span id="currentDate"></span>
        </div>
    </div>
</div>

<!-- STATS GRID -->
<div class="stats-grid">

    <!-- Total Users -->
    <div class="stat-card rose">
        <div class="stat-label">Total Members</div>
        <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
        <div class="stat-sub">↑ {{ $stats['new_users_this_month'] }} this month</div>
    </div>

    <!-- Active Users -->
    <div class="stat-card green">
        <div class="stat-label">Active Profiles</div>
        <div class="stat-value">{{ number_format($stats['active_users']) }}</div>
        <div class="stat-sub">
            {{ $stats['total_users'] > 0 ? round(($stats['active_users']/$stats['total_users'])*100,1) : 0 }}% active
        </div>
    </div>

    <!-- Premium -->
    <div class="stat-card gold">
        <div class="stat-label">Premium Users</div>
        <div class="stat-value">{{ number_format($stats['premium_users']) }}</div>
        <div class="stat-sub">Subscribed users</div>
    </div>

    <!-- Pending Profiles -->
    <div class="stat-card rose">
        <div class="stat-label">Pending Profiles</div>
        <div class="stat-value">{{ $stats['pending_profiles'] }}</div>
        <div class="stat-sub">Need approval</div>
    </div>

    <!-- Reports -->
    <div class="stat-card teal">
        <div class="stat-label">Pending Reports</div>
        <div class="stat-value">{{ $stats['pending_reports'] }}</div>
        <div class="stat-sub">User issues</div>
    </div>

    <!-- Contacts -->
    <div class="stat-card blue">
        <div class="stat-label">Open Contacts</div>
        <div class="stat-value">{{ $stats['open_contacts'] }}</div>
        <div class="stat-sub">Support queries</div>
    </div>

    <!-- Revenue -->
    <div class="stat-card gold">
        <div class="stat-label">Revenue (Monthly)</div>
        <div class="stat-value">₹{{ number_format($stats['revenue_month']) }}</div>
        <div class="stat-sub">Current month</div>
    </div>

</div>


<!-- RECENT USERS -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Users</h3>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Joined</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($recentUsers as $user)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-cell-avatar">
                                    {{ strtoupper(substr($user->name,0,2)) }}
                                </div>
                                <div class="user-cell-name">{{ $user->name }}</div>
                            </div>
                        </td>

                        <td>{{ $user->email }}</td>

                        <td>
                            @if($user->profile_status == 'approved')
                                <span class="badge badge-verified">Approved</span>
                            @elseif($user->profile_status == 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @else
                                <span class="badge badge-inactive">Inactive</span>
                            @endif
                        </td>

                        <td>{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center;">No users found</td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>


<!-- EXTRA INFO -->
<div class="grid-2">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pending Reports</h3>
        </div>
        <div class="card-body">
            <h2>{{ $pendingReports }}</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pending Contacts</h3>
        </div>
        <div class="card-body">
            <h2>{{ $pendingContacts }}</h2>
        </div>
    </div>

</div>

@endsection


@push('scripts')
<script>
document.getElementById("currentDate").innerText =
    new Date().toLocaleDateString('en-IN', {
        weekday:'long',
        day:'numeric',
        month:'long',
        year:'numeric'
    });
</script>
@endpush