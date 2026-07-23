<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    AuthController,
    ArenaController,
    ComplexoController,
    EquipeController,
    FinanceiroController,
    ReservaController
};
use App\Models\{Complexo, Reserva};
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
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::get('/login/administrativo', function () { return view('auth.login-admin'); });
    Route::post('/cadastro', [AuthController::class, 'registrar']);
    Route::post('/login', [AuthController::class, 'entrar']);
    Route::post('/login/administrativo', [AuthController::class, 'entrarStaff']);
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

    // 1. FLUXO DE AGENDAMENTO
    Route::prefix('agendamento')->group(function () {
        // 1. Buscar complexo
        Route::get('/', function () {
            $busca = request('busca');
            $complexos = \App\Models\Complexo::withCount('arenas')
                ->when($busca, function ($query) use ($busca) {
                    $query->where(function ($q) use ($busca) {
                        $q->where('nome', 'like', "%{$busca}%")
                          ->orWhere('endereco', 'like', "%{$busca}%");
                    });
                })
                ->orderBy('nome')
                ->get();

            return view('agendamento.complexos', compact('complexos', 'busca'));
        });

        // 2. Escolher arena dentro do complexo
        Route::get('/arenas', function () {
            $complexo = \App\Models\Complexo::with(['arenas.esportes', 'arenas.precosTurno'])
                ->findOrFail(request('complexo_id'));

            return view('agendamento.arenas', ['complexo' => $complexo, 'arenas' => $complexo->arenas]);
        });

        // 3. Escolher data e horário
        Route::get('/data', function () {
            $arena = \App\Models\Arena::with(['complexo.funcionamento', 'esportes', 'precosTurno'])
                ->findOrFail(request('arena_id'));

            if ($arena->esportes->where('ativo', true)->isEmpty() || $arena->precosTurno->isEmpty()) {
                return redirect('/agendamento/arenas?complexo_id=' . $arena->complexo_id)
                    ->with('error', 'Esta quadra ainda não tem esportes ou preços configurados.');
            }

            return view('agendamento.data', compact('arena'));
        });

        // Horários disponíveis para uma data específica (usado via AJAX ao trocar o dia no calendário)
        Route::get('/horarios-disponiveis', function () {
            $arena = \App\Models\Arena::findOrFail(request('arena_id'));
            $data = request('data');

            if (!$data) {
                return response()->json(['aberto' => false, 'horarios' => []]);
            }

            $dataReserva = \Carbon\Carbon::parse($data);

            $funcionamento = \App\Models\ComplexoFuncionamento::where('complexo_id', $arena->complexo_id)
                ->where('dia_semana', $dataReserva->dayOfWeek)
                ->where('ativo', true)
                ->first();

            if (!$funcionamento) {
                return response()->json(['aberto' => false, 'horarios' => []]);
            }

            $abertura = (int) substr($funcionamento->hora_abertura, 0, 2);
            $fechamento = (int) substr($funcionamento->hora_fechamento, 0, 2);

            $ocupados = \App\Models\Reserva::where('arena_id', $arena->id)
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
            $reservas = Reserva::with('arena.complexo')
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

            $jogosHoje = Reserva::with(['arena', 'user'])
                ->whereDate('data_reserva', Carbon::today())
                ->orderBy('horario', 'asc')
                ->get();

            return view('recepcao.painel', compact('jogosHoje'));
        });

        // Marca o pagamento como recebido — não mexe no status do horário (que é calculado por tempo).
        Route::post('/reservas/{id}/pagamento', function($id) {
            Reserva::findOrFail($id)->update(['pago' => true]);
            return back()->with('success', 'Pagamento confirmado!');
        });
    });

    // 4. PAINEL DO ADMINISTRADOR
    Route::prefix('admin')->group(function () {
        
        Route::get('/', function () { return redirect('/admin/dashboard'); });
        
        Route::get('/dashboard', function () { 
            if (Auth::user()->tipo_conta !== 'admin') return redirect('/');
            $complexo = Complexo::where('user_id', Auth::id())->first();
            if (!$complexo) return redirect('/admin/complexo/nova');

            $arenas = $complexo->arenas()->with('esportes')->get();
            $totalReservas = Reserva::whereIn('arena_id', $arenas->pluck('id'))
                ->where('status', '!=', 'cancelado')
                ->count();
            
            return view('admin.dashboard', compact('complexo', 'arenas', 'totalReservas')); 
        });

        // Complexo
        Route::get('/complexo/nova', function () { return view('admin.complexo.nova'); });
        Route::get('/complexo/{id}/editar', [ComplexoController::class, 'editar']);
        Route::post('/complexo/salvar', [ComplexoController::class, 'salvar']);
        Route::post('/complexo/{id}/atualizar', [ComplexoController::class, 'atualizar']);

        // Gestão de Arenas
        Route::get('/arenas', function () {
            $complexo = Complexo::where('user_id', Auth::id())->first();
            $arenas = $complexo ? $complexo->arenas()->with('esportes')->get() : collect();
            return view('admin.arenas', compact('arenas'));
        });
        Route::get('/arenas/nova', function () { return view('admin.arenas.nova'); });
        Route::post('/arenas/salvar', [ArenaController::class, 'salvar']);
        Route::get('/arenas/{id}/editar', [ArenaController::class, 'editar']);
        Route::post('/arenas/{id}/atualizar', [ArenaController::class, 'atualizar']);
        Route::post('/arenas/{id}/excluir', [ArenaController::class, 'excluir']);

        // Financeiro e Equipe
        Route::get('/financeiro', [FinanceiroController::class, 'index']);
        Route::get('/equipe', function () { return view('admin.equipe'); });
        Route::post('/equipe/salvar', [EquipeController::class, 'salvar']);
        Route::post('/equipe/{id}/atualizar', [EquipeController::class, 'atualizar']);
        Route::post('/equipe/{id}/excluir', [EquipeController::class, 'excluir']);
    });
});