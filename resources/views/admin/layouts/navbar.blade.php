@auth
<nav class="navbar">
    <div class="navbar-left">
        <button class="mobile-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>

        <div class="breadcrumb">
            <span>Vivah</span>
            <span>›</span>
            <span class="current">@yield('title', 'Dashboard')</span>
        </div>
    </div>

    <div class="navbar-right">
        <button class="icon-btn" id="themeToggle">
            <i class="fas fa-moon"></i>
        </button>

        <a href="{{ route('admin.notifications.index') }}" class="icon-btn">
            <i class="fas fa-bell"></i>
        </a>

        <div class="user-chip">
            <div class="avatar">
                {{ strtoupper(substr(auth()->user()->name,0,2)) }}
            </div>
            <span class="user-chip-name">
                {{ auth()->user()->name }}
            </span>
        </div>
    </div>
</nav>
@endauth