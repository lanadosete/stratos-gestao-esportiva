<?php

namespace App\Http\Controllers;

use App\Models\Complexo;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FinanceiroController extends Controller
{
    public function index()
    {
        // Trava de segurança
        if (Auth::user()->tipo_conta !== 'admin') {
            return redirect('/');
        }

        // 1. Busca o complexo do dono logado (A sua excelente lógica!)
        $complexo = Complexo::where('user_id', Auth::id())->first();

        // Se não tiver complexo, manda criar
        if (!$complexo) {
            return redirect('/admin/complexo/nova');
        }

        // 2. Pega os IDs de todas as quadras desse complexo
        $arenaIds = $complexo->arenas()->pluck('id');

        // Preparando as datas do mês atual para a nova tela
        $mesAtual = Carbon::now()->month;
        $anoAtual = Carbon::now()->year;
        $nomeMes = Carbon::now()->translatedFormat('F \d\e Y');

        // 3. Busca as reservas APENAS deste mês e destas quadras
        $reservasMes = Reserva::with(['user', 'arena'])
            ->whereIn('arena_id', $arenaIds)
            ->whereMonth('data_reserva', $mesAtual)
            ->whereYear('data_reserva', $anoAtual)
            ->where('status', '!=', 'cancelado')
            ->orderBy('data_reserva', 'desc')
            ->orderBy('horario', 'desc')
            ->get();

        // 4. Matemática do Faturamento (Pix, Local Recebido, Local Pendente)
        $faturamentoTotal = $reservasMes->sum('valor_total');
        
        $recebidoPix = $reservasMes->where('metodo_pagamento', 'pix')->sum('valor_total');
        
        $recebidoLocal = $reservasMes->where('metodo_pagamento', 'local')
                                     ->where('status', 'finalizado')
                                     ->sum('valor_total');
        
        $pendenteLocal = $reservasMes->where('metodo_pagamento', 'local')
                                     ->where('status', 'confirmado')
                                     ->sum('valor_total');

        // 5. Envia tudo mastigado para a tela nova
        return view('admin.financeiro', compact(
            'reservasMes', 
            'faturamentoTotal', 
            'recebidoPix', 
            'recebidoLocal', 
            'pendenteLocal',
            'nomeMes'
        ));
    }
}