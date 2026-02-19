<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parada extends Model
{
    protected $fillable = [
        'supervisor_id',
        'maquina_id',
        'tipo',
        'duracion',
        'fecha'
    ];

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function maquina()
    {
        return $this->belongsTo(Maquina::class);
    }
}
