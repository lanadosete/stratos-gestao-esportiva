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

class ReservaStatusTest extends TestCase
{
    use RefreshDatabase;

    private function criarReserva(array $overrides = []): Reserva
    {
        $admin = User::create([
            'name' => 'Dono Teste',
            'email' => 'dono' . uniqid() . '@test.com',
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

        $cliente = User::create([
            'name' => 'Cliente Teste',
            'email' => 'cliente' . uniqid() . '@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'cliente',
        ]);

        return Reserva::create(array_merge([
            'user_id' => $cliente->id,
            'quadra_id' => $quadra->id,
            'data_reserva' => Carbon::today()->toDateString(),
            'horario' => '18:00',
            'valor_total' => 100,
            'metodo_pagamento' => 'local',
            'status' => 'confirmado',
            'pago' => false,
        ], $overrides));
    }

    public function test_reserva_via_pix_nasce_marcada_como_paga_e_local_nao(): void
    {
        Carbon::setTestNow(Carbon::today()->setTime(10, 0));

        $admin = User::create([
            'name' => 'Admin Teste',
            'email' => 'admin' . uniqid() . '@test.com',
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
        QuadraPrecoTurno::create(['quadra_id' => $quadra->id, 'esporte' => 'Futevôlei', 'turno' => 'Noite', 'valor_hora' => 100]);

        $cliente = User::create([
            'name' => 'Cliente Teste',
            'email' => 'cliente' . uniqid() . '@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'cliente',
        ]);

        $this->actingAs($cliente)->post('/agendamento/finalizar', [
            'quadra_id' => $quadra->id,
            'data_reserva' => Carbon::today()->toDateString(),
            'horarios' => ['20:00'],
            'esporte' => 'Futevôlei',
            'metodo_pagamento' => 'pix',
        ]);

        $reservaPix = Reserva::where('quadra_id', $quadra->id)->where('horario', '20:00')->firstOrFail();
        $this->assertTrue($reservaPix->pago);

        $this->actingAs($cliente)->post('/agendamento/finalizar', [
            'quadra_id' => $quadra->id,
            'data_reserva' => Carbon::today()->toDateString(),
            'horarios' => ['21:00'],
            'esporte' => 'Futevôlei',
            'metodo_pagamento' => 'local',
        ]);

        $reservaLocal = Reserva::where('quadra_id', $quadra->id)->where('horario', '21:00')->firstOrFail();
        $this->assertFalse($reservaLocal->pago);

        Carbon::setTestNow();
    }

    public function test_status_calculado_a_iniciar_para_horario_futuro(): void
    {
        $amanha = Carbon::tomorrow();
        $reserva = $this->criarReserva([
            'data_reserva' => $amanha->toDateString(),
            'horario' => '20:00',
        ]);

        $this->assertSame('a_iniciar', $reserva->status_calculado);
    }

    public function test_status_calculado_em_jogo_durante_o_horario(): void
    {
        Carbon::setTestNow(Carbon::today()->setTime(18, 30));

        $reserva = $this->criarReserva([
            'data_reserva' => Carbon::today()->toDateString(),
            'horario' => '18:00',
        ]);

        $this->assertSame('em_jogo', $reserva->status_calculado);

        Carbon::setTestNow();
    }

    public function test_status_calculado_finalizado_apos_o_horario(): void
    {
        $reserva = $this->criarReserva([
            'data_reserva' => Carbon::yesterday()->toDateString(),
            'horario' => '18:00',
        ]);

        $this->assertSame('finalizado', $reserva->status_calculado);
    }

    public function test_status_calculado_cancelado_prevalece_sobre_o_tempo(): void
    {
        $reserva = $this->criarReserva([
            'data_reserva' => Carbon::tomorrow()->toDateString(),
            'status' => 'cancelado',
        ]);

        $this->assertSame('cancelado', $reserva->status_calculado);
    }

    public function test_admin_pode_cancelar_reserva_de_outro_usuario(): void
    {
        $reserva = $this->criarReserva();

        $admin = User::create([
            'name' => 'Admin Cancelador',
            'email' => 'admincancel@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post("/reservas/{$reserva->id}/cancelar");

        $response->assertRedirect();
        $this->assertSame('cancelado', $reserva->fresh()->status);
    }

    public function test_cliente_nao_pode_cancelar_reserva_de_outro_cliente(): void
    {
        $reserva = $this->criarReserva();

        $outroCliente = User::create([
            'name' => 'Outro Cliente',
            'email' => 'outro@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'cliente',
        ]);

        $response = $this->actingAs($outroCliente)->post("/reservas/{$reserva->id}/cancelar");

        $response->assertForbidden();
        $this->assertSame('confirmado', $reserva->fresh()->status);
    }

    public function test_recepcao_confirma_pagamento_sem_alterar_status(): void
    {
        $reserva = $this->criarReserva([
            'data_reserva' => Carbon::tomorrow()->toDateString(),
        ]);

        $funcionario = User::create([
            'name' => 'Funcionario Teste',
            'email' => 'func@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'funcionario',
        ]);

        $response = $this->actingAs($funcionario)->post("/recepcao/reservas/{$reserva->id}/pagamento");

        $response->assertRedirect();
        $reserva->refresh();
        $this->assertTrue($reserva->pago);
        $this->assertSame('confirmado', $reserva->status);
        $this->assertSame('a_iniciar', $reserva->status_calculado);
    }
}