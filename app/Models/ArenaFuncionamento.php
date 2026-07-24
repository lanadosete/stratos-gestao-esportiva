<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplexoFuncionamento extends Model
{
    protected $table = 'complexo_funcionamentos';

    protected $fillable = [
        'complexo_id',
        'dia_semana',
        'hora_abertura',
        'hora_fechamento',
        'ativo',
    ];

    public function complexo()
    {
        return $this->belongsTo(Complexo::class);
    }
}
