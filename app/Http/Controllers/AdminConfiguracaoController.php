<?php

namespace App\Http\Controllers;

use App\Models\Arena;
use App\Models\ArenaEsporte;
use App\Models\ArenaFuncionamento;
use App\Models\ArenaPrecoTurno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminConfiguracaoController extends Controller
{
    public function configuracoes($arenaId)
    {
        $arena = Arena::with(['funcionamento', 'esportes', 'precosTurno'])->findOrFail($arenaId);

        return view('admin.configuracoes-arena', compact('arena'));
    }

    public function salvarFuncionamento(Request $request, $arenaId)
    {
        $request->validate([
            'dia_semana' => 'required|integer|min:0|max:6',
            'hora_abertura' => 'required',
            'hora_fechamento' => 'required',
        ]);

        ArenaFuncionamento::create([
            'arena_id' => $arenaId,
            'dia_semana' => $request->dia_semana,
            'hora_abertura' => $request->hora_abertura,
            'hora_fechamento' => $request->hora_fechamento,
            'ativo' => true,
        ]);

        return back()->with('success', 'Dia de funcionamento cadastrado com sucesso!');
    }

    public function salvarEsporte(Request $request, $arenaId)
    {
        $request->validate([
            'nomes' => 'required|array',
            'nomes.*' => 'in:Beach Vôlei,Beach Tênis,Futevôlei',
        ]);

        foreach ($request->input('nomes', []) as $nome) {
            ArenaEsporte::firstOrCreate([
                'arena_id' => $arenaId,
                'nome' => $nome,
            ], [
                'ativo' => true,
            ]);
        }

        return back()->with('success', 'Esportes marcados com sucesso!');
    }

    public function salvarPreco(Request $request, $arenaId)
    {
        $request->validate([
            'esporte' => 'required|string',
            'turno' => 'required|string',
            'valor_hora' => 'required|numeric|min:0',
        ]);

        ArenaPrecoTurno::create([
            'arena_id' => $arenaId,
            'esporte' => $request->esporte,
            'turno' => $request->turno,
            'valor_hora' => $request->valor_hora,
        ]);

        return back()->with('success', 'Preço por turno cadastrado com sucesso!');
    }
}
