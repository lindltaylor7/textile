<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ControlCalidad extends Model
{
    protected $table = 'control_calidad';

    protected $fillable = [
        'lote_id',
        'defecto_id',
        'usuario_id',
        'cantidad_defectuosa',
        'resultado'
    ];

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    public function defecto()
    {
        return $this->belongsTo(Defecto::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
