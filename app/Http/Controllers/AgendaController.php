<?php

namespace App\Http\Controllers;

use App\Models\Arena;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    // Admin vê a agenda da arena que possui; funcionário, a arena à qual está vinculado.
    private function arenaDoUsuario(): ?Arena
    {
        $usuario = Auth::user();

        return $usuario->tipo_conta === 'admin'
            ? Arena::where('user_id', $usuario->id)->first()
            : $usuario->arena;
    }

    public function index()
    {
        if (!in_array(Auth::user()->tipo_conta, ['admin', 'funcionario'])) {
            return redirect('/');
        }

        $arena = $this->arenaDoUsuario();
        $diasComReservas = $arena ? $this->diasDoMes($arena, Carbon::now()) : collect();

        return view('agenda', compact('diasComReservas'));
    }

    public function diasComReservas(Request $request)
    {
        abort_unless(in_array(Auth::user()->tipo_conta, ['admin', 'funcionario']), 403);

        $arena = $this->arenaDoUsuario();
        $mes = Carbon::parse($request->query('mes', Carbon::now()->format('Y-m')) . '-01');

        return response()->json([
            'dias' => $arena ? $this->diasDoMes($arena, $mes) : collect(),
        ]);
    }

    public function reservasDoDia(Request $request)
    {
        abort_unless(in_array(Auth::user()->tipo_conta, ['admin', 'funcionario']), 403);

        $arena = $this->arenaDoUsuario();
        $data = $request->query('data');

        if (!$arena || !$data) {
            return response()->json(['reservas' => []]);
        }

        $reservas = Reserva::with(['quadra', 'user'])
            ->whereIn('quadra_id', $arena->quadras()->pluck('id'))
            ->where('data_reserva', $data)
            ->orderBy('horario')
            ->get()
            ->map(function (Reserva $reserva) {
                return [
                    'horario' => $reserva->horario,
                    'quadra' => $reserva->quadra->nome ?? 'Quadra removida',
                    'esporte' => $reserva->esporte ?? 'Esporte',
                    'cliente' => $reserva->reservado_para ?: ($reserva->user->name ?? 'Cliente'),
                    'status_calculado' => $reserva->status_calculado,
                    'whatsapp' => $reserva->whatsapp_link,
                ];
            });

        return response()->json(['reservas' => $reservas]);
    }

    // Datas (Y-m-d) do mês informado em que a arena tem pelo menos uma reserva ativa
    private function diasDoMes(Arena $arena, Carbon $mesReferencia)
    {
        return Reserva::whereIn('quadra_id', $arena->quadras()->pluck('id'))
            ->whereMonth('data_reserva', $mesReferencia->month)
            ->whereYear('data_reserva', $mesReferencia->year)
            ->where('status', '!=', 'cancelado')
            ->pluck('data_reserva')
            ->map(fn ($data) => Carbon::parse($data)->format('Y-m-d'))
            ->unique()
            ->values();
    }
}
