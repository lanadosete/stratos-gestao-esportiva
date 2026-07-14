<?php

namespace App\Http\Controllers;

use App\Models\Complexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplexoController extends Controller
{
    public function salvar(Request $request)
    {
        // 1. Validação dos dados (garante que não venha nada vazio)
        $request->validate([
            'nome' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
        ]);

        // 2. Criação do registro no banco de dados
        Complexo::create([
            'user_id' => Auth::id(), // Vincula automaticamente ao dono logado
            'nome' => $request->nome,
            'endereco' => $request->endereco,
            'telefone' => $request->telefone,
        ]);

        // 3. Redirecionamento de sucesso
        return redirect('/admin/dashboard')->with('success', 'Complexo registrado com sucesso!');
    }
}