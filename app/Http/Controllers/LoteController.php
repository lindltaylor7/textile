<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Estacion;
use Inertia\Inertia;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    public function index()
    {
        return Inertia::render('Lotes/Index', [
            'lotes' => Lote::with(['pedido', 'estacionActual'])->latest()->get(),
            'estaciones' => Estacion::orderBy('orden', 'asc')->get()
        ]);
    }

    public function move(Request $request)
    {
        // Reuse logic from API controller or call it internally
        $apiController = new \App\Http\Controllers\Api\MovimientoController();
        $apiController->store($request);

        return redirect()->back()->with('success', 'Movimiento registrado');
    }
}
