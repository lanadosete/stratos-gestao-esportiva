<?php

namespace Tests\Feature;

use App\Models\Complexo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ComplexoEdicaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_complexo_edit_screen_even_if_functioning_table_is_missing(): void
    {
        $user = User::factory()->create(['tipo_conta' => 'admin']);
        $complexo = Complexo::create([
            'user_id' => $user->id,
            'nome' => 'Complexo Antigo',
            'endereco' => 'Rua Antiga',
            'telefone' => '1111',
        ]);

        Schema::dropIfExists('complexo_funcionamentos');

        $this->actingAs($user)
            ->get('/admin/complexo/' . $complexo->id . '/editar')
            ->assertOk();
    }

    public function test_admin_can_save_multiple_weekdays_with_shared_hours(): void
    {
        $user = User::factory()->create(['tipo_conta' => 'admin']);

        $this->actingAs($user)
            ->post('/admin/complexo/salvar', [
                'nome' => 'Complexo Novo',
                'endereco' => 'Rua Nova',
                'telefone' => '(11) 99999-9999',
                'dias_semana' => ['1', '2'],
                'hora_abertura' => '08:00',
                'hora_fechamento' => '20:00',
            ])
            ->assertRedirect('/admin/dashboard');

        $complexo = Complexo::where('user_id', $user->id)->latest('id')->first();

        $this->assertDatabaseHas('complexo_funcionamentos', [
            'complexo_id' => $complexo->id,
            'dia_semana' => 1,
            'hora_abertura' => '08:00:00',
            'hora_fechamento' => '20:00:00',
        ]);

        $this->assertDatabaseHas('complexo_funcionamentos', [
            'complexo_id' => $complexo->id,
            'dia_semana' => 2,
            'hora_abertura' => '08:00:00',
            'hora_fechamento' => '20:00:00',
        ]);
    }

    public function test_admin_cannot_save_without_days_or_hours(): void
    {
        $user = User::factory()->create(['tipo_conta' => 'admin']);

        $this->actingAs($user)
            ->post('/admin/complexo/salvar', [
                'nome' => 'Complexo Novo',
                'endereco' => 'Rua Nova',
                'telefone' => '(11) 99999-9999',
            ])
            ->assertSessionHasErrors(['dias_semana', 'hora_abertura', 'hora_fechamento']);
    }

    public function test_admin_receives_validation_errors_for_blank_name_and_address(): void
    {
        $user = User::factory()->create(['tipo_conta' => 'admin']);

        $this->actingAs($user)
            ->post('/admin/complexo/salvar', [
                'nome' => '',
                'endereco' => '',
                'telefone' => '(11) 99999-9999',
                'dias_semana' => ['1'],
                'hora_abertura' => '08:00',
                'hora_fechamento' => '20:00',
            ])
            ->assertSessionHasErrors(['nome', 'endereco']);
    }

    public function test_admin_can_update_complexo_data(): void
    {
        $user = User::factory()->create(['tipo_conta' => 'admin']);
        $complexo = Complexo::create([
            'user_id' => $user->id,
            'nome' => 'Complexo Antigo',
            'endereco' => 'Rua Antiga',
            'telefone' => '1111',
        ]);

        $this->actingAs($user)
            ->post('/admin/complexo/' . $complexo->id . '/atualizar', [
                'nome' => 'Complexo Novo',
                'endereco' => 'Rua Nova',
                'telefone' => '(11) 99999-9999',
                'dias_semana' => ['1'],
                'hora_abertura' => '08:00',
                'hora_fechamento' => '20:00',
            ])
            ->assertRedirect('/admin/dashboard');

        $this->assertDatabaseHas('complexos', [
            'id' => $complexo->id,
            'nome' => 'Complexo Novo',
            'endereco' => 'Rua Nova',
            'telefone' => '(11) 99999-9999',
        ]);
    }
}
