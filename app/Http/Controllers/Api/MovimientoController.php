<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\MovimientoLote;
use App\Models\Estacion;
use App\Models\Pedido;
use App\Models\RegistroProduccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MovimientoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lote_id' => 'required|exists:lotes,id',
            'estacion_destino_id' => 'required|exists:estaciones,id',
            'usuario_id' => 'required|exists:users,id',
            'cantidad_entrada' => 'required|integer|min:1',
            'maquina_id' => 'nullable|exists:maquinas,id'
        ]);

        return DB::transaction(function () use ($validated) {
            $lote = Lote::lockForUpdate()->find($validated['lote_id']);
            $estacionDestino = Estacion::find($validated['estacion_destino_id']);

            // 1. Validar flujo rgido
            $ultimaEstacionId = $lote->estacion_actual_id;
            if ($ultimaEstacionId) {
                $ultimaEstacion = Estacion::find($ultimaEstacionId);
                if ($estacionDestino->orden !== $ultimaEstacion->orden + 1) {
                    return response()->json(['message' => 'Flujo rgido violado. Debe seguir el orden establecido.'], 422);
                }
            } else {
                // Primer movimiento debe ser a la primera estacin (orden 1)
                if ($estacionDestino->orden !== 1) {
                    return response()->json(['message' => 'El primer movimiento debe ser a la estacin inicial.'], 422);
                }
            }

            // 2. Validar cantidad
            if ($ultimaEstacionId && $validated['cantidad_entrada'] > $lote->cantidad_actual) {
                return response()->json(['message' => 'La cantidad de entrada no puede superar la cantidad actual del lote.'], 422);
            }

            // 3. Registrar movimiento
            $movimiento = MovimientoLote::create([
                'lote_id' => $lote->id,
                'estacion_origen_id' => $ultimaEstacionId,
                'estacion_destino_id' => $estacionDestino->id,
                'usuario_id' => $validated['usuario_id'],
                'fecha_inicio' => Carbon::now(),
                'cantidad_entrada' => $validated['cantidad_entrada']
            ]);

            // 4. Actualizar lote
            $lote->update([
                'estacion_actual_id' => $estacionDestino->id,
                'cantidad_actual' => $validated['cantidad_entrada']
            ]);

            // 5. Actualizar Pedido a EN_PRODUCCION si es el primer movimiento
            $pedido = $lote->pedido;
            if ($pedido->estado === 'CREADO') {
                $pedido->update(['estado' => 'EN_PRODUCCION']);
            }

            // 6. Registrar produccin (opcional en este paso, segn requerimiento de productividad)
            RegistroProduccion::create([
                'lote_id' => $lote->id,
                'operario_id' => $validated['usuario_id'],
                'maquina_id' => $validated['maquina_id'] ?? null,
                'estacion_id' => $estacionDestino->id,
                'cantidad' => $validated['cantidad_entrada'],
                'fecha' => Carbon::now()
            ]);

            return response()->json($movimiento->load(['estacionOrigen', 'estacionDestino']), 201);
        });
    }

    public function finish(Request $request, MovimientoLote $movimiento)
    {
        $validated = $request->validate([
            'cantidad_salida' => 'required|integer|min:0'
        ]);

        if ($validated['cantidad_salida'] > $movimiento->cantidad_entrada) {
            return response()->json(['message' => 'La cantidad de salida no puede superar la de entrada.'], 422);
        }

        $movimiento->update([
            'fecha_fin' => Carbon::now(),
            'cantidad_salida' => $validated['cantidad_salida']
        ]);

        $movimiento->lote->update([
            'cantidad_actual' => $validated['cantidad_salida']
        ]);

        return $movimiento;
    }
}
