<?php

namespace App\Http\Controllers;

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
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tipo_conta' => 'funcionario', // Define explicitamente como funcionário
        ]);

        return redirect('/admin/equipe')->with('success', 'Funcionário cadastrado com sucesso!');
    }
}