<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Estacion;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CorteController extends Controller
{
    public function index()
    {
        // Estacin 1: Corte
        $estacionCorte = Estacion::where('nombre', 'Corte')->first();
        $estacionCostura = Estacion::where('nombre', 'Costura')->first();

        return Inertia::render('Corte/Index', [
            'lotes' => $estacionCorte
                ? Lote::with(['pedido', 'estacionActual'])
                ->where(function ($q) use ($estacionCorte) {
                    $q->whereNull('estacion_actual_id')
                        ->orWhere('estacion_actual_id', $estacionCorte->id);
                })
                ->latest()->get()
                : collect(),
            'estacion' => $estacionCorte,
            'estacion_siguiente' => $estacionCostura
        ]);
    }

    public function move(Request $request)
    {
        $lote = Lote::findOrFail($request->lote_id);
        $estacionCorte = Estacion::where('nombre', 'Corte')->first();
        $estacionCostura = Estacion::where('nombre', 'Costura')->first();

        // Si el lote no ha iniciado, su primer destino es Corte
        // Si ya est en Corte, su destino es Costura
        if (is_null($lote->estacion_actual_id)) {
            $request->merge(['estacion_destino_id' => $estacionCorte->id]);
        } else {
            $request->merge(['estacion_destino_id' => $estacionCostura->id]);
        }

        $apiController = new \App\Http\Controllers\Api\MovimientoController();
        $response = $apiController->store($request);

        if ($response->getStatusCode() >= 400) {
            $data = json_decode($response->getContent(), true);
            return redirect()->back()->withErrors(['error' => $data['message'] ?? 'Error al procesar el movimiento']);
        }

        return redirect()->back()->with('success', 'Lote procesado correctamente');
    }
}
