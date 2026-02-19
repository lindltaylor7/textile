<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index()
    {
        return Pedido::withCount('lotes')->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|unique:pedidos,codigo',
            'fecha' => 'required|date',
            'cantidad' => 'required|integer|min:1',
            'cantidad_lotes' => 'required|integer|min:1'
        ]);

        return DB::transaction(function () use ($validated) {
            $pedido = Pedido::create([
                'codigo' => $validated['codigo'],
                'fecha' => $validated['fecha'],
                'cantidad' => $validated['cantidad'],
                'estado' => 'CREADO'
            ]);

            // Crear lotes iniciales
            $cantidadPorLote = floor($validated['cantidad'] / $validated['cantidad_lotes']);
            $residuo = $validated['cantidad'] % $validated['cantidad_lotes'];

            for ($i = 1; $i <= $validated['cantidad_lotes']; $i++) {
                $cantidadLote = $cantidadPorLote + ($i <= $residuo ? 1 : 0);

                Lote::create([
                    'pedido_id' => $pedido->id,
                    'codigo' => $pedido->codigo . '-L' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'cantidad_inicial' => $cantidadLote,
                    'cantidad_actual' => $cantidadLote,
                    'estacion_actual_id' => null // No ha empezado
                ]);
            }

            return response()->json($pedido->load('lotes'), 201);
        });
    }

    public function show(Pedido $pedido)
    {
        return $pedido->load('lotes.estacionActual');
    }

    public function update(Request $request, Pedido $pedido)
    {
        if ($pedido->estado !== 'CREADO') {
            return response()->json(['message' => 'Solo se pueden editar pedidos en estado CREADO'], 422);
        }

        $validated = $request->validate([
            'fecha' => 'sometimes|date',
            'cantidad' => 'sometimes|integer|min:1'
        ]);

        $pedido->update($validated);

        return $pedido;
    }
}
