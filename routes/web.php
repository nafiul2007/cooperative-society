<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SocietyInfoController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Requires Auth + Verified + is_active)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'is_active'])->group(function () {
    Route::get('members', [MemberController::class, 'index'])->name('members.index');
    Route::get('members/{member}', [MemberController::class, 'show'])->name('members.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update-profile', [ProfileController::class, 'updateProfile'])->name('profile.updateProfile');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ Routes that require profile to be completed
    Route::middleware('profile.complete')->group(function () {

        Route::patch('/profile/email-request', [ProfileController::class, 'requestEmailChange'])->name('profile.requestEmailChange');
        Route::get('/email/change/verify/{token}', [ProfileController::class, 'verifyNewEmail'])->name('profile.verifyNewEmail');
        
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::post('/members/check-email', [MemberController::class, 'checkEmail'])->name('members.check-email');
        Route::patch('/members/{member}/disable', [MemberController::class, 'disable'])->name('members.disable');
        Route::patch('/members/{member}/enable', [MemberController::class, 'enable'])->name('members.enable');

        Route::middleware('role:admin')->group(function () {
    Route::resource('members', MemberController::class)->except(['index', 'show']);
            Route::get('society-info/edit', [SocietyInfoController::class, 'edit'])->name('society-info.edit');
            Route::post('society-info/update', [SocietyInfoController::class, 'update'])->name('society-info.update');
        });
        Route::get('society-info/show', [SocietyInfoController::class, 'show'])->name('society-info.show');
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
