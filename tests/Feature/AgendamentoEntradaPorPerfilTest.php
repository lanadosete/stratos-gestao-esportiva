<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendamentoEntradaPorPerfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_e_redirecionado_direto_para_as_quadras_da_sua_arena(): void
    {
        $admin = User::factory()->create(['tipo_conta' => 'admin']);
        $arena = Arena::create([
            'user_id' => $admin->id,
            'nome' => 'Arena do Admin',
            'endereco' => 'Rua A',
            'telefone' => '1111',
        ]);

        $this->actingAs($admin)
            ->get('/agendamento')
            ->assertRedirect('/agendamento/quadras?arena_id=' . $arena->id);
    }

    public function test_admin_sem_arena_e_redirecionado_para_cadastro_de_arena(): void
    {
        $admin = User::factory()->create(['tipo_conta' => 'admin']);

        $this->actingAs($admin)
            ->get('/agendamento')
            ->assertRedirect('/admin/arena/nova');
    }

    public function test_funcionario_e_redirecionado_direto_para_as_quadras_da_arena_vinculada(): void
    {
        $admin = User::factory()->create(['tipo_conta' => 'admin']);
        $arena = Arena::create([
            'user_id' => $admin->id,
            'nome' => 'Arena do Admin',
            'endereco' => 'Rua A',
            'telefone' => '1111',
        ]);
        $funcionario = User::factory()->create([
            'tipo_conta' => 'funcionario',
            'arena_id' => $arena->id,
        ]);

        $this->actingAs($funcionario)
            ->get('/agendamento')
            ->assertRedirect('/agendamento/quadras?arena_id=' . $arena->id);
    }

    public function test_funcionario_sem_arena_vinculada_e_redirecionado_para_recepcao(): void
    {
        $funcionario = User::factory()->create(['tipo_conta' => 'funcionario']);

        $this->actingAs($funcionario)
            ->get('/agendamento')
            ->assertRedirect('/recepcao');
    }

    public function test_cliente_continua_vendo_a_lista_de_arenas(): void
    {
        $cliente = User::factory()->create(['tipo_conta' => 'cliente']);

        $this->actingAs($cliente)
            ->get('/agendamento')
            ->assertOk()
            ->assertViewIs('agendamento.arenas');
    }

    public function test_novo_funcionario_e_vinculado_automaticamente_a_arena_do_admin_que_o_cadastrou(): void
    {
        $admin = User::factory()->create(['tipo_conta' => 'admin']);
        $arena = Arena::create([
            'user_id' => $admin->id,
            'nome' => 'Arena do Admin',
            'endereco' => 'Rua A',
            'telefone' => '1111',
        ]);

        $this->actingAs($admin)->post('/admin/equipe/salvar', [
            'name' => 'Novo Funcionario',
            'email' => 'novo.funcionario@test.com',
            'telefone' => '(11) 99999-9999',
            'password' => 'segredo123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'novo.funcionario@test.com',
            'tipo_conta' => 'funcionario',
            'arena_id' => $arena->id,
        ]);
    }
}
