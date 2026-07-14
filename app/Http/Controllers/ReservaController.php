<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    public function salvar(Request $request)
    {
        // 1. Validação: garante que o cliente enviou tudo que precisamos
        $request->validate([
            'arena_id' => 'required|exists:arenas,id',
            'data_reserva' => 'required|date',
            'horario' => 'required|string',
            'valor_total' => 'required|numeric',
            'metodo_pagamento' => 'required|string',
        ]);

        // 2. Salva a reserva no banco de dados
        Reserva::create([
            'user_id' => Auth::id(),
            'arena_id' => $request->arena_id,
            'data_reserva' => $request->data_reserva,
            'horario' => $request->horario,
            'valor_total' => $request->valor_total,
            'metodo_pagamento' => $request->metodo_pagamento,
            'status' => 'confirmado', // Por padrão, já entra confirmada
        ]);

        // 3. Retorna sucesso
        return redirect('/cliente/agendamentos')->with('success', 'Reserva realizada com sucesso!');
    }
}