<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maquina extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'estacion_id',
        'estado'
    ];

    public function estacion()
    {
        return $this->belongsTo(Estacion::class);
    }

    public function registroProduccion()
    {
        return $this->hasMany(RegistroProduccion::class);
    }

    public function paradas()
    {
        return $this->hasMany(Parada::class);
    }
}
