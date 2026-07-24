<?php

namespace App\Http\Controllers;

use App\Models\Arena;
use App\Models\Quadra;
use App\Models\QuadraEsporte;
use App\Models\QuadraPrecoTurno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuadraController extends Controller
{
    // Método que você já tinha para criar
    public function salvar(Request $request)
    {
        $arena = Arena::where('user_id', Auth::id())->first();

        $request->validate([
            'nome' => 'required|string',
            'esportes' => 'required|array|min:1',
        ], [
            'esportes.required' => 'Selecione pelo menos um esporte.',
            'esportes.min' => 'Selecione pelo menos um esporte.',
        ]);

        $quadra = Quadra::create([
            'arena_id' => $arena->id,
            'nome' => $request->nome,
            'tipo_esporte' => 'Multiuso',
        ]);

        $nomes = $request->input('esportes', []);
        foreach ($nomes as $nome) {
            $esporte = QuadraEsporte::firstOrCreate([
                'quadra_id' => $quadra->id,
                'nome' => $nome,
            ], [
                'ativo' => true,
            ]);

            $precos = $request->input('precos.' . $nome, []);
            foreach ($precos as $turno => $valor) {
                if ($valor !== null && $valor !== '') {
                    QuadraPrecoTurno::create([
                        'quadra_id' => $quadra->id,
                        'esporte' => $nome,
                        'turno' => $turno,
                        'valor_hora' => $valor,
                    ]);
                }
            }
        }

        return redirect('/admin/quadras')->with('success', 'Quadra cadastrada com sucesso!');
    }

    // ==========================================
    // NOVOS MÉTODOS: Editar, Atualizar e Excluir
    // ==========================================

    public function editar($id)
    {
        $quadra = Quadra::with(['esportes', 'precosTurno'])->findOrFail($id);
        return view('admin.quadras.editar', compact('quadra'));
    }

    public function atualizar(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string',
            'esportes' => 'required|array|min:1',
        ], [
            'esportes.required' => 'Selecione pelo menos um esporte.',
            'esportes.min' => 'Selecione pelo menos um esporte.',
        ]);

        $quadra = Quadra::findOrFail($id);

        $quadra->update([
            'nome' => $request->nome,
        ]);

        $nomesSelecionados = $request->input('esportes', []);

        // Remove esportes desmarcados e seus preços por turno
        QuadraEsporte::where('quadra_id', $quadra->id)->whereNotIn('nome', $nomesSelecionados)->delete();
        QuadraPrecoTurno::where('quadra_id', $quadra->id)->whereNotIn('esporte', $nomesSelecionados)->delete();

        foreach ($nomesSelecionados as $nome) {
            QuadraEsporte::firstOrCreate([
                'quadra_id' => $quadra->id,
                'nome' => $nome,
            ], [
                'ativo' => true,
            ]);

            $precos = $request->input('precos.' . $nome, []);
            foreach ($precos as $turno => $valor) {
                if ($valor !== null && $valor !== '') {
                    QuadraPrecoTurno::updateOrCreate(
                        ['quadra_id' => $quadra->id, 'esporte' => $nome, 'turno' => $turno],
                        ['valor_hora' => $valor]
                    );
                } else {
                    QuadraPrecoTurno::where('quadra_id', $quadra->id)
                        ->where('esporte', $nome)
                        ->where('turno', $turno)
                        ->delete();
                }
            }
        }

        return redirect('/admin/quadras')->with('success', 'Dados da quadra atualizados com sucesso!');
    }

    public function excluir($id)
    {
        $quadra = Quadra::findOrFail($id);
        $quadra->delete();

        return back()->with('success', 'Quadra removida do sistema com sucesso!');
    }
}