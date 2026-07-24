<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\Quadra;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteAgendamentosEntrarEmContatoTest extends TestCase
{
    use RefreshDatabase;

    private function criarReservaDoCliente(string $dataReserva, array $overridesArena = []): array
    {
        $admin = User::create([
            'name' => 'Admin Teste',
            'email' => 'admin' . uniqid() . '@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'admin',
        ]);

        $arena = Arena::create(array_merge([
            'user_id' => $admin->id,
            'nome' => 'Arena Teste',
            'endereco' => 'Rua Teste',
            'telefone' => '(11) 98888-7777',
        ], $overridesArena));

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

        $reserva = Reserva::create([
            'user_id' => $cliente->id,
            'quadra_id' => $quadra->id,
            'esporte' => 'Beach Tênis',
            'data_reserva' => $dataReserva,
            'horario' => '18:00',
            'valor_total' => 100,
            'metodo_pagamento' => 'local',
            'status' => 'confirmado',
            'pago' => false,
        ]);

        return compact('arena', 'quadra', 'cliente', 'reserva');
    }

    public function test_botao_entrar_em_contato_aparece_em_jogo_futuro_com_link_da_arena(): void
    {
        $dados = $this->criarReservaDoCliente(Carbon::tomorrow()->toDateString());

        $response = $this->actingAs($dados['cliente'])->get('/cliente/agendamentos');

        $response->assertOk();
        $response->assertSee('Entrar em contato');
        $response->assertSee('https://wa.me/5511988887777', false);
    }

    public function test_botao_entrar_em_contato_aparece_no_historico_passado(): void
    {
        $dados = $this->criarReservaDoCliente(Carbon::yesterday()->toDateString());

        $response = $this->actingAs($dados['cliente'])->get('/cliente/agendamentos');

        $response->assertOk();
        $response->assertSee('Entrar em contato');
        $response->assertSee('https://wa.me/5511988887777', false);
    }

    public function test_botao_nao_aparece_quando_arena_nao_tem_telefone(): void
    {
        $dados = $this->criarReservaDoCliente(Carbon::tomorrow()->toDateString());
        $dados['arena']->update(['telefone' => '']);

        $response = $this->actingAs($dados['cliente'])->get('/cliente/agendamentos');

        $response->assertOk();
        $response->assertDontSee('Entrar em contato');
    }
}
