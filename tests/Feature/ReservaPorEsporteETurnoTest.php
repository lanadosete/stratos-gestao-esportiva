<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\ArenaEsporte;
use App\Models\ArenaPrecoTurno;
use App\Models\Complexo;
use App\Models\ComplexoFuncionamento;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservaPorEsporteETurnoTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_configure_prices_by_sport_and_turn_and_reservation_uses_them(): void
    {
        $user = User::create([
            'name' => 'Admin Teste',
            'email' => 'admin@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'admin',
        ]);

        $complexo = Complexo::create([
            'user_id' => $user->id,
            'nome' => 'Complexo Teste',
            'endereco' => 'Rua Teste',
            'telefone' => '11999999999',
        ]);

        $arena = Arena::create([
            'complexo_id' => $complexo->id,
            'nome' => 'Arena 1',
            'tipo_esporte' => 'Multiuso',
        ]);

        ComplexoFuncionamento::create([
            'complexo_id' => $complexo->id,
            'dia_semana' => Carbon::today()->dayOfWeek,
            'hora_abertura' => '08:00:00',
            'hora_fechamento' => '23:00:00',
            'ativo' => true,
        ]);

        ArenaEsporte::create([
            'arena_id' => $arena->id,
            'nome' => 'Futevôlei',
            'ativo' => true,
        ]);

        ArenaPrecoTurno::create([
            'arena_id' => $arena->id,
            'esporte' => 'Futevôlei',
            'turno' => 'Noite',
            'valor_hora' => 145.00,
        ]);

        $this->actingAs($user);

        $response = $this->post('/agendamento/finalizar', [
            'arena_id' => $arena->id,
            'data_reserva' => Carbon::today()->toDateString(),
            'horarios' => ['21:00'],
            'esporte' => 'Futevôlei',
            'metodo_pagamento' => 'pix',
        ]);

        $response->assertRedirectContains('/agendamento/');
        $this->assertDatabaseHas('reservas', [
            'arena_id' => $arena->id,
            'user_id' => $user->id,
            'valor_total' => 145.00,
        ]);
    }
}
