<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArenaEsporte extends Model
{
    protected $fillable = [
        'arena_id',
        'nome',
        'ativo',
    ];

    public function arena()
    {
        return $this->belongsTo(Arena::class);
    }
}
