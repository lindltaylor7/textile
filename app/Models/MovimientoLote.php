<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoLote extends Model
{
    protected $table = 'movimientos_lote';

    protected $fillable = [
        'lote_id',
        'estacion_origen_id',
        'estacion_destino_id',
        'usuario_id',
        'fecha_inicio',
        'fecha_fin',
        'cantidad_entrada',
        'cantidad_salida'
    ];

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    public function estacionOrigen()
    {
        return $this->belongsTo(Estacion::class, 'estacion_origen_id');
    }

    public function estacionDestino()
    {
        return $this->belongsTo(Estacion::class, 'estacion_destino_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
