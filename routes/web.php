<?php

use Illuminate\Support\Facades\Route;

// ==========================================
// ROTAS PÚBLICAS E AUTENTICAÇÃO
// ==========================================
Route::get('/', function () {
    return view('home');
});

Route::get('/cadastro', function () {
    return view('auth.register');
});

Route::get('/login', function () {
    return view('auth.login');
});

// ==========================================
// FLUXO DE AGENDAMENTO (WIZARD)
// ==========================================
Route::prefix('agendamento')->group(function () {
    Route::get('/', function () { return view('agendamento.arenas'); });
    Route::get('/data', function () { return view('agendamento.data'); });
    Route::get('/pagamento', function () { return view('agendamento.pagamento'); });
});

// ==========================================
// PAINEL DO ADMINISTRADOR (Admin = Dono do Complexo)
// ==========================================
Route::prefix('admin')->group(function () {
    Route::get('/', function () { return redirect('/admin/dashboard'); });
    Route::get('/dashboard', function () { return view('admin.dashboard'); });
    Route::get('/arenas', function () { return view('admin.arenas'); });
    Route::get('/financeiro', function () { return view('admin.financeiro'); });
    Route::get('/equipe', function () { return view('admin.equipe'); }); // Nova tela que definimos
});

// ==========================================
// PAINEL DA RECEPÇÃO (Operação Diária - Balcão)
// ==========================================
Route::prefix('recepcao')->group(function () {
    Route::get('/', function () { return view('recepcao.painel'); });
});

// ==========================================
// PAINEL DO CLIENTE (JOGADOR)
// ==========================================
Route::prefix('cliente')->group(function () {
    Route::get('/agendamentos', function () { return view('cliente.agendamentos'); });
    Route::get('/perfil', function () { return view('cliente.perfil'); }); // Nova tela que definimos
});