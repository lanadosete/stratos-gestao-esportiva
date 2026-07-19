@extends('layouts.app')

@section('conteudo')
@php
    // O Laravel faz os cálculos matemáticos para preencher os seus cartões do topo!
    $totalReservas = $jogosHoje->count();
    $pagamentosPendentes = $jogosHoje->where('status', 'confirmado')->where('metodo_pagamento', 'local')->count();
    $checkinsRealizados = $jogosHoje->where('status', 'finalizado')->count();
@endphp

<div class="container py-5">
    
    <!-- Alerta de Sucesso ao dar Baixa -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Cabeçalho da Recepção -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border-0 fw-semibold mb-2">
                <i class="bi bi-pc-display-horizontal me-1"></i> Frente de Caixa
            </span>
            <h3 class="fw-bold mb-1 text-dark">Olá, {{ explode(' ', Auth::user()->name)[0] }}!</h3>
            <p class="text-muted mb-0">Gerencie as entradas e pagamentos do dia de hoje.</p>
        </div>
        
        <div class="mt-3 mt-md-0 text-md-end">
            <h4 class="text-success fw-bold mb-0">Hoje</h4>
            <span class="text-muted small text-uppercase fw-semibold">
                {{ \Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y') }}
            </span>
        </div>
    </div>

    <!-- Indicadores Rápidos (Agora com Matemática Dinâmica) -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card card-stratos p-3 border-0 shadow-sm border-start border-success border-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase fw-bold">Total de Reservas (Hoje)</small>
                        <h3 class="mb-0 fw-bold text-dark mt-1">{{ $totalReservas }}</h3>
                    </div>
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-calendar-check fs-4 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stratos p-3 border-0 shadow-sm border-start border-warning border-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase fw-bold">Pagamentos Pendentes</small>
                        <h3 class="mb-0 fw-bold text-dark mt-1">{{ $pagamentosPendentes }}</h3>
                    </div>
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-cash-coin fs-4 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stratos p-3 border-0 shadow-sm border-start border-primary border-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase fw-bold">Check-ins Realizados</small>
                        <h3 class="mb-0 fw-bold text-dark mt-1">{{ $checkinsRealizados }}</h3>
                    </div>
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-person-check fs-4 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Controle -->
    <div class="card card-stratos p-4 border-0 shadow-sm rounded-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Agenda do Dia</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="text-muted small text-uppercase">
                    <tr>
                        <th style="width: 10%;">Horário</th>
                        <th style="width: 25%;">Cliente</th>
                        <th style="width: 25%;">Quadra / Esporte</th>
                        <th style="width: 15%;">Pagamento</th>
                        <th class="text-end" style="width: 25%;">Ações (Check-in)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jogosHoje as $jogo)
                        <!-- Se já fez check-in, a linha inteira fica mais apagada (opacity-50) -->
                        <tr class="{{ $jogo->status === 'finalizado' ? 'opacity-50' : '' }}">
                            <td><h5 class="mb-0 fw-bold {{ $jogo->status === 'finalizado' ? 'text-muted' : 'text-dark' }}">{{ $jogo->horario }}</h5></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <!-- Pega a primeira letra do nome do cliente para o avatar -->
                                    <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex justify-content-center align-items-center me-2 fw-bold" style="width: 35px; height: 35px;">
                                        {{ strtoupper(substr($jogo->user->name ?? 'C', 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold {{ $jogo->status === 'finalizado' ? 'text-muted' : 'text-dark' }}">{{ $jogo->user->name ?? 'Cliente' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="d-block fw-bold {{ $jogo->status === 'finalizado' ? 'text-muted' : 'text-dark' }}">{{ $jogo->arena->nome ?? 'Quadra' }}</span>
                                <small class="text-muted">{{ $jogo->arena->tipo_esporte ?? 'Esporte' }}</small>
                            </td>
                            
                            <!-- Regras de Exibição do Pagamento -->
                            <td>
                                @if($jogo->status === 'finalizado')
                                    <span class="badge bg-success bg-opacity-10 text-success border-0 px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i> Pago
                                    </span>
                                @elseif($jogo->metodo_pagamento === 'pix')
                                    <span class="badge bg-success bg-opacity-10 text-success border-0 px-2 py-1">
                                        <i class="bi bi-lightning-charge-fill me-1"></i> Pix Pago
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border-0 px-2 py-1">
                                        <i class="bi bi-clock me-1"></i> R$ {{ number_format($jogo->valor_total, 2, ',', '.') }}
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Regras do Botão de Ação -->
                            <td class="text-end">
                                @if($jogo->status === 'finalizado')
                                    <span class="text-muted fw-bold small"><i class="bi bi-check2-all me-1"></i> Em Jogo</span>
                                @else
                                    <form action="/recepcao/reservas/{{ $jogo->id }}/finalizar" method="POST" class="m-0">
                                        @csrf
                                        @if($jogo->metodo_pagamento === 'pix')
                                            <button type="submit" class="btn btn-sm btn-verde rounded-pill px-3 fw-bold shadow-sm">
                                                Liberar Entrada <i class="bi bi-door-open ms-1"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-bold shadow-sm">
                                                Receber e Liberar <i class="bi bi-cash-coin ms-1"></i>
                                            </button>
                                        @endif
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x display-4 d-block mb-3 opacity-25"></i>
                                <h5 class="fw-bold">Nenhuma reserva para hoje</h5>
                                <p class="mb-0">A agenda do dia está livre no momento.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
</div>
@endsection