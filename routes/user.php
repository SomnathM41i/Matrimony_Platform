<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Auth\LoginController;
use App\Http\Controllers\User\Auth\RegisterController;
use App\Http\Controllers\User\Auth\ForgotPasswordController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileSetupController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\MatchesController;
use App\Http\Controllers\User\InterestsController;
use App\Http\Controllers\User\SearchController;
use App\Http\Controllers\User\ShortlistController;

/*
|--------------------------------------------------------------------------
| User Panel Routes
|--------------------------------------------------------------------------
*/

// ─────────────────────────────────────────────────────────────────────────────
// GUEST-ONLY ROUTES
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware('user.guest')->group(function () {

    Route::get('/login',  [LoginController::class, 'showLogin'])->name('user.login');
    Route::post('/login', [LoginController::class, 'login'])->name('user.login.post');

    Route::get('/register',  [RegisterController::class, 'showRegister'])->name('user.register');
    Route::post('/register', [RegisterController::class, 'store'])->name('user.register.post');

    Route::get('/forgot-password',  [ForgotPasswordController::class, 'showForm'])
         ->name('user.password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])
         ->name('user.password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showReset'])
         ->name('user.password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])
         ->name('user.password.update');
});

// ─────────────────────────────────────────────────────────────────────────────
// EMAIL VERIFICATION
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/email/verify', function () {
        return view('user.auth.verify-email');
    })->name('user.verification.notice');

    Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verifyEmail'])
         ->middleware('signed')
         ->name('user.verification.verify');

    Route::post('/email/verification-notification', [RegisterController::class, 'resendVerification'])
         ->middleware('throttle:6,1')
         ->name('user.verification.send');
});

// ─────────────────────────────────────────────────────────────────────────────
// AUTHENTICATED + VERIFIED USER ROUTES
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'user.active', 'user.role'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('user.logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

    // ── Profile Setup Wizard ──────────────────────────────────────────────
    Route::prefix('profile/setup')->name('user.profile.setup.')->group(function () {
        Route::get('/{step}',      [ProfileSetupController::class, 'show'])
             ->name('show')->where('step', '[1-7]');
        Route::post('/{step}',     [ProfileSetupController::class, 'save'])
             ->name('save')->where('step', '[1-7]');
        Route::post('/skip/{step}',[ProfileSetupController::class, 'skip'])
             ->name('skip')->where('step', '3');
    });

    // ── AJAX Cascading Dropdowns ──────────────────────────────────────────
    Route::prefix('ajax')->name('user.ajax.')->group(function () {
        Route::get('/castes-by-religion/{religion}',      [ProfileSetupController::class, 'castesByReligion'])->name('castes_by_religion');
        Route::get('/sub-castes-by-caste/{caste}',        [ProfileSetupController::class, 'subCastesByCaste'])->name('sub_castes_by_caste');
        Route::get('/gotras-by-religion/{religion}',      [ProfileSetupController::class, 'gotrasByReligion'])->name('gotras_by_religion');
        Route::get('/communities-by-religion/{religion}', [ProfileSetupController::class, 'communitiesByReligion'])->name('communities_by_religion');
        Route::get('/states-by-country/{country}',        [ProfileSetupController::class, 'statesByCountry'])->name('states_by_country');
        Route::get('/cities-by-state/{state}',            [ProfileSetupController::class, 'citiesByState'])->name('cities_by_state');
        Route::get('/areas-by-city/{city}',               [ProfileSetupController::class, 'areasByCity'])->name('areas_by_city');
    });

    // ── My Profile & Edit ─────────────────────────────────────────────────
    Route::get('/profile/me',      [ProfileController::class, 'myProfile'])->name('user.profile.me');
    Route::get('/profile/me/edit', [ProfileController::class, 'editProfile'])->name('user.profile.edit');

    // Public profile by slug
    Route::get('/profile/{slug}', [ProfileController::class, 'publicProfile'])
         ->name('user.profile.public')
         ->where('slug', '[a-z0-9\-]+');

    // ── Matches ───────────────────────────────────────────────────────────
    Route::get('/matches', [MatchesController::class, 'index'])->name('user.matches.index');

    // ── Search ────────────────────────────────────────────────────────────
    Route::get('/search',                            [SearchController::class, 'index'])            ->name('user.search.index');
    Route::post('/search/save',                      [SearchController::class, 'saveSearch'])       ->name('user.search.save');
    Route::delete('/search/saved/{savedSearch}',     [SearchController::class, 'deleteSavedSearch'])->name('user.search.saved.delete');

    // ── Interests ─────────────────────────────────────────────────────────
    Route::get('/interests/sent',                    [InterestsController::class, 'sent'])    ->name('user.interests.sent');
    Route::get('/interests/received',                [InterestsController::class, 'received'])->name('user.interests.received');
    Route::post('/interests/send/{user}',            [InterestsController::class, 'send'])   ->name('user.interests.send');
    Route::delete('/interests/{interest}/cancel',    [InterestsController::class, 'cancel']) ->name('user.interests.cancel');
    Route::patch('/interests/{interest}/accept',     [InterestsController::class, 'accept']) ->name('user.interests.accept');
    Route::patch('/interests/{interest}/decline',    [InterestsController::class, 'decline'])->name('user.interests.decline');

    // ── Shortlist ─────────────────────────────────────────────────────────
    Route::get('/shortlist',                         [ShortlistController::class, 'index'])  ->name('user.shortlist.index');
    Route::post('/shortlist/toggle/{user}',          [ShortlistController::class, 'toggle']) ->name('user.shortlist.toggle');
    Route::delete('/shortlist/{shortlist}',          [ShortlistController::class, 'remove']) ->name('user.shortlist.remove');

    // ── Remaining Phase stubs ─────────────────────────────────────────────
    Route::get('/messages',      fn() => abort(501, 'Phase 4 — not yet implemented'))->name('user.messages.index');
    Route::get('/subscription',  fn() => abort(501, 'Phase 6 — not yet implemented'))->name('user.subscription.show');
    Route::get('/packages',      fn() => abort(501, 'Phase 6 — not yet implemented'))->name('user.packages.index');
    Route::get('/notifications', fn() => abort(501, 'Phase 7 — not yet implemented'))->name('user.notifications.index');
    Route::get('/settings',      fn() => abort(501, 'Phase 7 — not yet implemented'))->name('user.settings.index');
});