<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Default route
Route::get('/', function () {
    return view('welcome'); // Ensure this points to your Vue app's entry point
});

// Web route for displaying the password reset form
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

// Catch-all route (must be last)
Route::get('/{any}', function () {
    return view('welcome'); // Ensure this points to your Vue app's entry point
})->where('any', '.*'); // Use '.*' to match all routes

// Socialite routes
Route::get('auth/{provider}/redirect', [SocialiteController::class, 'loginSocial'])
    ->name('socialite.auth');

Route::get('auth/{provider}/callback', [SocialiteController::class, 'callbackSocial'])
    ->name('socialite.callback');
