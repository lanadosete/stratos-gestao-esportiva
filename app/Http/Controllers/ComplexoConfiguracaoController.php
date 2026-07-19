<?php

namespace App\Http\Controllers;

use App\Models\Complexo;
use App\Models\ComplexoFuncionamento;
use Illuminate\Http\Request;

class ComplexoConfiguracaoController extends Controller
{
    public function salvarFuncionamento(Request $request, $complexoId)
    {
        $request->validate([
            'dia_semana' => 'required|integer|min:0|max:6',
            'hora_abertura' => 'required',
            'hora_fechamento' => 'required',
        ]);

        ComplexoFuncionamento::create([
            'complexo_id' => $complexoId,
            'dia_semana' => $request->dia_semana,
            'hora_abertura' => $request->hora_abertura,
            'hora_fechamento' => $request->hora_fechamento,
            'ativo' => true,
        ]);

        return back()->with('success', 'Dias de funcionamento salvos com sucesso!');
    }
}
