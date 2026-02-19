<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use Inertia\Inertia;
use Illuminate\Http\Request;

class TrazabilidadController extends Controller
{
    public function index()
    {
        return Inertia::render('Trazabilidad/Index', [
            'lotes' => Lote::with(['pedido', 'estacionActual'])->latest()->get()
        ]);
    }

    public function show(Lote $lote)
    {
        return Inertia::render('Trazabilidad/Show', [
            'lote' => $lote->load(['pedido', 'estacionActual', 'movimientos.estacionOrigen', 'movimientos.estacionDestino', 'movimientos.usuario', 'controlesCalidad.defecto'])
        ]);
    }
}
