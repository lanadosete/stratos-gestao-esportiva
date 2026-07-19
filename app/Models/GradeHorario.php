<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeHorario extends Model
{
    // Permitir preenchimento em massa
    protected $fillable = [
        'arena_id',
        'dia_semana',
        'horario',
        'esporte',
        'preco',
        'ativo'
    ];

    // Relacionamento: Uma grade pertence a uma arena
    public function arena()
    {
        return $this->belongsTo(Arena::class);
    }
}
