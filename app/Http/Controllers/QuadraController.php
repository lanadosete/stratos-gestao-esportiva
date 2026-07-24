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
        $arena = Arena::with(['esportes', 'precosTurno'])->findOrFail($id);
        return view('admin.arenas.editar', compact('arena'));
    }

    public function atualizar(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string',
        ]);

        $arena = Arena::findOrFail($id);

        $arena->update([
            'nome' => $request->nome,
        ]);

        $nomesSelecionados = $request->input('esportes', []);

        // Remove esportes desmarcados e seus preços por turno
        ArenaEsporte::where('arena_id', $arena->id)->whereNotIn('nome', $nomesSelecionados)->delete();
        ArenaPrecoTurno::where('arena_id', $arena->id)->whereNotIn('esporte', $nomesSelecionados)->delete();

        foreach ($nomesSelecionados as $nome) {
            ArenaEsporte::firstOrCreate([
                'arena_id' => $arena->id,
                'nome' => $nome,
            ], [
                'ativo' => true,
            ]);

            $precos = $request->input('precos.' . $nome, []);
            foreach ($precos as $turno => $valor) {
                if ($valor !== null && $valor !== '') {
                    ArenaPrecoTurno::updateOrCreate(
                        ['arena_id' => $arena->id, 'esporte' => $nome, 'turno' => $turno],
                        ['valor_hora' => $valor]
                    );
                } else {
                    ArenaPrecoTurno::where('arena_id', $arena->id)
                        ->where('esporte', $nome)
                        ->where('turno', $turno)
                        ->delete();
                }
            }
        }

        return redirect('/admin/arenas')->with('success', 'Dados da quadra atualizados com sucesso!');
    }

    public function excluir($id)
    {
        $arena = Arena::findOrFail($id);
        $arena->delete();

        return back()->with('success', 'Quadra removida do sistema com sucesso!');
    }
}