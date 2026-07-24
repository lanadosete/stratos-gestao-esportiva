<?php

namespace App\Http\Controllers;

use App\Models\Complexo;
use App\Models\ComplexoFuncionamento;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ComplexoController extends Controller
{
    private function normalizeTime(?string $time): ?string
    {
        if (empty($time)) {
            return null;
        }

        return date('H:i:s', strtotime($time));
    }

    private function ensureComplexoFuncionamentoTable(): void
    {
        if (!Schema::hasTable('complexo_funcionamentos')) {
            Schema::create('complexo_funcionamentos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('complexo_id')->constrained('complexos')->onDelete('cascade');
                $table->integer('dia_semana');
                $table->time('hora_abertura')->nullable();
                $table->time('hora_fechamento')->nullable();
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }
    }

    public function salvar(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'telefone' => ['required', 'string', 'max:20', 'regex:/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/'],
            'dias_semana' => 'required|array|min:1',
            'hora_abertura' => 'required',
            'hora_fechamento' => 'required',
        ], [
            'nome.required' => 'O nome do complexo é obrigatório.',
            'nome.max' => 'O nome do complexo deve ter no máximo 255 caracteres.',
            'endereco.required' => 'O endereço é obrigatório.',
            'endereco.max' => 'O endereço deve ter no máximo 255 caracteres.',
            'telefone.required' => 'O telefone é obrigatório.',
            'telefone.max' => 'O telefone deve ter no máximo 20 caracteres.',
            'telefone.regex' => 'O telefone deve estar no formato (00) 00000-0000 ou 0000-0000.',
            'dias_semana.required' => 'Selecione pelo menos um dia da semana.',
            'dias_semana.min' => 'Selecione pelo menos um dia da semana.',
            'hora_abertura.required' => 'Informe o horário de início de expediente.',
            'hora_fechamento.required' => 'Informe o horário de fim de expediente.',
        ]);

        $complexo = Complexo::create([
            'user_id' => Auth::id(),
            'nome' => $request->nome,
            'endereco' => $request->endereco,
            'telefone' => $request->telefone,
        ]);

        $this->ensureComplexoFuncionamentoTable();

        if ($request->filled('hora_abertura') && $request->filled('hora_fechamento')) {
            $dias = $request->input('dias_semana', []);

            if (empty($dias) && $request->filled('dia_semana')) {
                $dias = [$request->dia_semana];
            }

            foreach ($dias as $dia) {
                ComplexoFuncionamento::create([
                    'complexo_id' => $complexo->id,
                    'dia_semana' => $dia,
                    'hora_abertura' => $this->normalizeTime($request->hora_abertura),
                    'hora_fechamento' => $this->normalizeTime($request->hora_fechamento),
                    'ativo' => true,
                ]);
            }
        }

        return redirect('/admin/dashboard')->with('success', 'Complexo registrado com sucesso!');
    }

    public function editar($id)
    {
        $this->ensureComplexoFuncionamentoTable();
        $complexo = Complexo::where('user_id', Auth::id())->findOrFail($id);

        return view('admin.complexo.editar', compact('complexo'));
    }

    public function atualizar(Request $request, $id)
    {
        $complexo = Complexo::where('user_id', Auth::id())->findOrFail($id);
        $this->ensureComplexoFuncionamentoTable();

        $request->validate([
            'nome' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'telefone' => ['required', 'string', 'max:20', 'regex:/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/'],
            'dias_semana' => 'required|array|min:1',
            'hora_abertura' => 'required',
            'hora_fechamento' => 'required',
        ], [
            'nome.required' => 'O nome do complexo é obrigatório.',
            'nome.max' => 'O nome do complexo deve ter no máximo 255 caracteres.',
            'endereco.required' => 'O endereço é obrigatório.',
            'endereco.max' => 'O endereço deve ter no máximo 255 caracteres.',
            'telefone.required' => 'O telefone é obrigatório.',
            'telefone.max' => 'O telefone deve ter no máximo 20 caracteres.',
            'telefone.regex' => 'O telefone deve estar no formato (00) 00000-0000 ou 0000-0000.',
            'dias_semana.required' => 'Selecione pelo menos um dia da semana.',
            'dias_semana.min' => 'Selecione pelo menos um dia da semana.',
            'hora_abertura.required' => 'Informe o horário de início de expediente.',
            'hora_fechamento.required' => 'Informe o horário de fim de expediente.',
        ]);

        $complexo->update([
            'nome' => $request->nome,
            'endereco' => $request->endereco,
            'telefone' => $request->telefone,
        ]);

        if ($request->filled('hora_abertura') && $request->filled('hora_fechamento')) {
            $dias = $request->input('dias_semana', []);

            if (empty($dias) && $request->filled('dia_semana')) {
                $dias = [$request->dia_semana];
            }

            $complexo->funcionamento()->where('ativo', true)->delete();

            foreach ($dias as $dia) {
                ComplexoFuncionamento::create([
                    'complexo_id' => $complexo->id,
                    'dia_semana' => $dia,
                    'hora_abertura' => $this->normalizeTime($request->hora_abertura),
                    'hora_fechamento' => $this->normalizeTime($request->hora_fechamento),
                    'ativo' => true,
                ]);
            }
        }

        return redirect('/admin/dashboard')->with('success', 'Complexo atualizado com sucesso!');
    }
}