<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ArenaEdicaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_arena_edit_screen_even_if_functioning_table_is_missing(): void
    {
        $user = User::factory()->create(['tipo_conta' => 'admin']);
        $arena = Arena::create([
            'user_id' => $user->id,
            'nome' => 'Arena Antiga',
            'endereco' => 'Rua Antiga',
            'telefone' => '1111',
        ]);

        Schema::dropIfExists('arena_funcionamentos');

        $this->actingAs($user)
            ->get('/admin/arena/' . $arena->id . '/editar')
            ->assertOk();
    }

    public function test_admin_can_save_multiple_weekdays_with_shared_hours(): void
    {
        $user = User::factory()->create(['tipo_conta' => 'admin']);

        $this->actingAs($user)
            ->post('/admin/arena/salvar', [
                'nome' => 'Arena Nova',
                'endereco' => 'Rua Nova',
                'telefone' => '(11) 99999-9999',
                'dias_semana' => ['1', '2'],
                'hora_abertura' => '08:00',
                'hora_fechamento' => '20:00',
            ])
            ->assertRedirect('/admin/dashboard');

        $arena = Arena::where('user_id', $user->id)->latest('id')->first();

        $this->assertDatabaseHas('arena_funcionamentos', [
            'arena_id' => $arena->id,
            'dia_semana' => 1,
            'hora_abertura' => '08:00:00',
            'hora_fechamento' => '20:00:00',
        ]);

        $this->assertDatabaseHas('arena_funcionamentos', [
            'arena_id' => $arena->id,
            'dia_semana' => 2,
            'hora_abertura' => '08:00:00',
            'hora_fechamento' => '20:00:00',
        ]);
    }

    public function test_admin_cannot_save_without_days_or_hours(): void
    {
        $user = User::factory()->create(['tipo_conta' => 'admin']);

        $this->actingAs($user)
            ->post('/admin/arena/salvar', [
                'nome' => 'Arena Nova',
                'endereco' => 'Rua Nova',
                'telefone' => '(11) 99999-9999',
            ])
            ->assertSessionHasErrors(['dias_semana', 'hora_abertura', 'hora_fechamento']);
    }

    public function test_admin_receives_validation_errors_for_blank_name_and_address(): void
    {
        $user = User::factory()->create(['tipo_conta' => 'admin']);

        $this->actingAs($user)
            ->post('/admin/arena/salvar', [
                'nome' => '',
                'endereco' => '',
                'telefone' => '(11) 99999-9999',
                'dias_semana' => ['1'],
                'hora_abertura' => '08:00',
                'hora_fechamento' => '20:00',
            ])
            ->assertSessionHasErrors(['nome', 'endereco']);
    }

    public function test_admin_can_update_arena_data(): void
    {
        $user = User::factory()->create(['tipo_conta' => 'admin']);
        $arena = Arena::create([
            'user_id' => $user->id,
            'nome' => 'Arena Antiga',
            'endereco' => 'Rua Antiga',
            'telefone' => '1111',
        ]);

        $this->actingAs($user)
            ->post('/admin/arena/' . $arena->id . '/atualizar', [
                'nome' => 'Arena Nova',
                'endereco' => 'Rua Nova',
                'telefone' => '(11) 99999-9999',
                'dias_semana' => ['1'],
                'hora_abertura' => '08:00',
                'hora_fechamento' => '20:00',
            ])
            ->assertRedirect('/admin/dashboard');

        $this->assertDatabaseHas('arenas', [
            'id' => $arena->id,
            'nome' => 'Arena Nova',
            'endereco' => 'Rua Nova',
            'telefone' => '(11) 99999-9999',
        ]);
    }
}
