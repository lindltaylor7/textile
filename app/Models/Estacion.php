<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estacion extends Model
{
    protected $table = 'estaciones';

    protected $fillable = [
        'nombre',
        'orden'
    ];

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'estacion_actual_id');
    }

    public function maquinas()
    {
        return $this->hasMany(Maquina::class);
    }

    public function getCongestionAttribute()
    {
        $lotesEsperando = $this->lotes()->count();
        $umbral = 10; // Ejemplo de umbral de congestin

        if ($lotesEsperando > $umbral) return 'ALTA';
        if ($lotesEsperando > $umbral / 2) return 'MEDIA';
        return 'BAJA';
    }
}
