<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroProduccion extends Model
{
    protected $table = 'registro_produccion';

    protected $fillable = [
        'lote_id',
        'operario_id',
        'maquina_id',
        'estacion_id',
        'cantidad',
        'fecha'
    ];

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    public function operario()
    {
        return $this->belongsTo(User::class, 'operario_id');
    }

    public function maquina()
    {
        return $this->belongsTo(Maquina::class);
    }

    public function estacion()
    {
        return $this->belongsTo(Estacion::class);
    }
}
