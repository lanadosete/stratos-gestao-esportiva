<?php

namespace App\Http\Controllers;

use App\Models\ArenaPrecoTurno;
use App\Models\ComplexoFuncionamento;
use App\Models\Reserva;
use App\Models\User;
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

        // 1.1 Se quem está reservando é admin/funcionário, a reserva pode ser feita em nome de um cliente
        // já cadastrado (cliente_id) ou, na ausência de cadastro, anotada com o nome do cliente (reservado_para)
        $usuarioReserva = Auth::id();
        $reservadoPara = null;

        if (in_array(Auth::user()->tipo_conta, ['admin', 'funcionario'])) {
            if ($request->filled('cliente_id')) {
                $cliente = User::where('id', $request->cliente_id)->where('tipo_conta', 'cliente')->first();

                if (!$cliente) {
                    return back()->withErrors(['cliente_id' => 'Cliente selecionado inválido.']);
                }

                $usuarioReserva = $cliente->id;
            } else {
                $request->validate([
                    'reservado_para' => 'required|string|max:255',
                ]);

                $reservadoPara = $request->reservado_para;
            }
        }

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

        // Não permite reservar horário que já passou no dia de hoje
        if ($dataReserva->isToday()) {
            foreach ($horariosSelecionados as $hora) {
                if ((int) substr($hora, 0, 2) <= Carbon::now()->hour) {
                    return back()->withErrors(['horario' => "O horário {$hora} já passou."]);
                }
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
        // O horário é considerado confirmado assim que a reserva é criada (independente
        // do pagamento). Pix é debitado na hora, então já nasce marcado como pago;
        // pagamento local só é marcado como pago quando a recepção confirma o recebimento.
        $reserva = Reserva::create([
            'user_id' => $usuarioReserva,
            'reservado_para' => $reservadoPara,
            'arena_id' => $request->arena_id,
            'data_reserva' => $request->data_reserva,
            'horario' => implode(' | ', $horariosSelecionados),
            'valor_total' => $totalCalculado, // Valor real calculado no servidor
            'metodo_pagamento' => $request->metodo_pagamento,
            'status' => 'confirmado',
            'pago' => $request->metodo_pagamento === 'pix',
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
        $usuario = Auth::user();

        // Cliente só cancela a própria reserva; admin e funcionário podem cancelar qualquer uma.
        if ($usuario->tipo_conta === 'cliente' && $reserva->user_id !== $usuario->id) {
            abort(403);
        }

        $reserva->update(['status' => 'cancelado']);
        return back()->with('success', 'Reserva cancelada com sucesso!');
    }
}