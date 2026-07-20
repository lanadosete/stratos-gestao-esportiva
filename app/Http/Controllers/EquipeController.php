<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EquipeController extends Controller
{
    public function salvar(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telefone' => 'required|string|max:20',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'password' => Hash::make($request->password),
            'tipo_conta' => 'funcionario', // Define explicitamente como funcionário
        ]);

        return redirect('/admin/equipe')->with('success', 'Funcionário cadastrado com sucesso!');
    }

    public function atualizar(Request $request, $id)
    {
        $funcionario = User::where('tipo_conta', 'funcionario')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $funcionario->id,
            'telefone' => 'required|string|max:20',
            'password' => 'nullable|min:6',
        ]);

        $dados = [
            'name' => $request->name,
            'email' => $request->email,
            'telefone' => $request->telefone,
        ];

        if ($request->filled('password')) {
            $dados['password'] = Hash::make($request->password);
        }

        $funcionario->update($dados);

        return redirect('/admin/equipe')->with('success', 'Funcionário atualizado com sucesso!');
    }

    public function excluir($id)
    {
        $funcionario = User::where('tipo_conta', 'funcionario')->findOrFail($id);

        if (Reserva::where('user_id', $funcionario->id)->exists()) {
            return redirect('/admin/equipe')->with('error', 'Não é possível remover: este funcionário possui reservas vinculadas no sistema.');
        }

        $funcionario->delete();

        return redirect('/admin/equipe')->with('success', 'Funcionário removido com sucesso!');
    }
}