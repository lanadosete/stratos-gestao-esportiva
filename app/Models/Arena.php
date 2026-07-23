<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Arena extends Model
{
    use HasFactory;

    protected $fillable = [
        'complexo_id',
        'nome',
        'tipo_esporte', // Ex: Vôlei, Beach Tennis
    ];

    // Relacionamento com o Complexo
    public function complexo()
    {
        return $this->belongsTo(Complexo::class);
    }

    public function esportes()
    {
        return $this->hasMany(ArenaEsporte::class);
    }

    public function precosTurno()
    {
        return $this->hasMany(ArenaPrecoTurno::class);
    }
}