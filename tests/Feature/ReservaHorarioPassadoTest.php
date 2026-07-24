<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\ArenaFuncionamento;
use App\Models\Quadra;
use App\Models\QuadraEsporte;
use App\Models\QuadraPrecoTurno;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservaHorarioPassadoTest extends TestCase
{
    use RefreshDatabase;

    public function test_nao_permite_reservar_horario_que_ja_passou_hoje(): void
    {
        Carbon::setTestNow(Carbon::today()->setTime(15, 0));

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

        QuadraEsporte::create(['quadra_id' => $quadra->id, 'nome' => 'Futevôlei', 'ativo' => true]);
        QuadraPrecoTurno::create(['quadra_id' => $quadra->id, 'esporte' => 'Futevôlei', 'turno' => 'Tarde', 'valor_hora' => 100]);

        $cliente = User::create([
            'name' => 'Cliente Teste',
            'email' => 'cliente@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'cliente',
        ]);

        // Agora são 15:00 — tentar reservar as 14:00 (já passou) deve falhar
        $response = $this->actingAs($cliente)->post('/agendamento/finalizar', [
            'quadra_id' => $quadra->id,
            'data_reserva' => Carbon::today()->toDateString(),
            'horarios' => ['14:00'],
            'esporte' => 'Futevôlei',
            'metodo_pagamento' => 'pix',
        ]);

        $response->assertSessionHasErrors('horario');
        $this->assertDatabaseCount('reservas', 0);

        // Reservar as 16:00 (ainda não chegou) deve funcionar normalmente
        $response = $this->actingAs($cliente)->post('/agendamento/finalizar', [
            'quadra_id' => $quadra->id,
            'data_reserva' => Carbon::today()->toDateString(),
            'horarios' => ['16:00'],
            'esporte' => 'Futevôlei',
            'metodo_pagamento' => 'pix',
        ]);

        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseCount('reservas', 1);

        Carbon::setTestNow();
    }

    public function test_endpoint_de_horarios_disponiveis_marca_horas_passadas(): void
    {
        Carbon::setTestNow(Carbon::today()->setTime(15, 0));

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

        $cliente = User::create([
            'name' => 'Cliente Teste',
            'email' => 'cliente@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'cliente',
        ]);

        $response = $this->actingAs($cliente)->getJson(
            '/agendamento/horarios-disponiveis?quadra_id=' . $quadra->id . '&data=' . Carbon::today()->toDateString()
        );

        $response->assertOk();
        $horarios = collect($response->json('horarios'));

        $this->assertTrue($horarios->firstWhere('hora', '14:00')['passado']);
        $this->assertFalse($horarios->firstWhere('hora', '16:00')['passado']);

        Carbon::setTestNow();
    }
}