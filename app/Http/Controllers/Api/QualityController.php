<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ControlCalidad;
use App\Models\Lote;
use App\Models\Defecto;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QualityController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lote_id' => 'required|exists:lotes,id',
            'defecto_id' => 'nullable|exists:defectos,id',
            'usuario_id' => 'required|exists:users,id',
            'cantidad_defectuosa' => 'required|integer|min:0',
            'resultado' => 'required|in:LIBERADO,RETENIDO'
        ]);

        return DB::transaction(function () use ($validated) {
            $lote = Lote::lockForUpdate()->find($validated['lote_id']);

            // 1. Registrar control de calidad
            $qc = ControlCalidad::create($validated);

            // 2. Reducir cantidad del lote si hay defectos
            if ($validated['cantidad_defectuosa'] > 0) {
                $lote->decrement('cantidad_actual', $validated['cantidad_defectuosa']);
            }

            // 3. Verificar si el pedido ha finalizado
            $pedido = $lote->pedido;
            $todosLotesLiberados = !Lote::where('pedido_id', $pedido->id)
                ->where(function ($query) {
                    $query->whereNull('estacion_actual_id')
                        ->orWhereHas('estacionActual', function ($q) {
                            $q->where('nombre', '!=', 'Control de Calidad');
                        })
                        ->orWhereDoesntHave('controlesCalidad', function ($q) {
                            $q->where('resultado', 'LIBERADO');
                        });
                })->exists();

            if ($todosLotesLiberados) {
                $pedido->update(['estado' => 'FINALIZADO']);
            }

            return response()->json($qc, 201);
        });
    }

    public function defects()
    {
        return Defecto::all();
    }
}
