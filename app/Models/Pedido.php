<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'codigo',
        'fecha',
        'cantidad',
        'estado'
    ];

    public function lotes()
    {
        return $this->hasMany(Lote::class);
    }
}
