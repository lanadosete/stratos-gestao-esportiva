<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    AuthController,
    AgendaController,
    ArenaController,
    QuadraController,
    EquipeController,
    FinanceiroController,
    ReservaController
};
use App\Models\{Arena, Reserva};
use Carbon\Carbon;

// ==========================================
// ROTA PÚBLICA (Landing Page)
// ==========================================
Route::get('/', function () { return view('home'); });

// ==========================================
// ROTA DE GUEST (Login/Cadastro)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/cadastro', function () { return view('auth.register'); });
    Route::get('/cadastro/administrativo', function () { return view('auth.cadastro-admin'); });
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::get('/login/administrativo', function () { return view('auth.login-admin'); });
    Route::post('/cadastro', [AuthController::class, 'registrar']);
    Route::post('/cadastro/administrativo', [AuthController::class, 'registrarAdmin']);
    Route::post('/login', [AuthController::class, 'entrar']);
    Route::post('/login/administrativo', [AuthController::class, 'entrarStaff']);
});

// ==========================================
// FLUXO DE AGENDAMENTO — PASSOS PÚBLICOS (navegação sem exigir login)
// ==========================================
Route::prefix('agendamento')->group(function () {
    // 1. Buscar arena (Admin e Funcionário logados pulam esta etapa e vão direto
    // para as quadras da sua arena; visitantes e clientes veem a lista pra navegar)
    Route::get('/', function () {
        if (Auth::check() && Auth::user()->tipo_conta === 'admin') {
            $arena = \App\Models\Arena::where('user_id', Auth::id())->first();

            if (!$arena) {
                return redirect('/admin/arena/nova')->with('error', 'Cadastre sua arena antes de criar uma reserva.');
            }

            return redirect('/agendamento/quadras?arena_id=' . $arena->id);
        }

        if (Auth::check() && Auth::user()->tipo_conta === 'funcionario') {
            $arena = Auth::user()->arena;

            if (!$arena) {
                return redirect('/recepcao')->with('error', 'Nenhuma arena vinculada à sua conta ainda.');
            }

            return redirect('/agendamento/quadras?arena_id=' . $arena->id);
        }

        $busca = request('busca');
        $arenas = \App\Models\Arena::withCount('quadras')
            ->when($busca, function ($query) use ($busca) {
                $query->where(function ($q) use ($busca) {
                    $q->where('nome', 'like', "%{$busca}%")
                      ->orWhere('endereco', 'like', "%{$busca}%");
                });
            })
            ->orderBy('nome')
            ->get();

        return view('agendamento.arenas', compact('arenas', 'busca'));
    });

    // 2. Escolher quadra dentro da arena
    Route::get('/quadras', function () {
        $arena = \App\Models\Arena::with(['quadras.esportes', 'quadras.precosTurno'])
            ->findOrFail(request('arena_id'));

        return view('agendamento.quadras', ['arena' => $arena, 'quadras' => $arena->quadras]);
    });

    // 3. Escolher data e horário
    Route::get('/data', function () {
        $quadra = \App\Models\Quadra::with(['arena.funcionamento', 'esportes', 'precosTurno'])
            ->findOrFail(request('quadra_id'));

        if ($quadra->esportes->where('ativo', true)->isEmpty() || $quadra->precosTurno->isEmpty()) {
            return redirect('/agendamento/quadras?arena_id=' . $quadra->arena_id)
                ->with('error', 'Esta quadra ainda não tem esportes ou preços configurados.');
        }

        return view('agendamento.data', compact('quadra'));
    });

    // Horários disponíveis para uma data específica (usado via AJAX ao trocar o dia no calendário)
    Route::get('/horarios-disponiveis', function () {
        $quadra = \App\Models\Quadra::findOrFail(request('quadra_id'));
        $data = request('data');

        if (!$data) {
            return response()->json(['aberto' => false, 'horarios' => []]);
        }

        $dataReserva = \Carbon\Carbon::parse($data);

        $funcionamento = \App\Models\ArenaFuncionamento::where('arena_id', $quadra->arena_id)
            ->where('dia_semana', $dataReserva->dayOfWeek)
            ->where('ativo', true)
            ->first();

        if (!$funcionamento) {
            return response()->json(['aberto' => false, 'horarios' => []]);
        }

        $abertura = (int) substr($funcionamento->hora_abertura, 0, 2);
        $fechamento = (int) substr($funcionamento->hora_fechamento, 0, 2);

        $ocupados = \App\Models\Reserva::where('quadra_id', $quadra->id)
            ->where('data_reserva', $dataReserva->format('Y-m-d'))
            ->where('status', '!=', 'cancelado')
            ->pluck('horario')
            ->flatMap(function ($horario) {
                return array_map('trim', explode('|', $horario));
            })
            ->toArray();

        $ehHoje = $dataReserva->isToday();
        $horaAtual = Carbon::now()->hour;

        $horarios = [];
        for ($h = $abertura; $h < $fechamento; $h++) {
            $hora = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            $horarios[] = [
                'hora' => $hora,
                'ocupado' => in_array($hora, $ocupados),
                'passado' => $ehHoje && $h <= $horaAtual,
            ];
        }

        return response()->json(['aberto' => true, 'horarios' => $horarios]);
    });
});

// ==========================================
// ROTAS PROTEGIDAS (Auth)
// ==========================================
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'sair']);

    // Perfil (acessível a todos os tipos de conta)
    Route::get('/perfil', function () {
        return view('perfil');
    });

    // FLUXO DE AGENDAMENTO — passo final (exige login, quem está reservando importa)
    Route::prefix('agendamento')->group(function () {
        // 4. Pagamento
        Route::get('/pagamento', function () { return view('agendamento.pagamento'); });

        Route::post('/finalizar', [ReservaController::class, 'salvar']);

        // Busca de clientes cadastrados (usado por admin/funcionário ao reservar em nome de um cliente)
        Route::get('/clientes-busca', function () {
            if (!in_array(Auth::user()->tipo_conta, ['admin', 'funcionario'])) {
                abort(403);
            }

            $termo = trim((string) request('q'));
            if (strlen($termo) < 2) {
                return response()->json([]);
            }

            $clientes = \App\Models\User::where('tipo_conta', 'cliente')
                ->where(function ($query) use ($termo) {
                    $query->where('name', 'like', "%{$termo}%")
                          ->orWhere('email', 'like', "%{$termo}%");
                })
                ->orderBy('name')
                ->limit(8)
                ->get(['id', 'name', 'email']);

            return response()->json($clientes);
        });
    });

    Route::post('/reservas/{id}/cancelar', [ReservaController::class, 'cancelar']);

    // 2. PAINEL DO CLIENTE
    Route::prefix('cliente')->group(function () {
        Route::get('/agendamentos', function () {
            if (Auth::user()->tipo_conta !== 'cliente') return redirect('/');
            $reservas = Reserva::with('quadra.arena')
                ->where('user_id', Auth::id())
                ->orderBy('data_reserva', 'desc')
                ->orderBy('horario', 'desc')
                ->get();
            return view('cliente.agendamentos', compact('reservas'));
        });
    });

    // 3. PAINEL DA RECEPÇÃO
    Route::prefix('recepcao')->group(function () {
        Route::get('/', function () {
            if (!in_array(Auth::user()->tipo_conta, ['admin', 'funcionario'])) return redirect('/');

            $arena = Auth::user()->tipo_conta === 'admin'
                ? Arena::where('user_id', Auth::id())->first()
                : Auth::user()->arena;

            $jogosHoje = $arena
                ? Reserva::with(['quadra', 'user'])
                    ->whereIn('quadra_id', $arena->quadras()->pluck('id'))
                    ->whereDate('data_reserva', Carbon::today())
                    ->orderBy('horario', 'asc')
                    ->get()
                : collect();

            return view('recepcao.painel', compact('jogosHoje'));
        });

        // Marca o pagamento como recebido — não mexe no status do horário (que é calculado por tempo).
        Route::post('/reservas/{id}/pagamento', function($id) {
            Reserva::findOrFail($id)->update(['pago' => true]);
            return back()->with('success', 'Pagamento confirmado!');
        });
    });

    // AGENDA — Admin (arena que possui) e Funcionário (arena vinculada). Fora do
    // prefixo /admin de propósito: não é uma tela de gestão exclusiva do admin,
    // então não deve carregar a sidebar admin-only (nem exigir tipo_conta=admin).
    Route::prefix('agenda')->group(function () {
        Route::get('/', [AgendaController::class, 'index']);
        Route::get('/dias-com-reservas', [AgendaController::class, 'diasComReservas']);
        Route::get('/reservas-do-dia', [AgendaController::class, 'reservasDoDia']);
    });

    // 4. PAINEL DO ADMINISTRADOR
    Route::prefix('admin')->group(function () {

        Route::get('/', function () { return redirect('/admin/dashboard'); });

        Route::get('/dashboard', function () {
            if (Auth::user()->tipo_conta !== 'admin') return redirect('/');
            $arena = Arena::where('user_id', Auth::id())->first();
            if (!$arena) return redirect('/admin/arena/nova');

            $quadras = $arena->quadras()->with('esportes')->get();
            $totalReservas = Reserva::whereIn('quadra_id', $quadras->pluck('id'))
                ->where('status', '!=', 'cancelado')
                ->count();

            // Status de funcionamento da arena agora, conforme os horários cadastrados
            $agora = Carbon::now();
            $funcionamentoHoje = $arena->funcionamento()
                ->where('dia_semana', $agora->dayOfWeek)
                ->where('ativo', true)
                ->first();

            $arenaAberta = false;
            $statusArenaMensagem = 'Não opera hoje';

            if ($funcionamentoHoje) {
                $horaAtual = $agora->format('H:i:s');

                if ($horaAtual < $funcionamentoHoje->hora_abertura) {
                    $statusArenaMensagem = 'Abre às ' . substr($funcionamentoHoje->hora_abertura, 0, 5);
                } elseif ($horaAtual > $funcionamentoHoje->hora_fechamento) {
                    $statusArenaMensagem = 'Fechou às ' . substr($funcionamentoHoje->hora_fechamento, 0, 5);
                } else {
                    $arenaAberta = true;
                    $statusArenaMensagem = 'Fecha às ' . substr($funcionamentoHoje->hora_fechamento, 0, 5);
                }
            }

            return view('admin.dashboard', compact('arena', 'quadras', 'totalReservas', 'arenaAberta', 'statusArenaMensagem'));
        });

        // Arena
        Route::get('/arena/nova', function () { return view('admin.arena.nova'); });
        Route::get('/arena/{id}/editar', [ArenaController::class, 'editar']);
        Route::post('/arena/salvar', [ArenaController::class, 'salvar']);
        Route::post('/arena/{id}/atualizar', [ArenaController::class, 'atualizar']);

        // Gestão de Quadras
        Route::get('/quadras', function () {
            $arena = Arena::where('user_id', Auth::id())->first();
            $quadras = $arena ? $arena->quadras()->with('esportes')->get() : collect();
            return view('admin.quadras', compact('quadras'));
        });
        Route::get('/quadras/nova', function () { return view('admin.quadras.nova'); });
        Route::post('/quadras/salvar', [QuadraController::class, 'salvar']);
        Route::get('/quadras/{id}/editar', [QuadraController::class, 'editar']);
        Route::post('/quadras/{id}/atualizar', [QuadraController::class, 'atualizar']);
        Route::post('/quadras/{id}/excluir', [QuadraController::class, 'excluir']);

        // Financeiro e Equipe
        Route::get('/financeiro', [FinanceiroController::class, 'index']);
        Route::get('/equipe', function () {
            $arena = Arena::where('user_id', Auth::id())->first();
            $funcionarios = $arena ? $arena->usuarios()->where('tipo_conta', 'funcionario')->get() : collect();
            return view('admin.equipe', compact('funcionarios'));
        });
        Route::post('/equipe/salvar', [EquipeController::class, 'salvar']);
        Route::post('/equipe/{id}/atualizar', [EquipeController::class, 'atualizar']);
        Route::post('/equipe/{id}/excluir', [EquipeController::class, 'excluir']);
    });
});