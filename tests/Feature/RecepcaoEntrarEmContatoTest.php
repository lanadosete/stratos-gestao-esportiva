<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\Quadra;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecepcaoEntrarEmContatoTest extends TestCase
{
    use RefreshDatabase;

    private function criarArenaComReserva(array $overridesReserva = []): array
    {
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

        $cliente = User::create([
            'name' => 'Cliente Teste',
            'email' => 'cliente' . uniqid() . '@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'cliente',
            'telefone' => '(11) 99999-8888',
        ]);

        $reserva = Reserva::create(array_merge([
            'user_id' => $cliente->id,
            'quadra_id' => $quadra->id,
            'esporte' => 'Beach Tênis',
            'data_reserva' => Carbon::today()->toDateString(),
            'horario' => '18:00',
            'valor_total' => 100,
            'metodo_pagamento' => 'local',
            'status' => 'confirmado',
            'pago' => false,
        ], $overridesReserva));

        return compact('admin', 'arena', 'quadra', 'cliente', 'reserva');
    }

    public function test_botao_entrar_em_contato_aparece_com_link_do_whatsapp_para_reserva_de_cliente(): void
    {
        $dados = $this->criarArenaComReserva();

        $response = $this->actingAs($dados['admin'])->get('/recepcao');

        $response->assertOk();
        $response->assertSee('Entrar em contato');
        $response->assertSee('https://wa.me/5511999998888', false);
    }

    public function test_botao_entrar_em_contato_nao_aparece_para_reserva_sem_cadastro_de_cliente(): void
    {
        $dados = $this->criarArenaComReserva();
        $dados['reserva']->update([
            'user_id' => $dados['admin']->id,
            'reservado_para' => 'Fulano Avulso',
        ]);

        $response = $this->actingAs($dados['admin'])->get('/recepcao');

        $response->assertOk();
        $response->assertDontSee('Entrar em contato');
    }

    public function test_botao_entrar_em_contato_nao_aparece_quando_cliente_nao_tem_telefone(): void
    {
        $dados = $this->criarArenaComReserva();
        $dados['cliente']->update(['telefone' => null]);

        $response = $this->actingAs($dados['admin'])->get('/recepcao');

        $response->assertOk();
        $response->assertDontSee('Entrar em contato');
    }

    public function test_botao_entrar_em_contato_aparece_mesmo_para_reserva_cancelada(): void
    {
        $dados = $this->criarArenaComReserva(['status' => 'cancelado']);

        $response = $this->actingAs($dados['admin'])->get('/recepcao');

        $response->assertOk();
        $response->assertSee('Entrar em contato');
    }
}
