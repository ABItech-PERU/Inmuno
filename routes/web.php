<?php

use App\Actions\Fortify\CompletarRegistro;
use App\Http\Controllers\GoogleController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['guest'])->controller(GoogleController::class)->group(function () {
    Route::get('/google/redirect', 'redirect')->name('google');
    Route::get('/google/callback', 'callback');
});

Route::middleware('auth')->controller(CompletarRegistro::class)->group(function () {
    Route::get('/completar-registro', 'create')->name('completar.registro');
    Route::post('/completar-registro', 'store');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'registro',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
