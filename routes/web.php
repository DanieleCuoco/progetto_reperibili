<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RepartoController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ReperibileController;
use App\Http\Controllers\ReperibileAuthController;
use App\Http\Controllers\TurnoReperibilitaController;
use App\Http\Controllers\ModificheController;

Route::get('/', function () {
    return view('welcome');
});

// Rotte Admin
Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('login', [AdminAuthController::class,'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class,'login']);
    
    Route::get('dashboard', [AdminAuthController::class, 'index'])->name('dashboard');
    Route::post('logout', [AdminAuthController::class,'logout'])->name('logout');

    Route::resource('reperibili', ReperibileController::class)->parameters(['reperibili' => 'reperibile']);
    Route::delete('reperibili/delete/{id}', [ReperibileController::class, 'deleteReperibile'])->name('reperibili.delete');
    Route::resource('reparti', RepartoController::class)->parameters(['reparti' => 'reparto']);
    
    // Rotte per la gestione modifiche
    Route::get('modifiche', [ModificheController::class, 'index'])->name('modifiche.index');
    Route::post('modifiche/approva/{id}', [ModificheController::class, 'approva'])->name('modifiche.approva');
    Route::post('modifiche/rifiuta/{id}', [ModificheController::class, 'rifiuta'])->name('modifiche.rifiuta');
});

// Rotte per i reperibili (SENZA middleware)
Route::prefix('reperibile')->name('reperibile.')->group(function() {
    Route::get('login', [ReperibileAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [ReperibileAuthController::class, 'login']);
    Route::get('dashboard', [ReperibileAuthController::class, 'dashboard'])->name('dashboard');
    Route::post('logout', [ReperibileAuthController::class, 'logout'])->name('logout');
    
    // Rotte per i turni di reperibilità
    Route::post('turni', [TurnoReperibilitaController::class, 'store'])->name('turni.store');
});

// Rotte pubbliche
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/calendar', [UserController::class, 'calendar'])->name('calendar');
});