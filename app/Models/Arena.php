<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Arena extends Model
{
    use HasFactory;

    protected $fillable = [
        'complexo_id', // Agora o vínculo é com o complexo
        'nome', 
        'tipo_esporte', // Ex: Vôlei, Beach Tennis
        'preco_hora'
    ];

    public function complexo()
    {
        return $this->belongsTo(Complexo::class);
    }
}