@extends('layouts.app')

@section('conteudo')
@php
    // O Laravel faz os cálculos matemáticos para preencher os seus cartões do topo!
    $jogosAtivosHoje = $jogosHoje->where('status', '!=', 'cancelado');
    $totalReservas = $jogosAtivosHoje->count();
    $pagamentosPendentes = $jogosAtivosHoje->where('pago', false)->where('metodo_pagamento', 'local')->count();
    $pagamentosConfirmados = $jogosAtivosHoje->where('pago', true)->count();
@endphp

<div class="bg-gradient-stratos" style="min-height: 100vh;">
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
            @if(Auth::user()->telefone)
                <p class="text-muted small mb-0 mt-1"><i class="bi bi-whatsapp text-success me-1"></i> {{ Auth::user()->telefone }}</p>
            @endif
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
                        <small class="text-muted text-uppercase fw-bold">Pagamentos Confirmados</small>
                        <h3 class="mb-0 fw-bold text-dark mt-1">{{ $pagamentosConfirmados }}</h3>
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
                        <th style="width: 20%;">Cliente</th>
                        <th style="width: 20%;">Quadra / Esporte</th>
                        <th style="width: 12%;">Status</th>
                        <th style="width: 13%;">Pagamento</th>
                        <th class="text-end" style="width: 25%;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jogosHoje as $jogo)
                        @php $statusCalc = $jogo->status_calculado; @endphp
                        <!-- Jogos finalizados ou cancelados ficam mais apagados na lista -->
                        <tr class="{{ in_array($statusCalc, ['finalizado', 'cancelado']) ? 'opacity-50' : '' }}">
                            <td><h5 class="mb-0 fw-bold {{ $statusCalc === 'finalizado' ? 'text-muted' : 'text-dark' }}">{{ $jogo->horario }}</h5></td>
                            <td>
                                @php $nomeExibido = $jogo->reservado_para ?: ($jogo->user->name ?? 'Cliente'); @endphp
                                <div class="d-flex align-items-center">
                                    <!-- Pega a primeira letra do nome do cliente para o avatar -->
                                    <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex justify-content-center align-items-center me-2 fw-bold" style="width: 35px; height: 35px;">
                                        {{ strtoupper(substr($nomeExibido, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="fw-semibold d-block {{ $statusCalc === 'finalizado' ? 'text-muted' : 'text-dark' }}">{{ $nomeExibido }}</span>
                                        @if($jogo->reservado_para)
                                            <small class="text-muted">Reservado por {{ $jogo->user->name ?? 'funcionário' }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="d-block fw-bold {{ $statusCalc === 'finalizado' ? 'text-muted' : 'text-dark' }}">{{ $jogo->quadra->nome ?? 'Quadra' }}</span>
                                <small class="text-muted">{{ $jogo->esporte ?? 'Esporte' }}</small>
                            </td>

                            <!-- Status do horário (calculado por tempo, independente de pagamento) -->
                            <td>
                                @switch($statusCalc)
                                    @case('cancelado')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border-0 px-2 py-1">
                                            <i class="bi bi-x-circle me-1"></i> Cancelado
                                        </span>
                                        @break
                                    @case('a_iniciar')
                                        <span class="badge bg-amber-soft text-amber border-0 px-2 py-1">
                                            <i class="bi bi-hourglass-split me-1"></i> A Iniciar
                                        </span>
                                        @break
                                    @case('em_jogo')
                                        <span class="badge bg-success bg-opacity-10 text-success border-0 px-2 py-1">
                                            <i class="bi bi-controller me-1"></i> Em Jogo
                                        </span>
                                        @break
                                    @case('finalizado')
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border-0 px-2 py-1">
                                            <i class="bi bi-check2-all me-1"></i> Finalizado
                                        </span>
                                        @break
                                @endswitch
                            </td>

                            <!-- Pagamento: apenas indica se foi recebido, não interfere no status do horário -->
                            <td>
                                @if($jogo->pago)
                                    <span class="badge bg-success bg-opacity-10 text-success border-0 px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i> Pago
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border-0 px-2 py-1">
                                        <i class="bi bi-clock me-1"></i> R$ {{ number_format($jogo->valor_total, 2, ',', '.') }}
                                    </span>
                                @endif
                            </td>

                            <!-- Ações: contato via WhatsApp, confirmar pagamento (se pendente) e cancelar (admin/funcionário) -->
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end flex-wrap">
                                    @if($jogo->whatsapp_link)
                                        <a href="{{ $jogo->whatsapp_link }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">
                                            <i class="bi bi-whatsapp me-1"></i> Entrar em contato
                                        </a>
                                    @endif

                                    @if($statusCalc !== 'cancelado')
                                        @if(!$jogo->pago)
                                            <form action="/recepcao/reservas/{{ $jogo->id }}/pagamento" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-bold shadow-sm">
                                                    Confirmar Pagamento <i class="bi bi-cash-coin ms-1"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if(in_array($statusCalc, ['a_iniciar', 'em_jogo']))
                                            <form action="/reservas/{{ $jogo->id }}/cancelar" method="POST" class="m-0" onsubmit="return confirm('Tem certeza que deseja cancelar esta reserva?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold">
                                                    Cancelar
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
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
</div>
@endsection