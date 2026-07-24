<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\ArenaFuncionamento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardStatusArenaTest extends TestCase
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

    public function test_mostra_aberta_quando_horario_atual_esta_dentro_do_funcionamento(): void
    {
        Carbon::setTestNow(Carbon::today()->setTime(10, 0));
        [$admin, $arena] = $this->criarAdminComArena();

        ArenaFuncionamento::create([
            'arena_id' => $arena->id,
            'dia_semana' => Carbon::today()->dayOfWeek,
            'hora_abertura' => '08:00:00',
            'hora_fechamento' => '22:00:00',
            'ativo' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Aberta');
        $response->assertSee('Fecha às 22:00');

        Carbon::setTestNow();
    }

    public function test_mostra_fechada_quando_horario_atual_esta_fora_do_funcionamento(): void
    {
        Carbon::setTestNow(Carbon::today()->setTime(23, 0));
        [$admin, $arena] = $this->criarAdminComArena();

        ArenaFuncionamento::create([
            'arena_id' => $arena->id,
            'dia_semana' => Carbon::today()->dayOfWeek,
            'hora_abertura' => '08:00:00',
            'hora_fechamento' => '22:00:00',
            'ativo' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Fechada');
        $response->assertSee('Fechou às 22:00');

        Carbon::setTestNow();
    }

    public function test_mostra_fechada_quando_arena_nao_opera_no_dia_de_hoje(): void
    {
        Carbon::setTestNow(Carbon::today()->setTime(10, 0));
        [$admin, $arena] = $this->criarAdminComArena();

        // Cadastra funcionamento só para um dia diferente do de hoje
        $diaDiferente = (Carbon::today()->dayOfWeek + 1) % 7;
        ArenaFuncionamento::create([
            'arena_id' => $arena->id,
            'dia_semana' => $diaDiferente,
            'hora_abertura' => '08:00:00',
            'hora_fechamento' => '22:00:00',
            'ativo' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Fechada');
        $response->assertSee('Não opera hoje');

        Carbon::setTestNow();
    }
}
