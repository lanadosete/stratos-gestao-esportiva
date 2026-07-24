<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\Quadra;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecepcaoEscopoPorArenaTest extends TestCase
{
    use RefreshDatabase;

    private function criarArenaComReserva(string $nomeArena): array
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

        $admin->update(['arena_id' => $arena->id]);

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
            'data_reserva' => Carbon::today()->toDateString(),
            'horario' => '18:00',
            'valor_total' => 100,
            'metodo_pagamento' => 'local',
            'status' => 'confirmado',
            'pago' => false,
        ]);

        return compact('admin', 'arena', 'quadra', 'reserva');
    }

    public function test_admin_ve_apenas_as_reservas_da_propria_arena(): void
    {
        $minhaArena = $this->criarArenaComReserva('Arena Minha');
        $outraArena = $this->criarArenaComReserva('Arena Outra');

        $response = $this->actingAs($minhaArena['admin'])->get('/recepcao');

        $response->assertOk();
        $response->assertSee($minhaArena['reserva']->horario);
        $response->assertDontSee($outraArena['quadra']->nome);
    }

    public function test_funcionario_ve_apenas_as_reservas_da_arena_vinculada(): void
    {
        $minhaArena = $this->criarArenaComReserva('Arena Minha');
        $outraArena = $this->criarArenaComReserva('Arena Outra');

        $funcionario = User::create([
            'name' => 'Funcionario Teste',
            'email' => 'func' . uniqid() . '@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'funcionario',
            'arena_id' => $minhaArena['arena']->id,
        ]);

        $response = $this->actingAs($funcionario)->get('/recepcao');

        $response->assertOk();
        $response->assertSee($minhaArena['quadra']->nome);
        $response->assertDontSee($outraArena['quadra']->nome);
    }

    public function test_funcionario_sem_arena_vinculada_ve_lista_vazia_sem_erro(): void
    {
        $this->criarArenaComReserva('Arena Qualquer');

        $funcionario = User::create([
            'name' => 'Funcionario Sem Arena',
            'email' => 'semvinculo' . uniqid() . '@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'funcionario',
        ]);

        $response = $this->actingAs($funcionario)->get('/recepcao');

        $response->assertOk();
        $response->assertSee('Nenhuma reserva para hoje');
    }
}
