<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    // A solução: Liberamos esses campos para serem salvos via Reserva::create()
    protected $fillable = [
        'user_id',
        'reservado_para',
        'arena_id',
        'data_reserva',
        'horario',
        'valor_total',
        'metodo_pagamento',
        'status',
        'pago',
    ];

    protected function casts(): array
    {
        return [
            'pago' => 'boolean',
        ];
    }

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

    /**
     * Início e fim reais do jogo, a partir dos horários reservados (ex: "18:00 | 19:00").
     * Cada horário representa um bloco de 1 hora.
     */
    public function intervalo(): array
    {
        $horas = collect(explode('|', $this->horario))
            ->map(fn ($h) => trim($h))
            ->filter()
            ->sort();

        $data = Carbon::parse($this->data_reserva)->toDateString();
        $inicio = Carbon::parse($data . ' ' . $horas->first());
        $fim = Carbon::parse($data . ' ' . $horas->last())->addHour();

        return [$inicio, $fim];
    }

    /**
     * Status calculado por tempo: cancelado / a_iniciar / em_jogo / finalizado.
     * Independente do campo "pago".
     */
    public function getStatusCalculadoAttribute(): string
    {
        if ($this->status === 'cancelado') {
            return 'cancelado';
        }

        [$inicio, $fim] = $this->intervalo();
        $agora = Carbon::now();

        if ($agora->lt($inicio)) {
            return 'a_iniciar';
        }

        if ($agora->gte($fim)) {
            return 'finalizado';
        }

        return 'em_jogo';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_calculado) {
            'cancelado' => 'Cancelado',
            'a_iniciar' => 'A Iniciar',
            'em_jogo' => 'Em Jogo',
            'finalizado' => 'Finalizado',
        };
    }
}