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
        'preco_hora'    // Mantemos como um "preço base" ou fallback
    ];

    // Relacionamento com o Complexo
    public function complexo()
    {
        return $this->belongsTo(Complexo::class);
    }

    // 1. Dias em que a quadra opera
    public function funcionamento()
    {
        return $this->hasMany(ArenaFuncionamento::class);
    }

    public function esportes()
    {
        return $this->hasMany(ArenaEsporte::class);
    }

    public function precosTurno()
    {
        return $this->hasMany(ArenaPrecoTurno::class);
    }

    // 2. Grade de horários (Define o Turno de cada hora)
    public function grade()
    {
        return $this->hasMany(GradeHorario::class);
    }

    // 3. Regras de Preço (Valor por Esporte e Turno)
    public function precos()
    {
        return $this->hasMany(Preco::class);
    }
}