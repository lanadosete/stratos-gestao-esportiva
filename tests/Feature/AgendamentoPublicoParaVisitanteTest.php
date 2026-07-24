<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\ArenaFuncionamento;
use App\Models\Quadra;
use App\Models\QuadraEsporte;
use App\Models\QuadraPrecoTurno;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendamentoPublicoParaVisitanteTest extends TestCase
{
    use RefreshDatabase;

    private function criarArenaComQuadra(): array
    {
        $admin = User::factory()->create(['tipo_conta' => 'admin']);

        $arena = Arena::create([
            'user_id' => $admin->id,
            'nome' => 'Arena Pública',
            'endereco' => 'Rua Teste',
            'telefone' => '11999999999',
        ]);

        ArenaFuncionamento::create([
            'arena_id' => $arena->id,
            'dia_semana' => Carbon::today()->dayOfWeek,
            'hora_abertura' => '08:00:00',
            'hora_fechamento' => '23:00:00',
            'ativo' => true,
        ]);

        $quadra = Quadra::create([
            'arena_id' => $arena->id,
            'nome' => 'Quadra Pública',
            'tipo_esporte' => 'Multiuso',
        ]);

        QuadraEsporte::create(['quadra_id' => $quadra->id, 'nome' => 'Beach Tênis', 'ativo' => true]);
        QuadraPrecoTurno::create(['quadra_id' => $quadra->id, 'esporte' => 'Beach Tênis', 'turno' => 'Noite', 'valor_hora' => 100]);

        return compact('arena', 'quadra');
    }

    public function test_visitante_nao_logado_navega_pelos_passos_de_escolha(): void
    {
        ['arena' => $arena, 'quadra' => $quadra] = $this->criarArenaComQuadra();

        $this->get('/agendamento')->assertOk()->assertViewIs('agendamento.arenas');

        $this->get('/agendamento/quadras?arena_id=' . $arena->id)
            ->assertOk()
            ->assertViewIs('agendamento.quadras');

        $this->get('/agendamento/data?quadra_id=' . $quadra->id)
            ->assertOk()
            ->assertViewIs('agendamento.data');

        $this->getJson('/agendamento/horarios-disponiveis?quadra_id=' . $quadra->id . '&data=' . Carbon::today()->toDateString())
            ->assertOk()
            ->assertJson(['aberto' => true]);
    }

    public function test_visitante_nao_logado_e_barrado_apenas_na_etapa_de_pagamento(): void
    {
        ['quadra' => $quadra] = $this->criarArenaComQuadra();

        $this->get('/agendamento/pagamento?quadra_id=' . $quadra->id)
            ->assertRedirect('/login');
    }

    public function test_visitante_e_barrado_ao_tentar_finalizar_reserva_sem_login(): void
    {
        ['quadra' => $quadra] = $this->criarArenaComQuadra();

        $this->post('/agendamento/finalizar', [
            'quadra_id' => $quadra->id,
            'data_reserva' => Carbon::today()->toDateString(),
            'horarios' => ['20:00'],
            'esporte' => 'Beach Tênis',
            'metodo_pagamento' => 'pix',
        ])->assertRedirect('/login');
    }

    public function test_apos_login_visitante_e_levado_de_volta_para_a_tela_de_pagamento_com_a_selecao_preservada(): void
    {
        ['quadra' => $quadra] = $this->criarArenaComQuadra();
        $cliente = User::factory()->create(['tipo_conta' => 'cliente', 'password' => bcrypt('12345678')]);

        $urlPagamento = '/agendamento/pagamento?quadra_id=' . $quadra->id
            . '&data=' . Carbon::today()->toDateString()
            . '&horario=21%3A00&esporte=Beach+T%C3%AAnis';

        // Visitante tenta ir para o pagamento — Laravel guarda essa URL como "intended"
        $this->get($urlPagamento)->assertRedirect('/login');

        // Loga na mesma sessão (o teste HTTP do Laravel mantém a sessão entre requests)
        $response = $this->post('/login', [
            'email' => $cliente->email,
            'password' => '12345678',
        ]);

        $location = $response->headers->get('Location');
        $path = parse_url($location, PHP_URL_PATH);
        parse_str(parse_url($location, PHP_URL_QUERY), $params);

        $this->assertSame('/agendamento/pagamento', $path);
        $this->assertSame((string) $quadra->id, $params['quadra_id']);
        $this->assertSame(Carbon::today()->toDateString(), $params['data']);
        $this->assertSame('21:00', $params['horario']);
        $this->assertSame('Beach Tênis', $params['esporte']);
    }
}
