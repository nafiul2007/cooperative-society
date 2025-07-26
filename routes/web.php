<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Requires Auth + Verified + is_active)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'is_active'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update-profile', [ProfileController::class, 'updateProfile'])->name('profile.updateProfile');
    Route::patch('/profile/email-request', [ProfileController::class, 'requestEmailChange'])->name('profile.requestEmailChange');
    Route::get('/email/change/verify/{token}', [ProfileController::class, 'verifyNewEmail'])->name('profile.verifyNewEmail');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/members/check-email', [MemberController::class, 'checkEmail'])->name('members.check-email');
    Route::patch('/members/{member}/disable', [MemberController::class, 'disable'])->name('members.disable');
    Route::patch('/members/{member}/enable', [MemberController::class, 'enable'])->name('members.enable');

    Route::middleware('role:admin')->group(function () {
        Route::resource('members', MemberController::class);
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
