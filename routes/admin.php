<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\AdminRegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\FeatureFlagController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\SeoSettingController;
use App\Http\Controllers\Admin\ContactFormController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\RelationshipManagerController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\LookupController;
use App\Http\Controllers\Admin\LookupApiController;
use App\Http\Controllers\Admin\MatrimonyController;

use Illuminate\Http\Request; 

/*
|--------------------------------------------------------------------------
| Admin Panel Routes - Laravel 12
|--------------------------------------------------------------------------
| Prefix  : /admin
| Name    : admin.
|--------------------------------------------------------------------------
*/
use Illuminate\Support\Facades\Artisan;
Route::get('/setup-db', function () {
    Artisan::call('migrate', ['--force' => true]);
    Artisan::call('db:seed', [
        '--class' => 'RolePermissionSeeder'
    ]);

    return "Database setup completed";
});

Route::prefix('admin')->name('admin.')->group(function () {

    // ─────────────────────────────────────────────────────────────────
    // GUEST ROUTES (unauthenticated)
    // ─────────────────────────────────────────────────────────────────
    Route::middleware('admin.guest')->group(function () {

        // Login
        Route::get('/login',           [AdminLoginController::class, 'showLogin'])->name('login');
        Route::post('/login',          [AdminLoginController::class, 'login'])->name('login.post');

        // Register (first Super Admin setup — disable after first run)
        Route::get('/register',        [AdminRegisterController::class, 'showRegister'])->name('register');
        Route::post('/register',       [AdminRegisterController::class, 'register'])->name('register.post');

        // Password Reset
        Route::get('/forgot-password', [AdminLoginController::class, 'showForgotPassword'])->name('password.request');
        Route::post('/forgot-password',[AdminLoginController::class, 'sendResetLink'])->name('password.email');
        Route::get('/reset-password/{token}', [AdminLoginController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [AdminLoginController::class, 'resetPassword'])->name('password.update');
    });

    // ─────────────────────────────────────────────────────────────────
    // AUTHENTICATED ADMIN ROUTES
    // ─────────────────────────────────────────────────────────────────
    Route::middleware(['admin.auth', 'admin.active'])->group(function () {

        // Logout
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

        // ─── User Management ──────────────────────────────────────────
        Route::middleware('admin.permission:manage_users')->group(function () {
            Route::resource('users', UserController::class);
            Route::patch('users/{user}/toggle-status',  [UserController::class, 'toggleStatus'])->name('users.toggle-status');
            Route::patch('users/{user}/toggle-premium', [UserController::class, 'togglePremium'])->name('users.toggle-premium');
            Route::post('users/{user}/verify-email',    [UserController::class, 'verifyEmail'])->name('users.verify-email');
            Route::get('users/{user}/activity',         [UserController::class, 'activity'])->name('users.activity');
            Route::get('users/{user}/profile',          [UserController::class, 'profile'])->name('users.profile');
            Route::delete('users/{user}/force-delete',  [UserController::class, 'forceDelete'])->name('users.force-delete');
            Route::patch('users/{user}/restore',        [UserController::class, 'restore'])->name('users.restore');
            Route::get('users/trashed',                 [UserController::class, 'trashed'])->name('users.trashed');
            Route::get('users/export',                  [UserController::class, 'export'])->name('users.export');
        });

        // ─── Roles & Permissions ──────────────────────────────────────
        Route::middleware('admin.permission:manage_roles')->group(function () {
            Route::resource('roles', RoleController::class);
            Route::post('roles/{role}/sync-permissions', [RoleController::class, 'syncPermissions'])->name('roles.sync-permissions');
            Route::resource('permissions', PermissionController::class)->except(['show']);
        });

        // ─── Matrimony / Matchmaking ──────────────────────────────────
        Route::middleware('admin.permission:manage_matrimony')->prefix('matrimony')->name('matrimony.')->group(function () {
            Route::get('profiles',                          [MatrimonyController::class, 'profiles'])->name('profiles');
            Route::get('profiles/{user}',                   [MatrimonyController::class, 'profileDetail'])->name('profiles.show');
            Route::patch('profiles/{user}/approve',         [MatrimonyController::class, 'approveProfile'])->name('profiles.approve');
            Route::patch('profiles/{user}/reject',          [MatrimonyController::class, 'rejectProfile'])->name('profiles.reject');
            Route::get('interests',                         [MatrimonyController::class, 'interests'])->name('interests');
            Route::get('shortlists',                        [MatrimonyController::class, 'shortlists'])->name('shortlists');
            Route::get('compatibility',                     [MatrimonyController::class, 'compatibility'])->name('compatibility');
            Route::get('photo-requests',                    [MatrimonyController::class, 'photoRequests'])->name('photo-requests');
        });

        // ─── Subscriptions & Payments ─────────────────────────────────
        Route::resource('plans', SubscriptionController::class, ['as' => 'admin.subscriptions'])->names([
            'index'   => 'plans.index',
            'create'  => 'plans.create',
            'store'   => 'plans.store',
            'edit'    => 'plans.edit',
            'update'  => 'plans.update',
            'destroy' => 'plans.destroy',
        ]);

        // ─── Relationship Managers ────────────────────────────────────
        Route::middleware('admin.permission:manage_rm')->prefix('relationship-managers')->name('rm.')->group(function () {
            Route::get('/',                         [RelationshipManagerController::class, 'index'])->name('index');
            Route::get('{rm}',                      [RelationshipManagerController::class, 'show'])->name('show');
            Route::post('{rm}/assign/{user}',       [RelationshipManagerController::class, 'assignUser'])->name('assign');
            Route::delete('{rm}/unassign/{user}',   [RelationshipManagerController::class, 'unassignUser'])->name('unassign');
            Route::get('{rm}/interactions',         [RelationshipManagerController::class, 'interactions'])->name('interactions');
            Route::post('{rm}/interactions',        [RelationshipManagerController::class, 'logInteraction'])->name('interactions.log');
            Route::get('{rm}/assigned-users',       [RelationshipManagerController::class, 'assignedUsers'])->name('assigned-users');
        });

        // ─── Notifications ────────────────────────────────────────────
        Route::middleware('admin.permission:manage_notifications')
            ->prefix('notifications')
            ->name('notifications.')
            ->group(function () {

                Route::get('/', [NotificationController::class, 'index'])->name('index');
                Route::get('create', [NotificationController::class, 'create'])->name('create');

                Route::post('send-bulk', [NotificationController::class, 'sendBulk'])->name('send-bulk');
                Route::post('send/{user}', [NotificationController::class, 'sendToUser'])->name('send-user');

                Route::get('templates', [NotificationController::class, 'templates'])->name('templates');

                Route::resource('templates', NotificationController::class)
                    ->only(['store', 'edit', 'update', 'destroy'])
                    ->names([
                        'store'   => 'templates.store',
                        'edit'    => 'templates.edit',
                        'update'  => 'templates.update',
                        'destroy' => 'templates.destroy',
                    ]);

                Route::get('push-logs', [NotificationController::class, 'pushLogs'])->name('push-logs');
            });

        // ─── CMS Pages ────────────────────────────────────────────────
        Route::middleware('admin.permission:manage_cms')->prefix('cms')->name('cms.')->group(function () {
            Route::resource('pages',        CmsPageController::class);
            Route::patch('pages/{page}/toggle', [CmsPageController::class, 'toggle'])->name('pages.toggle');
            Route::post('pages/reorder',    [CmsPageController::class, 'reorder'])->name('pages.reorder');

            Route::resource('menus',        MenuController::class)->except(['show']);
            Route::post('menus/reorder',    [MenuController::class, 'reorder'])->name('menus.reorder');
            Route::resource('menu-locations', MenuController::class)->only(['index','store','update','destroy'])->names([
                'index'   => 'menu-locations.index',
                'store'   => 'menu-locations.store',
                'update'  => 'menu-locations.update',
                'destroy' => 'menu-locations.destroy',
            ]);

            Route::resource('banners',      BannerController::class);
            Route::patch('banners/{banner}/toggle', [BannerController::class, 'toggle'])->name('banners.toggle');

            Route::resource('testimonials', TestimonialController::class);
            Route::patch('testimonials/{testimonial}/toggle', [TestimonialController::class, 'toggle'])->name('testimonials.toggle');

            Route::resource('faqs',         FaqController::class);
            Route::patch('faqs/{faq}/toggle', [FaqController::class, 'toggle'])->name('faqs.toggle');
            Route::post('faqs/reorder',     [FaqController::class, 'reorder'])->name('faqs.reorder');
        });

        // ─── SEO ──────────────────────────────────────────────────────
        Route::middleware('admin.permission:manage_seo')
            ->prefix('seo')
            ->name('seo.')
            ->group(function () {

                Route::resource('settings', SeoSettingController::class)->except(['show']);

                Route::get('sitemap', [SeoSettingController::class, 'sitemap'])->name('sitemap');
                Route::post('sitemap/generate', [SeoSettingController::class, 'generateSitemap'])->name('sitemap.generate');

              
                Route::resource('sitemap-entries', SeoSettingController::class)
                    ->only(['store', 'update', 'destroy'])
                    ->names([
                        'store'   => 'sitemap-entries.store',
                        'update'  => 'sitemap-entries.update',
                        'destroy' => 'sitemap-entries.destroy',
                    ]);

                Route::get('robots', [SeoSettingController::class, 'robots'])->name('robots');
                Route::post('robots', [SeoSettingController::class, 'updateRobots'])->name('robots.update');
            });

        // ─── Contact & Reports ────────────────────────────────────────
        Route::middleware('admin.permission:manage_support')->group(function () {
            Route::get('contacts',                      [ContactFormController::class, 'index'])->name('contacts.index');
            Route::get('contacts/{contact}',            [ContactFormController::class, 'show'])->name('contacts.show');
            Route::patch('contacts/{contact}/reply',    [ContactFormController::class, 'reply'])->name('contacts.reply');
            Route::delete('contacts/{contact}',         [ContactFormController::class, 'destroy'])->name('contacts.destroy');

            Route::get('reports',                       [ReportController::class, 'index'])->name('reports.index');
            Route::get('reports/{report}',              [ReportController::class, 'show'])->name('reports.show');
            Route::patch('reports/{report}/resolve',    [ReportController::class, 'resolve'])->name('reports.resolve');
            Route::patch('reports/{report}/dismiss',    [ReportController::class, 'dismiss'])->name('reports.dismiss');
        });

        // ─── Analytics ────────────────────────────────────────────────
        Route::middleware('admin.permission:view_analytics')->prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/',                 [AnalyticsController::class, 'index'])->name('index');
            Route::get('users',             [AnalyticsController::class, 'users'])->name('users');
            Route::get('subscriptions',     [AnalyticsController::class, 'subscriptions'])->name('subscriptions');
            Route::get('revenue',           [AnalyticsController::class, 'revenue'])->name('revenue');
            Route::get('engagement',        [AnalyticsController::class, 'engagement'])->name('engagement');
            Route::get('snapshots',         [AnalyticsController::class, 'snapshots'])->name('snapshots');
            Route::post('snapshots/create', [AnalyticsController::class, 'createSnapshot'])->name('snapshots.create');
            Route::get('export',            [AnalyticsController::class, 'export'])->name('export');
        });

        // ─── Settings ─────────────────────────────────────────────────
        Route::middleware('admin.permission:manage_settings')->prefix('settings')->name('settings.')->group(function () {
            Route::get('/',                 [SettingController::class, 'index'])->name('index');
            Route::get('{group}',           [SettingController::class, 'group'])->name('group');
            Route::post('{group}',          [SettingController::class, 'update'])->name('update');
            Route::post('bulk',             [SettingController::class, 'bulkUpdate'])->name('bulk-update');
        });

        // ─── Feature Flags ────────────────────────────────────────────
        Route::middleware('admin.permission:manage_settings')->prefix('feature-flags')->name('feature-flags.')->group(function () {
            Route::get('/',                         [FeatureFlagController::class, 'index'])->name('index');
            Route::patch('{flag}/toggle',           [FeatureFlagController::class, 'toggle'])->name('toggle');
            Route::put('{flag}',                    [FeatureFlagController::class, 'update'])->name('update');
            Route::post('/',                        [FeatureFlagController::class, 'store'])->name('store');
            Route::delete('{flag}',                 [FeatureFlagController::class, 'destroy'])->name('destroy');
        });

        // ─── Lookup / Master Data ─────────────────────────────────────
        // Route::middleware('admin.permission:manage_lookups')->prefix('lookups')->name('lookups.')->group(function () {
        //     $lookupEntities = [
        //         'religions', 'castes', 'sub-castes', 'gotras', 'communities',
        //         'mother-tongues', 'rashis', 'nakshatras',
        //         'education-levels', 'professions', 'annual-income-ranges',
        //         'countries', 'states', 'cities', 'areas',
        //     ];
        //     foreach ($lookupEntities as $entity) {
        //         Route::resource($entity, LookupController::class)
        //             ->parameters([$entity => 'lookup'])
        //             ->names($entity); // 👈 IMPORTANT FIX
        //     }
        //     Route::post('{type}/import',   [LookupController::class, 'import'])->name('import');
        //     Route::get('{type}/export',    [LookupController::class, 'export'])->name('export');
        // });

        // ── AJAX API for cascade dropdowns ──────────────────────────────
        Route::get('/api/lookups/{type}', [LookupApiController::class, 'index'])
            ->name('api.lookups');
        
        // ── Lookup CRUD ─────────────────────────────────────────────────
        Route::prefix('lookups')->name('lookups.')->group(function () {
        
            $types = [
                'religions'            => 'religions',
                'castes'               => 'castes',
                'sub-castes'           => 'sub_castes',
                'gotras'               => 'gotras',
                'communities'          => 'communities',
                'mother-tongues'       => 'mother_tongues',
                'rashis'               => 'rashis',
                'nakshatras'           => 'nakshatras',
                'education-levels'     => 'education_levels',
                'professions'          => 'professions',
                'annual-income-ranges' => 'annual_income_ranges',
                'countries'            => 'countries',
                'states'               => 'states',
                'cities'               => 'cities',
                'areas'                => 'areas',
            ];
        
            foreach ($types as $slug => $routeName) {

                Route::get("/{$slug}", function () use ($slug) {
                    return app(\App\Http\Controllers\Admin\LookupController::class)->index($slug);
                })->name("{$routeName}.index");

                Route::post("/{$slug}", function (Request $request) use ($slug) {
                    return app(\App\Http\Controllers\Admin\LookupController::class)->store($request, $slug);
                })->name("{$routeName}.store");

                Route::put("/{$slug}/{lookup}", function (Request $request, $lookup) use ($slug) {
                    return app(\App\Http\Controllers\Admin\LookupController::class)->update($request, $slug, $lookup);
                })->name("{$routeName}.update");

                Route::delete("/{$slug}/{lookup}", function ($lookup) use ($slug) {
                    return app(\App\Http\Controllers\Admin\LookupController::class)->destroy($slug, $lookup);
                })->name("{$routeName}.destroy");
            }
        
            Route::post('/import/{type}', [LookupController::class, 'import'])->name('import');
            Route::get( '/export/{type}', [LookupController::class, 'export'])->name('export');
        });
 
 

        // ─── Activity Logs ────────────────────────────────────────────
        Route::middleware('admin.permission:view_logs')->prefix('logs')->name('logs.')->group(function () {
            Route::get('/',                 [ActivityLogController::class, 'index'])->name('index');
            Route::get('{log}',             [ActivityLogController::class, 'show'])->name('show');
            Route::delete('purge',          [ActivityLogController::class, 'purge'])->name('purge');
            Route::get('export',            [ActivityLogController::class, 'export'])->name('export');
        });
    });
});