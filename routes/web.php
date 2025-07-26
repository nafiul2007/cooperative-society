<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SocietyInfoController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\ContributionFileController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Requires Auth + Verified + is_active)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'is_active'])->group(function () {

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile/update-profile', [ProfileController::class, 'updateProfile'])->name('profile.updateProfile');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ Routes that require profile to be completed
    Route::middleware('profile.complete')->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');

        Route::resource('members', MemberController::class)->names(['index', 'show']);
        Route::post('members/check-email', [MemberController::class, 'checkEmail'])->name('members.check-email');

        Route::patch('profile/email-request', [ProfileController::class, 'requestEmailChange'])->name('profile.requestEmailChange');
        Route::get('email/change/verify/{token}', [ProfileController::class, 'verifyNewEmail'])->name('profile.verifyNewEmail');

        Route::resource('contributions', ContributionController::class)->names(['index', 'create', 'store', 'show', 'update', 'edit']);
        Route::delete('/attachments/{attachment}', [ContributionFileController::class, 'destroy'])->name('attachments.destroy');

        Route::get('society-info/show', [SocietyInfoController::class, 'show'])->name('society-info.show');

        // ✅ Routes that require admin role
        Route::middleware('role:admin')->group(function () {
            Route::resource('members', MemberController::class)->except(['index', 'show']);
            Route::patch('members/{member}/disable', [MemberController::class, 'disable'])->name('members.disable');
            Route::patch('members/{member}/enable', [MemberController::class, 'enable'])->name('members.enable');

            Route::get('society-info/edit', [SocietyInfoController::class, 'edit'])->name('society-info.edit');
            Route::post('society-info/update', [SocietyInfoController::class, 'update'])->name('society-info.update');

            Route::patch('contributions/{contribution}/approve', [ContributionController::class, 'approve'])->name('contributions.approve');
            Route::patch('contributions/{contribution}/reject', [ContributionController::class, 'reject'])->name('contributions.reject');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
