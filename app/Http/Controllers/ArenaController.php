<?php

namespace App\Http\Controllers;

use App\Models\Arena;
use App\Models\ArenaEsporte;
use App\Models\ArenaPrecoTurno;
use App\Models\Complexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArenaController extends Controller
{
    // Método que você já tinha para criar
    public function salvar(Request $request)
    {
        $complexo = Complexo::where('user_id', Auth::id())->first();

        $request->validate([
            'nome' => 'required|string',
        ]);

        $arena = Arena::create([
            'complexo_id' => $complexo->id,
            'nome' => $request->nome,
            'tipo_esporte' => 'Multiuso',
            'preco_hora' => 0,
        ]);

        $nomes = $request->input('esportes', []);
        foreach ($nomes as $nome) {
            $esporte = ArenaEsporte::firstOrCreate([
                'arena_id' => $arena->id,
                'nome' => $nome,
            ], [
                'ativo' => true,
            ]);

            $precos = $request->input('precos.' . $nome, []);
            foreach ($precos as $turno => $valor) {
                if ($valor !== null && $valor !== '') {
                    ArenaPrecoTurno::create([
                        'arena_id' => $arena->id,
                        'esporte' => $nome,
                        'turno' => $turno,
                        'valor_hora' => $valor,
                    ]);
                }
            }
        }

        return redirect('/admin/arenas')->with('success', 'Quadra cadastrada com sucesso!');
    }

    // ==========================================
    // NOVOS MÉTODOS: Editar, Atualizar e Excluir
    // ==========================================

    public function editar($id)
    {
        $arena = Arena::findOrFail($id);
        return view('admin.arenas.editar', compact('arena'));
    }

    public function atualizar(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string',
            'tipo_esporte' => 'required|string',
            'preco_hora' => 'required|numeric',
        ]);

        $arena = Arena::findOrFail($id);
        
        $arena->update([
            'nome' => $request->nome,
            'tipo_esporte' => $request->tipo_esporte,
            'preco_hora' => $request->preco_hora,
        ]);

        return redirect('/admin/arenas')->with('success', 'Dados da quadra atualizados com sucesso!');
    }

    public function excluir($id)
    {
        $arena = Arena::findOrFail($id);
        $arena->delete();

        return back()->with('success', 'Quadra removida do sistema com sucesso!');
    }
}