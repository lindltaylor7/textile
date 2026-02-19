<?php

use App\Http\Controllers\PedidoController;
use App\Http\Controllers\QualityController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\CorteController;
use App\Http\Controllers\CosturaController;
use App\Http\Controllers\TrazabilidadController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');

    // Corte
    Route::get('/corte', [CorteController::class, 'index'])->name('corte.index');
    Route::post('/corte/move', [CorteController::class, 'move'])->name('corte.move');

    // Costura
    Route::get('/costura', [CosturaController::class, 'index'])->name('costura.index');
    Route::post('/costura/move', [CosturaController::class, 'move'])->name('costura.move');
    Route::post('/costura/stop', [CosturaController::class, 'stop'])->name('costura.stop');

    // Calidad
    Route::get('/calidad', [QualityController::class, 'index'])->name('calidad.index');
    Route::post('/calidad', [QualityController::class, 'store'])->name('calidad.store');

    // Trazabilidad
    Route::get('/trazabilidad', [TrazabilidadController::class, 'index'])->name('trazabilidad.index');
    Route::get('/trazabilidad/{lote}', [TrazabilidadController::class, 'show'])->name('trazabilidad.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
