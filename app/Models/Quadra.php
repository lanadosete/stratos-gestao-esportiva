<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quadra extends Model
{
    use HasFactory;

    protected $fillable = [
        'arena_id',
        'nome',
        'tipo_esporte', // Ex: Vôlei, Beach Tennis
    ];

    // Relacionamento com a Arena
    public function arena()
    {
        return $this->belongsTo(Arena::class);
    }

    public function esportes()
    {
        return $this->hasMany(QuadraEsporte::class);
    }

    public function precosTurno()
    {
        return $this->hasMany(QuadraPrecoTurno::class);
    }
}