<?php

namespace App\Http\Controllers;

use App\Models\Arena;
use App\Models\Complexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArenaController extends Controller
{
    public function salvar(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo_esporte' => 'required|string|max:100',
            'preco_hora' => 'required|numeric',
        ]);

        // Busca o complexo do usuário logado
        $complexo = Complexo::where('user_id', Auth::id())->first();

        // Salva a arena vinculada a esse complexo
        Arena::create([
            'complexo_id' => $complexo->id,
            'nome' => $request->nome,
            'tipo_esporte' => $request->tipo_esporte,
            'preco_hora' => $request->preco_hora,
        ]);

        return redirect('/admin/dashboard')->with('success', 'Quadra adicionada com sucesso!');
    }
}