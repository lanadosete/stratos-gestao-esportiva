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
        'quadra_id',
        'esporte',
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

    // Relação: Uma reserva pertence a uma quadra
    public function quadra()
    {
        return $this->belongsTo(Quadra::class);
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

    /**
     * Link do wa.me para contatar o cliente, ou null se a reserva não tiver um
     * cliente cadastrado de fato (reserva anotada pela recepção sem cadastro,
     * onde user_id é do funcionário/admin) ou o cliente não tiver telefone.
     */
    public function getWhatsappLinkAttribute(): ?string
    {
        if ($this->reservado_para || !$this->user || $this->user->tipo_conta !== 'cliente') {
            return null;
        }

        return static::linkWhatsapp($this->user->telefone);
    }

    /**
     * Link do wa.me para o cliente contatar a arena sobre a reserva, ou null se
     * a quadra/arena não existir mais ou a arena não tiver telefone cadastrado.
     */
    public function getArenaWhatsappLinkAttribute(): ?string
    {
        return static::linkWhatsapp($this->quadra?->arena?->telefone);
    }

    // Normaliza um telefone cru pro formato exigido pelo wa.me (código do país +
    // DDD + número, só dígitos), ou null se não der pra formar um número válido.
    private static function linkWhatsapp(?string $telefone): ?string
    {
        if (!$telefone) {
            return null;
        }

        $digitos = preg_replace('/\D/', '', $telefone);

        if (strlen($digitos) < 10) {
            return null;
        }

        // DDD + número sem código do país (10 ou 11 dígitos) recebem o 55 na frente
        if (strlen($digitos) <= 11) {
            $digitos = '55' . $digitos;
        }

        return 'https://wa.me/' . $digitos;
    }
}