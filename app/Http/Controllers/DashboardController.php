<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Lote;
use App\Models\ControlCalidad;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $activeOrders = Pedido::whereIn('estado', ['CREADO', 'EN_PRODUCCION', 'EN_CALIDAD'])->count();
        $lotsInPlant = Lote::active()->count();

        $totalInspections = ControlCalidad::count();
        $defectiveInspections = ControlCalidad::where('resultado', 'RETENIDO')->count();
        $defectPercentage = $totalInspections > 0 ? round(($defectiveInspections / $totalInspections) * 100, 2) : 0;

        return Inertia::render('Dashboard', [
            'metrics' => [
                'activeOrders' => $activeOrders,
                'lotsInPlant' => $lotsInPlant,
                'defectPercentage' => $defectPercentage
            ]
        ]);
    }
}
