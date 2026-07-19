<?php

namespace App\Http\Controllers;

use App\Models\GradeHorario;
use App\Models\Arena;
use Illuminate\Http\Request;

class GradeHorarioController extends Controller
{
    // Esta é a função que estava faltando!
    public function configurar($id)
    {
        $arena = Arena::findOrFail($id);
        return view('admin.configurar-grade', compact('arena'));
    }

    public function salvar(Request $request)
    {
        $request->validate([
            'arena_id' => 'required|exists:arenas,id',
            'dia_semana' => 'required|integer|between:0,6',
            'horario' => 'required',
            'esporte' => 'required|string',
            'preco' => 'required|numeric',
        ]);

        GradeHorario::create([
            'arena_id' => $request->arena_id,
            'dia_semana' => $request->dia_semana,
            'horario' => $request->horario,
            'esporte' => $request->esporte,
            'preco' => $request->preco,
            'ativo' => true,
        ]);

        return back()->with('success', 'Horário e preço configurados com sucesso!');
    }

    public function excluir($id)
    {
        $grade = GradeHorario::findOrFail($id);
        $grade->delete();

        return back()->with('success', 'Horário removido da grade.');
    }
}
