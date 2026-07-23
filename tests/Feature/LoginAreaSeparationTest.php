<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginAreaSeparationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cliente_nao_consegue_logar_pela_area_administrativa(): void
    {
        $cliente = User::create([
            'name' => 'Cliente Teste',
            'email' => 'cliente@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'cliente',
        ]);

        $response = $this->post('/login/administrativo', [
            'email' => $cliente->email,
            'password' => '12345678',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_nao_consegue_logar_pela_area_principal(): void
    {
        $admin = User::create([
            'name' => 'Admin Teste',
            'email' => 'admin@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => '12345678',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_funcionario_nao_consegue_logar_pela_area_principal(): void
    {
        $funcionario = User::create([
            'name' => 'Funcionario Teste',
            'email' => 'funcionario@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'funcionario',
        ]);

        $response = $this->post('/login', [
            'email' => $funcionario->email,
            'password' => '12345678',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_cliente_loga_normalmente_pela_area_principal(): void
    {
        $cliente = User::create([
            'name' => 'Cliente Teste',
            'email' => 'cliente@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'cliente',
        ]);

        $response = $this->post('/login', [
            'email' => $cliente->email,
            'password' => '12345678',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($cliente);
    }

    public function test_admin_loga_normalmente_pela_area_administrativa(): void
    {
        $admin = User::create([
            'name' => 'Admin Teste',
            'email' => 'admin@test.com',
            'password' => bcrypt('12345678'),
            'tipo_conta' => 'admin',
        ]);

        $response = $this->post('/login/administrativo', [
            'email' => $admin->email,
            'password' => '12345678',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }
}