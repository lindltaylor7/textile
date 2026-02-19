<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\LoteController;
use App\Http\Controllers\Api\MovimientoController;
use App\Http\Controllers\Api\QualityController;
use App\Http\Controllers\Api\EstacionController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Pedidos
    Route::apiResource('pedidos', PedidoController::class)->names('api.pedidos');

    // Lotes
    Route::get('lotes', [LoteController::class, 'index'])->name('api.lotes.index');
    Route::get('lotes/{lote}', [LoteController::class, 'show'])->name('api.lotes.show');
    Route::get('lotes/{lote}/history', [LoteController::class, 'history'])->name('api.lotes.history');

    // Movimientos (Supervisor y Operario)
    Route::middleware('role:admin,supervisor,operator')->group(function () {
        Route::post('movimientos', [MovimientoController::class, 'store'])->name('api.movimientos.store');
        Route::patch('movimientos/{movimiento}/finish', [MovimientoController::class, 'finish'])->name('api.movimientos.finish');
    });

    // Calidad (Admin y Calidad)
    Route::middleware('role:admin,quality')->group(function () {
        Route::post('quality', [QualityController::class, 'store'])->name('api.quality.store');
        Route::get('defects', [QualityController::class, 'defects'])->name('api.quality.defects');
    });

    // Estaciones
    Route::get('estaciones', [EstacionController::class, 'index'])->name('api.estaciones.index');
});
