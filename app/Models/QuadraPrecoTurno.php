<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuadraPrecoTurno extends Model
{
    protected $fillable = [
        'quadra_id',
        'esporte',
        'turno',
        'valor_hora',
    ];

    public function quadra()
    {
        return $this->belongsTo(Quadra::class);
    }
}
