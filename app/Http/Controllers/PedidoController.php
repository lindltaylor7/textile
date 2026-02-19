<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index()
    {
        return Inertia::render('Pedidos/Index', [
            'pedidos' => Pedido::withCount('lotes')->latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|unique:pedidos,codigo',
            'fecha' => 'required|date',
            'cantidad' => 'required|integer|min:1',
            'cantidad_lotes' => 'required|integer|min:1'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            $pedido = Pedido::create([
                'codigo' => $validated['codigo'],
                'fecha' => $validated['fecha'],
                'cantidad' => $validated['cantidad'],
                'estado' => 'CREADO'
            ]);

            $cantidadPorLote = floor($validated['cantidad'] / $validated['cantidad_lotes']);
            $residuo = $validated['cantidad'] % $validated['cantidad_lotes'];

            for ($i = 1; $i <= $validated['cantidad_lotes']; $i++) {
                $cantidadLote = $cantidadPorLote + ($i <= $residuo ? 1 : 0);

                \App\Models\Lote::create([
                    'pedido_id' => $pedido->id,
                    'codigo' => $pedido->codigo . '-L' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'cantidad_inicial' => $cantidadLote,
                    'cantidad_actual' => $cantidadLote,
                    'estacion_actual_id' => null
                ]);
            }
        });

        return redirect()->back()->with('success', 'Pedido creado exitosamente');
    }
}
