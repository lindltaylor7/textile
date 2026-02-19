<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Defecto extends Model
{
    protected $fillable = [
        'nombre'
    ];

    public function controlesCalidad()
    {
        return $this->hasMany(ControlCalidad::class);
    }
}
