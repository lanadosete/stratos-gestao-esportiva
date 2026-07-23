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
            'telefone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        // 2. Cria o usuário no banco de dados com a senha segura
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telefone' => $request->telefone,
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

    // Login da área principal — exclusivo para clientes (jogadores)
    public function entrar(Request $request)
    {
        return $this->autenticar(
            $request,
            ['cliente'],
            'Esta área é exclusiva para jogadores. Proprietários e funcionários devem entrar pelo botão "Sou proprietário ou funcionário".'
        );
    }

    // Login administrativo — exclusivo para donos de complexo (admin) e funcionários
    public function entrarStaff(Request $request)
    {
        return $this->autenticar(
            $request,
            ['admin', 'funcionario'],
            'Esta área é exclusiva para proprietários e funcionários. Jogadores devem acessar pela área principal.'
        );
    }

    private function autenticar(Request $request, array $tiposPermitidos, string $mensagemAreaErrada)
    {
        $credenciais = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credenciais)) {
            return back()->withErrors(['email' => 'E-mail ou senha incorretos.']);
        }

        // Login válido, mas na área errada: desfaz a sessão e barra o acesso
        if (!in_array(Auth::user()->tipo_conta, $tiposPermitidos)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['email' => $mensagemAreaErrada]);
        }

        $request->session()->regenerate();

        // Direciona o dono para o painel e o funcionário para a recepção
        if (Auth::user()->tipo_conta === 'admin') {
            return redirect('/admin/dashboard');
        }
        if (Auth::user()->tipo_conta === 'funcionario') {
            return redirect('/recepcao');
        }

        $redirect = $request->input('redirect');
        if ($redirect && str_starts_with($redirect, '/')) {
            return redirect($redirect);
        }

        return redirect('/');
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