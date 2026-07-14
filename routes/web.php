<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArenaController;
use App\Http\Controllers\ComplexoController;
use App\Models\Complexo;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ==========================================
// 1. ROTAS PÚBLICAS (Apenas para Visitantes)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/', function () { return view('home'); });
    Route::get('/cadastro', function () { return view('auth.register'); });
    Route::get('/login', function () { return view('auth.login'); })->name('login');

    Route::post('/cadastro', [AuthController::class, 'registrar']);
    Route::post('/login', [AuthController::class, 'entrar']);
});

// ==========================================
// 2. ROTAS PROTEGIDAS (Exigem Login)
// ==========================================
Route::middleware('auth')->group(function () {

    // Rota de Logout
    Route::post('/logout', [AuthController::class, 'sair']);

    // ==========================================
    // A. FLUXO DE AGENDAMENTO (Acesso Geral)
    // ==========================================
    Route::prefix('agendamento')->group(function () {
        Route::get('/', function () { return view('agendamento.arenas'); });
        Route::get('/data', function () { return view('agendamento.data'); });
        Route::get('/pagamento', function () { return view('agendamento.pagamento'); });
        Route::post('/finalizar', [ReservaController::class, 'salvar']);
    });

    // ==========================================
    // B. PAINEL DO CLIENTE (Apenas Jogadores)
    // ==========================================
    Route::prefix('cliente')->group(function () {
        Route::get('/agendamentos', function () { 
            if (Auth::user()->tipo_conta !== 'cliente') return redirect('/');
            return view('cliente.agendamentos'); 
        });
        
        Route::get('/perfil', function () { 
            if (Auth::user()->tipo_conta !== 'cliente') return redirect('/');
            return view('cliente.perfil'); 
        }); 
    });

    // ==========================================
    // C. PAINEL DA RECEPÇÃO (Funcionários e Admins)
    // ==========================================
    Route::prefix('recepcao')->group(function () {
        Route::get('/', function () { 
            if (!in_array(Auth::user()->tipo_conta, ['admin', 'funcionario'])) {
                return redirect('/');
            }
            return view('recepcao.painel'); 
        });
    });

    // ==========================================
    // D. PAINEL DO ADMINISTRADOR (Dono do Espaço)
    // ==========================================
    Route::prefix('admin')->group(function () {
        
        Route::get('/', function () { return redirect('/admin/dashboard'); });
        
        // Dashboard com lógica de Onboarding
        Route::get('/dashboard', function () { 
            if (Auth::user()->tipo_conta !== 'admin') return redirect('/');
            
            $complexo = Complexo::where('user_id', Auth::id())->first();
            
            if (!$complexo) {
                return redirect('/admin/complexo/nova');
            }
            
            $arenas = $complexo->arenas;
            return view('admin.dashboard', compact('complexo', 'arenas')); 
        });

        // Gestão do Complexo
        Route::get('/complexo/nova', function () {
            if (Auth::user()->tipo_conta !== 'admin') return redirect('/');
            return view('admin.complexo.nova');
        });
        Route::post('/complexo/salvar', [ComplexoController::class, 'salvar']);

        // Gestão das Arenas
        Route::get('/arenas/nova', function () {
            if (Auth::user()->tipo_conta !== 'admin') return redirect('/');
            return view('admin.arenas.nova');
        });
        Route::post('/arenas/salvar', [ArenaController::class, 'salvar']);

        Route::get('/arenas', function () { 
            if (Auth::user()->tipo_conta !== 'admin') return redirect('/');
            return view('admin.arenas'); 
        });
        
        // Módulos Administrativos
        Route::get('/financeiro', function () { 
            if (Auth::user()->tipo_conta !== 'admin') return redirect('/');
            return view('admin.financeiro'); 
        });
        
        Route::get('/equipe', function () { 
            if (Auth::user()->tipo_conta !== 'admin') return redirect('/');
            return view('admin.equipe'); 
        });
    });
});