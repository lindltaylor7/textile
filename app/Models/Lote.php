<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $fillable = [
        'pedido_id',
        'codigo',
        'cantidad_inicial',
        'cantidad_actual',
        'estacion_actual_id'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function estacionActual()
    {
        return $this->belongsTo(Estacion::class, 'estacion_actual_id');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoLote::class);
    }

    public function registrosProduccion()
    {
        return $this->hasMany(RegistroProduccion::class);
    }

    public function controlesCalidad()
    {
        return $this->hasMany(ControlCalidad::class);
    }

    // Helpers de lgica
    public function isFinished()
    {
        return $this->controlesCalidad()->where('resultado', 'LIBERADO')->exists();
    }

    public function scopeActive($query)
    {
        return $query->whereHas('estacionActual', function ($q) {
            $q->where('nombre', '!=', 'Control de Calidad');
        })->orWhereDoesntHave('controlesCalidad', function ($q) {
            $q->where('resultado', 'LIBERADO');
        });
    }
}
