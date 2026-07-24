<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CadastroAdminWizardTest extends TestCase
{
    use RefreshDatabase;

    public function test_wizard_step_1_cria_usuario_admin_e_loga_e_redireciona_para_step_2(): void
    {
        $response = $this->post('/cadastro/administrativo', [
            'name' => 'Dono Teste',
            'email' => 'dono@test.com',
            'telefone' => '(11) 99999-9999',
            'password' => 'segredo123',
        ]);

        $response->assertRedirect('/admin/arena/nova');
        $this->assertDatabaseHas('users', [
            'email' => 'dono@test.com',
            'tipo_conta' => 'admin',
        ]);
        $this->assertAuthenticated();
    }

    public function test_wizard_completo_cria_usuario_e_arena(): void
    {
        $this->post('/cadastro/administrativo', [
            'name' => 'Dono Teste',
            'email' => 'dono@test.com',
            'telefone' => '(11) 99999-9999',
            'password' => 'segredo123',
        ]);

        $response = $this->post('/admin/arena/salvar', [
            'nome' => 'Arena do Dono',
            'endereco' => 'Rua Nova, 123',
            'telefone' => '(11) 98888-8888',
            'dias_semana' => ['1', '2', '3'],
            'hora_abertura' => '08:00',
            'hora_fechamento' => '22:00',
        ]);

        $response->assertRedirect('/admin/dashboard');

        $user = User::where('email', 'dono@test.com')->firstOrFail();
        $arena = Arena::where('user_id', $user->id)->where('nome', 'Arena do Dono')->firstOrFail();

        $this->assertSame($arena->id, $user->fresh()->arena_id);

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertOk();
    }

    public function test_login_administrativo_tem_link_para_o_wizard_de_cadastro(): void
    {
        $this->get('/login/administrativo')
            ->assertOk()
            ->assertSee('/cadastro/administrativo', false);
    }
}
