<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\Quadra;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuadraEsporteObrigatorioTest extends TestCase
{
    use RefreshDatabase;

    private function criarAdminComArena(): array
    {
        $admin = User::factory()->create(['tipo_conta' => 'admin']);
        $arena = Arena::create([
            'user_id' => $admin->id,
            'nome' => 'Arena Teste',
            'endereco' => 'Rua Teste',
            'telefone' => '11999999999',
        ]);

        return [$admin, $arena];
    }

    public function test_nao_permite_cadastrar_quadra_sem_nenhum_esporte_marcado(): void
    {
        [$admin] = $this->criarAdminComArena();

        $response = $this->actingAs($admin)->post('/admin/quadras/salvar', [
            'nome' => 'Quadra 1',
        ]);

        $response->assertSessionHasErrors(['esportes']);
        $this->assertDatabaseCount('quadras', 0);
    }

    public function test_permite_cadastrar_quadra_com_pelo_menos_um_esporte_marcado(): void
    {
        [$admin] = $this->criarAdminComArena();

        $response = $this->actingAs($admin)->post('/admin/quadras/salvar', [
            'nome' => 'Quadra 1',
            'esportes' => ['Futevôlei'],
        ]);

        $response->assertRedirect('/admin/quadras');
        $this->assertDatabaseHas('quadras', ['nome' => 'Quadra 1']);
    }

    public function test_nao_permite_atualizar_quadra_removendo_todos_os_esportes(): void
    {
        [$admin, $arena] = $this->criarAdminComArena();

        $quadra = Quadra::create([
            'arena_id' => $arena->id,
            'nome' => 'Quadra 1',
            'tipo_esporte' => 'Multiuso',
        ]);

        $response = $this->actingAs($admin)->post("/admin/quadras/{$quadra->id}/atualizar", [
            'nome' => 'Quadra 1',
        ]);

        $response->assertSessionHasErrors(['esportes']);
    }
}
