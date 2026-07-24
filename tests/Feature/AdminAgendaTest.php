<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\Quadra;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAgendaTest extends TestCase
{
    use RefreshDatabase;

    private function criarArenaComReserva(string $nomeArena, string $dataReserva, string $status = 'confirmado'): array
    {
        $admin = User::create([
            'name' => 'Admin ' . $nomeArena,
            'email' => strtolower(str_replace(' ', '', $nomeArena)) . uniqid() . '@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'admin',
        ]);

        $arena = Arena::create([
            'user_id' => $admin->id,
            'nome' => $nomeArena,
            'endereco' => 'Rua Teste',
            'telefone' => '11999999999',
        ]);

        $funcionario = User::create([
            'name' => 'Funcionario ' . $nomeArena,
            'email' => 'funcionario' . uniqid() . '@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'funcionario',
            'arena_id' => $arena->id,
        ]);

        $quadra = Quadra::create([
            'arena_id' => $arena->id,
            'nome' => 'Quadra de ' . $nomeArena,
            'tipo_esporte' => 'Multiuso',
        ]);

        $cliente = User::create([
            'name' => 'Cliente ' . $nomeArena,
            'email' => 'cliente' . uniqid() . '@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'cliente',
        ]);

        $reserva = Reserva::create([
            'user_id' => $cliente->id,
            'quadra_id' => $quadra->id,
            'esporte' => 'Beach Tênis',
            'data_reserva' => $dataReserva,
            'horario' => '18:00',
            'valor_total' => 100,
            'metodo_pagamento' => 'local',
            'status' => $status,
            'pago' => false,
        ]);

        return compact('admin', 'arena', 'funcionario', 'quadra', 'cliente', 'reserva');
    }

    public function test_pagina_da_agenda_carrega_para_o_admin(): void
    {
        $dados = $this->criarArenaComReserva('Arena A', Carbon::today()->toDateString());

        $response = $this->actingAs($dados['admin'])->get('/agenda');

        $response->assertOk();
        $response->assertSee('Agenda');
    }

    public function test_pagina_da_agenda_carrega_para_o_funcionario_da_arena(): void
    {
        $dados = $this->criarArenaComReserva('Arena B', Carbon::today()->toDateString());

        $response = $this->actingAs($dados['funcionario'])->get('/agenda');

        $response->assertOk();
        $response->assertSee('Agenda');
    }

    public function test_cliente_nao_acessa_a_agenda(): void
    {
        $cliente = User::factory()->create(['tipo_conta' => 'cliente']);

        $this->actingAs($cliente)->get('/agenda')->assertRedirect('/');
    }

    public function test_funcionario_sem_arena_vinculada_ve_agenda_vazia_sem_erro(): void
    {
        $funcionario = User::factory()->create(['tipo_conta' => 'funcionario']);

        $response = $this->actingAs($funcionario)->get('/agenda');

        $response->assertOk();
    }

    public function test_dias_com_reservas_retorna_apenas_datas_da_propria_arena_no_mes(): void
    {
        $hoje = Carbon::today();
        $minha = $this->criarArenaComReserva('Arena Minha', $hoje->toDateString());
        $outra = $this->criarArenaComReserva('Arena Outra', $hoje->toDateString());

        $response = $this->actingAs($minha['admin'])
            ->getJson('/agenda/dias-com-reservas?mes=' . $hoje->format('Y-m'));

        $response->assertOk();
        $response->assertJson(['dias' => [$hoje->toDateString()]]);
    }

    public function test_dias_com_reservas_ignora_reservas_canceladas(): void
    {
        $hoje = Carbon::today();
        $dados = $this->criarArenaComReserva('Arena Cancelada', $hoje->toDateString(), 'cancelado');

        $response = $this->actingAs($dados['admin'])
            ->getJson('/agenda/dias-com-reservas?mes=' . $hoje->format('Y-m'));

        $response->assertOk();
        $response->assertJson(['dias' => []]);
    }

    public function test_reservas_do_dia_retorna_apenas_as_da_propria_arena_com_dados_corretos(): void
    {
        $hoje = Carbon::today();
        $minha = $this->criarArenaComReserva('Arena Minha', $hoje->toDateString());
        $this->criarArenaComReserva('Arena Outra', $hoje->toDateString());

        $response = $this->actingAs($minha['admin'])
            ->getJson('/agenda/reservas-do-dia?data=' . $hoje->toDateString());

        $response->assertOk();
        $response->assertJsonCount(1, 'reservas');
        $response->assertJson([
            'reservas' => [
                [
                    'horario' => '18:00',
                    'quadra' => $minha['quadra']->nome,
                    'esporte' => 'Beach Tênis',
                    'cliente' => $minha['cliente']->name,
                ],
            ],
        ]);
    }

    public function test_funcionario_ve_apenas_reservas_da_arena_vinculada(): void
    {
        $hoje = Carbon::today();
        $minha = $this->criarArenaComReserva('Arena Minha F', $hoje->toDateString());
        $this->criarArenaComReserva('Arena Outra F', $hoje->toDateString());

        $response = $this->actingAs($minha['funcionario'])
            ->getJson('/agenda/reservas-do-dia?data=' . $hoje->toDateString());

        $response->assertOk();
        $response->assertJsonCount(1, 'reservas');
        $response->assertJson(['reservas' => [['quadra' => $minha['quadra']->nome]]]);
    }

    public function test_reservas_do_dia_inclui_link_whatsapp_quando_ha_cliente_com_telefone(): void
    {
        $hoje = Carbon::today();
        $dados = $this->criarArenaComReserva('Arena WhatsApp', $hoje->toDateString());
        $dados['cliente']->update(['telefone' => '(11) 99999-8888']);

        $response = $this->actingAs($dados['admin'])
            ->getJson('/agenda/reservas-do-dia?data=' . $hoje->toDateString());

        $response->assertOk();
        $response->assertJson(['reservas' => [['whatsapp' => 'https://wa.me/5511999998888']]]);
    }

    public function test_reservas_do_dia_normaliza_telefone_ja_com_codigo_do_pais(): void
    {
        $hoje = Carbon::today();
        $dados = $this->criarArenaComReserva('Arena WhatsApp Pais', $hoje->toDateString());
        $dados['cliente']->update(['telefone' => '+55 (11) 99999-8888']);

        $response = $this->actingAs($dados['admin'])
            ->getJson('/agenda/reservas-do-dia?data=' . $hoje->toDateString());

        $response->assertOk();
        $response->assertJson(['reservas' => [['whatsapp' => 'https://wa.me/5511999998888']]]);
    }

    public function test_reservas_do_dia_nao_inclui_whatsapp_quando_cliente_sem_telefone(): void
    {
        $hoje = Carbon::today();
        $dados = $this->criarArenaComReserva('Arena Sem Telefone', $hoje->toDateString());

        $response = $this->actingAs($dados['admin'])
            ->getJson('/agenda/reservas-do-dia?data=' . $hoje->toDateString());

        $response->assertOk();
        $response->assertJson(['reservas' => [['whatsapp' => null]]]);
    }

    public function test_reservas_do_dia_nao_inclui_whatsapp_para_reserva_sem_cadastro_de_cliente(): void
    {
        $hoje = Carbon::today();
        $dados = $this->criarArenaComReserva('Arena Sem Cadastro', $hoje->toDateString());
        // Reserva anotada pela recepção sem conta de cliente: user_id aponta pro
        // próprio funcionário/admin, reservado_para guarda o nome digitado.
        $dados['cliente']->update(['telefone' => '11999998888']);
        $dados['reserva']->update([
            'user_id' => $dados['admin']->id,
            'reservado_para' => 'Fulano Avulso',
        ]);

        $response = $this->actingAs($dados['admin'])
            ->getJson('/agenda/reservas-do-dia?data=' . $hoje->toDateString());

        $response->assertOk();
        $response->assertJson(['reservas' => [['whatsapp' => null, 'cliente' => 'Fulano Avulso']]]);
    }
}
