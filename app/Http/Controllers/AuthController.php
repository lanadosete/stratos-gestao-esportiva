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
        // Este formulário é exclusivo para clientes (jogadores) — proprietários se
        // cadastram pelo wizard dedicado em /cadastro/administrativo.
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'password' => Hash::make($request->password),
            'tipo_conta' => 'cliente',
        ]);

        // 3. Faz o login automático
        Auth::login($user);

        return redirect('/');
    }

    // Passo 1 do wizard de cadastro de proprietário: cria o usuário admin e
    // manda para o passo 2 (/admin/arena/nova), que cadastra a arena em si.
    public function registrarAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telefone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'password' => Hash::make($request->password),
            'tipo_conta' => 'admin',
        ]);

        Auth::login($user);

        return redirect('/admin/arena/nova');
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

    // Login administrativo — exclusivo para donos de arena (admin) e funcionários
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

        // Se o cliente foi barrado por uma rota protegida (ex.: clicou em "Ir para
        // Pagamento" sem estar logado), o Laravel já guardou a URL de destino —
        // volta pra lá com a quadra/data/horário/esporte escolhidos preservados.
        return redirect()->intended('/');
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