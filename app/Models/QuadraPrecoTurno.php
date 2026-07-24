<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArenaPrecoTurno extends Model
{
    protected $fillable = [
        'arena_id',
        'esporte',
        'turno',
        'valor_hora',
    ];

    public function arena()
    {
        return $this->belongsTo(Arena::class);
    }
}
