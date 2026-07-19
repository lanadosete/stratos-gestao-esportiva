<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    // A solução: Liberamos esses campos para serem salvos via Reserva::create()
    protected $fillable = [
        'user_id',
        'arena_id',
        'data_reserva',
        'horario',
        'valor_total',
        'metodo_pagamento',
        'status',
    ];

    // Relação: Uma reserva pertence a um usuário (quem agendou)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relação: Uma reserva pertence a uma arena (quadra reservada)
    public function arena()
    {
        return $this->belongsTo(Arena::class);
    }
}