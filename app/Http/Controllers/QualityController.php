<?php

namespace App\Http\Controllers;

use App\Models\ControlCalidad;
use App\Models\Defecto;
use App\Models\Lote;
use Inertia\Inertia;
use Illuminate\Http\Request;

class QualityController extends Controller
{
    public function index()
    {
        return Inertia::render('Calidad/Index', [
            'controles' => ControlCalidad::with(['lote', 'defecto', 'usuario'])->latest()->get(),
            'defectos' => Defecto::all(),
            'lotesDisponibles' => Lote::active()->get()
        ]);
    }

    public function store(Request $request)
    {
        // Reuse logic from API controller
        $apiController = new \App\Http\Controllers\Api\QualityController();
        $apiController->store($request);

        return redirect()->back()->with('success', 'Inspeccin registrada');
    }
}
