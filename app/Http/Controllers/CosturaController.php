<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Estacion;
use App\Models\Maquina;
use App\Models\Parada;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CosturaController extends Controller
{
    public function index()
    {
        // Estacin 2: Costura
        $estacionCostura = Estacion::where('nombre', 'Costura')->first();

        return Inertia::render('Costura/Index', [
            'lotes' => $estacionCostura
                ? Lote::with(['pedido', 'estacionActual'])
                ->where('estacion_actual_id', $estacionCostura->id)
                ->latest()->get()
                : collect(),
            'maquinas' => $estacionCostura
                ? Maquina::where('estacion_id', $estacionCostura->id)->get()
                : collect(),
            'estacion' => $estacionCostura
        ]);
    }

    public function move(Request $request)
    {
        $apiController = new \App\Http\Controllers\Api\MovimientoController();
        $apiController->store($request);

        return redirect()->back()->with('success', 'Lote de costura procesado');
    }

    public function stop(Request $request)
    {
        $validated = $request->validate([
            'maquina_id' => 'required|exists:maquinas,id',
            'tipo' => 'required|string',
            'duracion' => 'required|integer',
            'fecha' => 'required|date'
        ]);

        Parada::create([
            'supervisor_id' => auth()->id(),
            'maquina_id' => $validated['maquina_id'],
            'tipo' => $validated['tipo'],
            'duracion' => $validated['duracion'],
            'fecha' => $validated['fecha']
        ]);

        return redirect()->back()->with('success', 'Parada registrada');
    }
}
