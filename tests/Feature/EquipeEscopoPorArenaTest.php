<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipeEscopoPorArenaTest extends TestCase
{
    use RefreshDatabase;

    private function criarAdminComArenaEFuncionario(string $nomeArena): array
    {
        $admin = User::factory()->create(['tipo_conta' => 'admin']);

        $arena = Arena::create([
            'user_id' => $admin->id,
            'nome' => $nomeArena,
            'endereco' => 'Rua Teste',
            'telefone' => '11999999999',
        ]);

        $funcionario = User::factory()->create([
            'tipo_conta' => 'funcionario',
            'arena_id' => $arena->id,
        ]);

        return compact('admin', 'arena', 'funcionario');
    }

    public function test_admin_ve_apenas_funcionarios_da_propria_arena(): void
    {
        $minha = $this->criarAdminComArenaEFuncionario('Arena Minha');
        $outra = $this->criarAdminComArenaEFuncionario('Arena Outra');

        $response = $this->actingAs($minha['admin'])->get('/admin/equipe');

        $response->assertOk();
        $response->assertSee($minha['funcionario']->name);
        $response->assertDontSee($outra['funcionario']->name);
    }

    public function test_admin_sem_arena_ve_lista_vazia_sem_erro(): void
    {
        $admin = User::factory()->create(['tipo_conta' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/equipe');

        $response->assertOk();
    }

    public function test_admin_nao_consegue_editar_funcionario_de_outra_arena(): void
    {
        $minha = $this->criarAdminComArenaEFuncionario('Arena Minha');
        $outra = $this->criarAdminComArenaEFuncionario('Arena Outra');

        $response = $this->actingAs($minha['admin'])->post('/admin/equipe/' . $outra['funcionario']->id . '/atualizar', [
            'name' => 'Nome Trocado',
            'email' => $outra['funcionario']->email,
            'telefone' => '11988887777',
        ]);

        $response->assertNotFound();
        $this->assertNotSame('Nome Trocado', $outra['funcionario']->fresh()->name);
    }

    public function test_admin_nao_consegue_excluir_funcionario_de_outra_arena(): void
    {
        $minha = $this->criarAdminComArenaEFuncionario('Arena Minha');
        $outra = $this->criarAdminComArenaEFuncionario('Arena Outra');

        $response = $this->actingAs($minha['admin'])->post('/admin/equipe/' . $outra['funcionario']->id . '/excluir');

        $response->assertNotFound();
        $this->assertDatabaseHas('users', ['id' => $outra['funcionario']->id]);
    }

    public function test_admin_consegue_editar_funcionario_da_propria_arena(): void
    {
        $minha = $this->criarAdminComArenaEFuncionario('Arena Minha');

        $response = $this->actingAs($minha['admin'])->post('/admin/equipe/' . $minha['funcionario']->id . '/atualizar', [
            'name' => 'Nome Novo',
            'email' => $minha['funcionario']->email,
            'telefone' => '11988887777',
        ]);

        $response->assertRedirect('/admin/equipe');
        $this->assertSame('Nome Novo', $minha['funcionario']->fresh()->name);
    }
}
