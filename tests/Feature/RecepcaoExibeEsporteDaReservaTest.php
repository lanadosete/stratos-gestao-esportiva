<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\ArenaFuncionamento;
use App\Models\Quadra;
use App\Models\QuadraEsporte;
use App\Models\QuadraPrecoTurno;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecepcaoExibeEsporteDaReservaTest extends TestCase
{
    use RefreshDatabase;

    public function test_reserva_via_agendamento_persiste_o_esporte_escolhido(): void
    {
        Carbon::setTestNow(Carbon::today()->setTime(10, 0));

        $admin = User::create([
            'name' => 'Admin Teste',
            'email' => 'admin@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'admin',
        ]);

        $arena = Arena::create([
            'user_id' => $admin->id,
            'nome' => 'Arena Teste',
            'endereco' => 'Rua Teste',
            'telefone' => '11999999999',
        ]);

        $quadra = Quadra::create([
            'arena_id' => $arena->id,
            'nome' => 'Quadra 1',
            'tipo_esporte' => 'Multiuso',
        ]);

        ArenaFuncionamento::create([
            'arena_id' => $arena->id,
            'dia_semana' => Carbon::today()->dayOfWeek,
            'hora_abertura' => '08:00:00',
            'hora_fechamento' => '23:00:00',
            'ativo' => true,
        ]);

        QuadraEsporte::create(['quadra_id' => $quadra->id, 'nome' => 'Beach Tênis', 'ativo' => true]);
        QuadraPrecoTurno::create(['quadra_id' => $quadra->id, 'esporte' => 'Beach Tênis', 'turno' => 'Manhã', 'valor_hora' => 100]);

        $cliente = User::create([
            'name' => 'Cliente Teste',
            'email' => 'cliente@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'cliente',
        ]);

        $this->actingAs($cliente)->post('/agendamento/finalizar', [
            'quadra_id' => $quadra->id,
            'data_reserva' => Carbon::today()->toDateString(),
            'horarios' => ['11:00'],
            'esporte' => 'Beach Tênis',
            'metodo_pagamento' => 'pix',
        ]);

        $reserva = Reserva::where('quadra_id', $quadra->id)->firstOrFail();
        $this->assertSame('Beach Tênis', $reserva->esporte);

        $response = $this->actingAs($admin)->get('/recepcao');
        $response->assertOk();
        $response->assertSee('Beach Tênis');
        $response->assertDontSee('Multiuso');

        Carbon::setTestNow();
    }
}
