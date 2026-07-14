<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Função para salvar um novo usuário (Cadastro)
    public function registrar(Request $request)
    {
        // 1. Valida se os campos foram preenchidos corretamente
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // 2. Cria o usuário no banco de dados com a senha segura
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Se o botão foi marcado, vira admin. Se não, vira cliente.
            'tipo_conta' => $request->has('is_admin') ? 'admin' : 'cliente', 
        ]);

        // 3. Faz o login automático
        Auth::login($user);

        // 4. Direciona para o painel correto
        // Agora ele vai para o dashboard, que fará a verificação se o complexo já existe
        if ($user->tipo_conta === 'admin') {
            return redirect('/admin/dashboard'); 
        }
        
        return redirect('/');
    }

    // Função para fazer o Login
    public function entrar(Request $request)
    {
        $credenciais = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credenciais)) {
            $request->session()->regenerate();
            
            // Direciona o dono para o painel e o jogador para a home
            if (Auth::user()->tipo_conta === 'admin') {
                return redirect('/admin/dashboard');
            }
            if (Auth::user()->tipo_conta === 'funcionario') {
                return redirect('/recepcao');
            }
            
            return redirect('/');
        }

        return back()->withErrors(['email' => 'E-mail ou senha incorretos.']);
    }

    // Função para Sair (Logout)
    public function sair(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}