@auth
<aside class="sidebar">

    <div class="sidebar-header">
        <div class="logo-mark"><i class="fas fa-heart"></i></div>
        <div class="brand">
            <h4>Vivah</h4>
            <span>Admin Panel</span>
        </div>
    </div>

    <!-- DASHBOARD -->
    <nav class="nav-section">
        <div class="nav-section-title">Overview</div>
        <ul class="nav">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}"
                   href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-house"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.analytics.index') ? 'active' : '' }}"
                   href="{{ route('admin.analytics.index') }}">
                    <i class="fas fa-chart-line"></i> Analytics
                </a>
            </li>

        </ul>
    </nav>

    <!-- MEMBERS -->
    <nav class="nav-section">
        <div class="nav-section-title">Members</div>
        <ul class="nav">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                   href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i> All Members
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.trashed') ? 'active' : '' }}"
                   href="{{ route('admin.users.trashed') }}">
                    <i class="fas fa-trash"></i> Trashed Users
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users.export') }}">
                    <i class="fas fa-download"></i> Export Users
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
                   href="{{ route('admin.roles.index') }}">
                    <i class="fas fa-shield-halved"></i> Roles
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}"
                   href="{{ route('admin.permissions.index') }}">
                    <i class="fas fa-key"></i> Permissions
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.rm.*') ? 'active' : '' }}"
                   href="{{ route('admin.rm.index') }}">
                    <i class="fas fa-user-tie"></i> Relationship Managers
                </a>
            </li>

        </ul>
    </nav>

    <!-- MASTER DATA -->

    @php
        $lookupNav = [
            ['slug' => 'religions',            'route' => 'religions',            'label' => 'Religions',       'icon' => 'fa-om'],
            ['slug' => 'castes',               'route' => 'castes',               'label' => 'Castes',          'icon' => 'fa-layer-group'],
            ['slug' => 'sub-castes',           'route' => 'sub_castes',           'label' => 'Sub Castes',      'icon' => 'fa-sitemap'],
            ['slug' => 'gotras',               'route' => 'gotras',               'label' => 'Gotras',          'icon' => 'fa-tree'],
            ['slug' => 'communities',          'route' => 'communities',          'label' => 'Communities',     'icon' => 'fa-users'],
            ['slug' => 'mother-tongues',       'route' => 'mother_tongues',       'label' => 'Mother Tongues',  'icon' => 'fa-language'],
            ['slug' => 'rashis',               'route' => 'rashis',               'label' => 'Rashis',          'icon' => 'fa-star'],
            ['slug' => 'nakshatras',           'route' => 'nakshatras',           'label' => 'Nakshatras',      'icon' => 'fa-moon'],
            ['slug' => 'education-levels',     'route' => 'education_levels',     'label' => 'Education Levels','icon' => 'fa-graduation-cap'],
            ['slug' => 'professions',          'route' => 'professions',          'label' => 'Professions',     'icon' => 'fa-briefcase'],
            ['slug' => 'annual-income-ranges', 'route' => 'annual_income_ranges', 'label' => 'Income Ranges',   'icon' => 'fa-indian-rupee-sign'],
            ['slug' => 'countries',            'route' => 'countries',            'label' => 'Countries',       'icon' => 'fa-globe'],
            ['slug' => 'states',               'route' => 'states',               'label' => 'States',          'icon' => 'fa-map'],
            ['slug' => 'cities',               'route' => 'cities',               'label' => 'Cities',          'icon' => 'fa-city'],
            ['slug' => 'areas',                'route' => 'areas',                'label' => 'Areas',           'icon' => 'fa-location-dot'],
        ];

        $currentRoute = request()->route()?->getName();
    @endphp

    <nav class="nav-section">
        <div class="nav-section-title">Master Data</div>
        <ul class="nav">
            @foreach($lookupNav as $item)
            <li class="nav-item">
                <a
                    href="{{ route('admin.lookups.' . $item['route'] . '.index') }}"
                    class="nav-link {{ $currentRoute === 'admin.lookups.' . $item['route'] . '.index' ? 'active' : '' }}"
                >
                    <i class="fas {{ $item['icon'] }}"></i>
                    {{ $item['label'] }}
                </a>
            </li>
            @endforeach
        </ul>
    </nav>
  

    <!-- MATRIMONY -->
    <nav class="nav-section">
        <div class="nav-section-title">Matrimony</div>
        <ul class="nav">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.matrimony.profiles*') ? 'active' : '' }}"
                   href="{{ route('admin.matrimony.profiles') }}">
                    <i class="fas fa-id-card"></i> Profiles
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.matrimony.interests') ? 'active' : '' }}"
                   href="{{ route('admin.matrimony.interests') }}">
                    <i class="fas fa-heart"></i> Interests
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.matrimony.shortlists') ? 'active' : '' }}"
                   href="{{ route('admin.matrimony.shortlists') }}">
                    <i class="fas fa-bookmark"></i> Shortlists
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.matrimony.compatibility') ? 'active' : '' }}"
                   href="{{ route('admin.matrimony.compatibility') }}">
                    <i class="fas fa-scale-balanced"></i> Compatibility
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.matrimony.photo-requests') ? 'active' : '' }}"
                   href="{{ route('admin.matrimony.photo-requests') }}">
                    <i class="fas fa-image"></i> Photo Requests
                </a>
            </li>

        </ul>
    </nav>

    <!-- BUSINESS -->
    <nav class="nav-section">
        <div class="nav-section-title">Business</div>
        <ul class="nav">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}"
                   href="{{ route('admin.plans.index') }}">
                    <i class="fas fa-crown"></i> Plans
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.analytics.revenue') ? 'active' : '' }}"
                   href="{{ route('admin.analytics.revenue') }}">
                    <i class="fas fa-rupee-sign"></i> Revenue
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.analytics.subscriptions') ? 'active' : '' }}"
                   href="{{ route('admin.analytics.subscriptions') }}">
                    <i class="fas fa-receipt"></i> Subscriptions
                </a>
            </li>

        </ul>
    </nav>

    <!-- ANALYTICS -->
    <nav class="nav-section">
        <div class="nav-section-title">Analytics</div>
        <ul class="nav">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.analytics.users') ? 'active' : '' }}"
                   href="{{ route('admin.analytics.users') }}">
                    <i class="fas fa-chart-bar"></i> User Analytics
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.analytics.engagement') ? 'active' : '' }}"
                   href="{{ route('admin.analytics.engagement') }}">
                    <i class="fas fa-fire"></i> Engagement
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.analytics.snapshots') ? 'active' : '' }}"
                   href="{{ route('admin.analytics.snapshots') }}">
                    <i class="fas fa-camera"></i> Snapshots
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.analytics.export') }}">
                    <i class="fas fa-file-export"></i> Export
                </a>
            </li>

        </ul>
    </nav>

    <!-- NOTIFICATIONS -->
    <nav class="nav-section">
        <div class="nav-section-title">Notifications</div>
        <ul class="nav">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.notifications.index') ? 'active' : '' }}"
                   href="{{ route('admin.notifications.index') }}">
                    <i class="fas fa-bell"></i> All Notifications
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.notifications.create') ? 'active' : '' }}"
                   href="{{ route('admin.notifications.create') }}">
                    <i class="fas fa-paper-plane"></i> Send Notification
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.notifications.templates') ? 'active' : '' }}"
                   href="{{ route('admin.notifications.templates') }}">
                    <i class="fas fa-file-code"></i> Templates
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.notifications.push-logs') ? 'active' : '' }}"
                   href="{{ route('admin.notifications.push-logs') }}">
                    <i class="fas fa-list-check"></i> Push Logs
                </a>
            </li>

        </ul>
    </nav>

    <!-- CMS -->
    <nav class="nav-section">
        <div class="nav-section-title">CMS</div>
        <ul class="nav">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.cms.pages.*') ? 'active' : '' }}"
                   href="{{ route('admin.cms.pages.index') }}">
                    <i class="fas fa-file"></i> Pages
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.cms.menus.*') ? 'active' : '' }}"
                   href="{{ route('admin.cms.menus.index') }}">
                    <i class="fas fa-bars"></i> Menus
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.cms.banners.*') ? 'active' : '' }}"
                   href="{{ route('admin.cms.banners.index') }}">
                    <i class="fas fa-image"></i> Banners
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.cms.testimonials.*') ? 'active' : '' }}"
                   href="{{ route('admin.cms.testimonials.index') }}">
                    <i class="fas fa-quote-left"></i> Testimonials
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.cms.faqs.*') ? 'active' : '' }}"
                   href="{{ route('admin.cms.faqs.index') }}">
                    <i class="fas fa-circle-question"></i> FAQs
                </a>
            </li>

        </ul>
    </nav>

    <!-- SEO -->
    <nav class="nav-section">
        <div class="nav-section-title">SEO</div>
        <ul class="nav">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.seo.settings.*') ? 'active' : '' }}"
                   href="{{ route('admin.seo.settings.index') }}">
                    <i class="fas fa-magnifying-glass-chart"></i> SEO Settings
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.seo.sitemap*') ? 'active' : '' }}"
                   href="{{ route('admin.seo.sitemap') }}">
                    <i class="fas fa-sitemap"></i> Sitemap
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.seo.robots*') ? 'active' : '' }}"
                   href="{{ route('admin.seo.robots') }}">
                    <i class="fas fa-robot"></i> Robots.txt
                </a>
            </li>

        </ul>
    </nav>

    <!-- SUPPORT -->
    <nav class="nav-section">
        <div class="nav-section-title">Support</div>
        <ul class="nav">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"
                   href="{{ route('admin.contacts.index') }}">
                    <i class="fas fa-envelope"></i> Contacts
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                   href="{{ route('admin.reports.index') }}">
                    <i class="fas fa-flag"></i> Reports
                </a>
            </li>

        </ul>
    </nav>

    <!-- SYSTEM -->
    <nav class="nav-section">
        <div class="nav-section-title">System</div>
        <ul class="nav">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                   href="{{ route('admin.settings.index') }}">
                    <i class="fas fa-gear"></i> Settings
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.feature-flags.*') ? 'active' : '' }}"
                   href="{{ route('admin.feature-flags.index') }}">
                    <i class="fas fa-toggle-on"></i> Feature Flags
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}"
                   href="{{ route('admin.logs.index') }}">
                    <i class="fas fa-file-lines"></i> Activity Logs
                </a>
            </li>

            <li class="nav-item">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="nav-link" style="background:none;border:none;width:100%;text-align:left;">
                        <i class="fas fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </li>

        </ul>
    </nav>

</aside>
@endauth