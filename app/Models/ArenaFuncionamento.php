<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArenaFuncionamento extends Model
{
    protected $table = 'arena_funcionamento';

    protected $fillable = [
        'arena_id',
        'dia_semana',
        'hora_abertura',
        'hora_fechamento',
        'ativo',
    ];

    public function arena()
    {
        return $this->belongsTo(Arena::class);
    }
}
