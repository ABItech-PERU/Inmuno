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

    // Rutas de administración - solo para administradores
    Route::middleware(['role:ADMINISTRADOR'])->prefix('admin')->name('admin.')->group(function () {
        // Gestión de Vacunas
        Route::resource('vacunas', App\Http\Controllers\Admin\VacunaController::class);
        Route::patch('vacunas/{vacuna}/toggle-status', [App\Http\Controllers\Admin\VacunaController::class, 'toggleStatus'])
            ->name('vacunas.toggle-status');

        // Gestión de Centros de Salud
        Route::resource('centros-salud', App\Http\Controllers\Admin\CentroSaludController::class);
        Route::patch('centros-salud/{centros_salud}/toggle-status', [App\Http\Controllers\Admin\CentroSaludController::class, 'toggleStatus'])
            ->name('centros-salud.toggle-status');
        Route::get('api/provincias/{departamento}', [App\Http\Controllers\Admin\CentroSaludController::class, 'getProvincias'])
            ->name('api.provincias');
        Route::get('api/distritos/{provincia}', [App\Http\Controllers\Admin\CentroSaludController::class, 'getDistritos'])
            ->name('api.distritos');
    });
});
