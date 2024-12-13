<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Ruta principal de bienvenida
Route::get('/', function () {
    return Auth::check() ? redirect('/home') : redirect('/login');
});

// Rutas protegidas por autenticación y verificación de email
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Dashboard para todos los usuarios autenticados
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rutas exclusivas para administradores
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin');
        Route::get('/admin/manage-tracks', [AdminController::class, 'manageTracks'])->name('admin.manage-tracks');
        Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    });

    Route::resource('reserve', ReservationController::class)->only(['store', 'index', 'create']);

    Route::get('/contact', function () {
        return view('contact');
    })->name('contact');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/terms', function () {
        return view('terms');
    })->name('terms');

    Route::get('/privacy', function () {
        return view('privacy');
    })->name('privacy');

    Route::get('/my-reservations', function () {
        return view('my-reservations');
    })->name('my-reservations');
});

// Rutas adicionales
require __DIR__ . '/auth.php';
