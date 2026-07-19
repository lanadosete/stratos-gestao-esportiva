<?php

namespace App\Http\Controllers;

use App\Models\ArenaPrecoTurno;
use App\Models\ComplexoFuncionamento;
use App\Models\Reserva;
use App\Models\GradeHorario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservaController extends Controller
{
    public function salvar(Request $request)
    {
        // 1. Validação inicial
        $request->validate([
            'arena_id' => 'required|exists:arenas,id',
            'data_reserva' => 'required|date',
            'horarios' => 'required|array', // Agora recebemos um array de horários
            'esporte' => 'required|string', // Importante para definir o preço
            'metodo_pagamento' => 'required|string',
        ]);

        $dataReserva = Carbon::parse($request->data_reserva);
        $totalCalculado = 0;
        $horariosSelecionados = $request->horarios;

        $arena = \App\Models\Arena::findOrFail($request->arena_id);
        $funcionamento = ComplexoFuncionamento::where('complexo_id', $arena->complexo_id)
            ->where('dia_semana', $dataReserva->dayOfWeek)
            ->where('ativo', true)
            ->first();

        if (!$funcionamento) {
            return back()->withErrors(['data_reserva' => 'O complexo não opera neste dia da semana.']);
        }

        foreach ($horariosSelecionados as $hora) {
            $horaComparacao = strtotime($hora);
            $abertura = strtotime($funcionamento->hora_abertura);
            $fechamento = strtotime($funcionamento->hora_fechamento);

            if ($horaComparacao < $abertura || $horaComparacao > $fechamento) {
                return back()->withErrors(['horario' => "O horário {$hora} está fora do funcionamento do complexo."]);
            }
        }

        // 2. Loop para validar conflitos e CALCULAR O PREÇO NO SERVIDOR
        foreach ($horariosSelecionados as $hora) {
            
$turno = $this->detectarTurno($hora);

            $regraPreco = ArenaPrecoTurno::where('arena_id', $request->arena_id)
                ->where('esporte', $request->esporte)
                ->where('turno', $turno)
                ->first();

            if (!$regraPreco) {
                return back()->withErrors(['horario' => "Preço não configurado para o esporte {$request->esporte} no turno {$turno}."]);
            }

            $totalCalculado += $regraPreco->valor_hora;

            // Verifica conflito de reserva existente
            $conflito = Reserva::where('arena_id', $request->arena_id)
                ->where('data_reserva', $request->data_reserva)
                ->where('horario', 'LIKE', '%' . $hora . '%') // Verifica se o horário já está ocupado
                ->where('status', '!=', 'cancelado') 
                ->exists();

            if ($conflito) {
                return back()->withErrors(['horario' => "O horário $hora já foi reservado."]);
            }
        }

        // 3. Salvando a reserva
        $reserva = Reserva::create([
            'user_id' => Auth::id(),
            'arena_id' => $request->arena_id,
            'data_reserva' => $request->data_reserva,
            'horario' => implode(' | ', $horariosSelecionados),
            'valor_total' => $totalCalculado, // Valor real calculado no servidor
            'metodo_pagamento' => $request->metodo_pagamento,
            'status' => 'confirmado',
        ]);

        return redirect('/agendamento/pagamento?' . http_build_query([
            'arena_id' => $request->arena_id,
            'data' => $request->data_reserva,
            'horario' => implode(' | ', $horariosSelecionados),
            'esporte' => $request->esporte,
        ]))->with('reserva_confirmada', $reserva->id);
    }

    private function detectarTurno(string $hora): string
    {
        $horaNumerica = (int) substr($hora, 0, 2);

        if ($horaNumerica < 12) {
            return 'Manhã';
        }

        if ($horaNumerica < 18) {
            return 'Tarde';
        }

        return 'Noite';
    }

    public function cancelar($id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->update(['status' => 'cancelado']);
        return back()->with('success', 'Reserva cancelada com sucesso!');
    }
}