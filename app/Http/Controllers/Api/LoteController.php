<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    public function index()
    {
        return Lote::with(['pedido', 'estacionActual'])->latest()->get();
    }

    public function show(Lote $lote)
    {
        return $lote->load(['pedido', 'estacionActual', 'movimientos.estacionOrigen', 'movimientos.estacionDestino', 'movimientos.usuario']);
    }

    public function history(Lote $lote)
    {
        return $lote->movimientos()
            ->with(['estacionOrigen', 'estacionDestino', 'usuario'])
            ->orderBy('fecha_inicio', 'asc')
            ->get();
    }
}
