@extends('layouts.admin')

@section('admin-content')
<div class="row mb-4 align-items-center">
        <div class="col">
            <h3 class="fw-bold mb-0">Painel Financeiro</h3>
            <p class="text-muted mb-0">Visão geral do faturamento das suas quadras em <strong class="text-capitalize">{{ $nomeMes }}</strong>.</p>
        </div>
    </div>

    <!-- 4 Cartões de Indicadores (Faturamento, Pix, Recebido Local, Pendente Local) -->
    <div class="d-flex flex-wrap gap-4 mb-5">

        <!-- 1. Faturamento Total -->
        <div style="flex: 1 1 200px; min-width: 200px; max-width: 100%;">
            <div class="card card-stratos border-0 shadow-sm p-4 h-100 rounded-4 bg-dark text-white">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="fw-semibold mb-0 text-white-50">Faturamento Total</h6>
                    <div class="bg-white bg-opacity-25 rounded p-2">
                        <i class="bi bi-wallet2 text-white"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-0">R$ {{ number_format($faturamentoTotal, 2, ',', '.') }}</h2>
            </div>
        </div>

        <!-- 2. Garantido no Pix -->
        <div style="flex: 1 1 200px; min-width: 200px; max-width: 100%;">
            <div class="card card-stratos border-0 shadow-sm p-4 h-100 rounded-4 border-start border-success border-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="fw-semibold mb-0 text-muted">Garantido no Pix</h6>
                    <div class="bg-success bg-opacity-10 rounded p-2">
                        <i class="bi bi-lightning-charge-fill text-success"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-success mb-0">R$ {{ number_format($recebidoPix, 2, ',', '.') }}</h2>
                <small class="text-muted mt-2 d-block">Já está na sua conta</small>
            </div>
        </div>

        <!-- 3. Recebido no Balcão (NOVO) -->
        <div style="flex: 1 1 200px; min-width: 200px; max-width: 100%;">
            <div class="card card-stratos border-0 shadow-sm p-4 h-100 rounded-4 border-start border-primary border-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="fw-semibold mb-0 text-muted">Recebido no Local</h6>
                    <div class="bg-primary bg-opacity-10 rounded p-2">
                        <i class="bi bi-cash-stack text-primary"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-primary mb-0">R$ {{ number_format($recebidoLocal, 2, ',', '.') }}</h2>
                <small class="text-muted mt-2 d-block">Entrou no caixa hoje</small>
            </div>
        </div>

        <!-- 4. A Receber no Local -->
        <div style="flex: 1 1 200px; min-width: 200px; max-width: 100%;">
            <div class="card card-stratos border-0 shadow-sm p-4 h-100 rounded-4 border-start border-warning border-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="fw-semibold mb-0 text-muted">A Receber no Local</h6>
                    <div class="bg-warning bg-opacity-10 rounded p-2">
                        <i class="bi bi-shop text-warning"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-warning mb-0">R$ {{ number_format($pendenteLocal, 2, ',', '.') }}</h2>
                <small class="text-muted mt-2 d-block">Cobrar na recepção</small>
            </div>
        </div>

    </div>

    <h5 class="fw-bold mb-3">Histórico de Reservas</h5>
    <div class="card card-stratos p-0 border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Data e Hora</th>
                        <th class="py-3">Quadra</th>
                        <th class="py-3">Valor</th>
                        <th class="py-3">Pagamento</th>
                        <th class="py-3 pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasMes as $reserva)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-semibold d-block">{{ \Carbon\Carbon::parse($reserva->data_reserva)->format('d/m/Y') }}</span>
                                <small class="text-muted">{{ $reserva->horario }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $reserva->arena->nome ?? 'Quadra Removida' }}</span>
                            </td>
                            <td class="fw-bold text-dark">
                                R$ {{ number_format($reserva->valor_total, 2, ',', '.') }}
                            </td>
                            <td>
                                @if($reserva->metodo_pagamento === 'pix')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                        <i class="bi bi-lightning-charge-fill me-1"></i> Pix
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1">
                                        <i class="bi bi-shop me-1"></i> Local
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4">
                                @php $statusCalc = $reserva->status_calculado; @endphp
                                <span class="badge rounded-pill px-3 {{ $statusCalc === 'finalizado' ? 'bg-primary' : ($statusCalc === 'em_jogo' ? 'bg-success' : 'bg-secondary') }}">
                                    {{ $reserva->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-3 opacity-50"></i>
                                Nenhuma reserva realizada ainda neste mês.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection