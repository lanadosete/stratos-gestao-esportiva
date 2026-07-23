@extends('layouts.app')

@section('conteudo')
@php
    $hoje = \Carbon\Carbon::today();
    
    $proximosJogos = $reservas->filter(function($r) use ($hoje) {
        return \Carbon\Carbon::parse($r->data_reserva)->gte($hoje);
    });
    
    $historico = $reservas->filter(function($r) use ($hoje) {
        return \Carbon\Carbon::parse($r->data_reserva)->lt($hoje);
    });
@endphp

<div class="bg-gradient-stratos" style="min-height: 100vh;">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 border-bottom border-success border-opacity-25 pb-4">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">Fala, {{ explode(' ', Auth::user()->name)[0] }}! 🎾</h3>
                    <p class="text-muted mb-0">Gerencie suas próximas partidas e veja seu histórico.</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="/agendamento" class="btn btn-verde px-4 py-2 fw-bold rounded-pill shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Nova Reserva
                    </a>
                </div>
            </div>

            <h5 class="fw-bold mb-3 text-success"><i class="bi bi-calendar-event me-2"></i> Próximos Jogos</h5>
            
            <div class="card card-stratos p-0 border-0 shadow-sm mb-5 rounded-3 overflow-hidden">
                <div class="list-group list-group-flush">
                    @forelse($proximosJogos as $reserva)
                        <div class="list-group-item p-4 border-bottom border-light">
                            <div class="row align-items-center">
                                @php $statusCalc = $reserva->status_calculado; @endphp
                                <div class="col-md-2 text-center border-end border-light mb-3 mb-md-0">
                                    <h2 class="fw-bold @if($statusCalc === 'cancelado') text-danger @else text-success @endif mb-0">
                                        {{ \Carbon\Carbon::parse($reserva->data_reserva)->format('d') }}
                                    </h2>
                                    <span class="text-muted text-uppercase small fw-bold">
                                        {{ \Carbon\Carbon::parse($reserva->data_reserva)->translatedFormat('M Y') }}
                                    </span>
                                </div>
                                
                                <div class="col-md-7 ps-md-4 mb-3 mb-md-0">
                                    <h5 class="fw-bold mb-1 text-dark">{{ $reserva->arena->nome ?? 'Quadra Removida' }}</h5>
                                    <p class="text-muted mb-3 small">
                                        <i class="bi bi-geo-alt-fill text-success opacity-75 me-1"></i> {{ $reserva->arena->complexo->nome ?? 'Complexo Stratos' }} 
                                        <span class="mx-2 text-light-subtle">|</span> 
                                        <i class="bi bi-trophy text-warning opacity-75 me-1"></i> {{ $reserva->arena->tipo_esporte ?? 'Esporte' }}
                                    </p>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge bg-success bg-opacity-10 text-success border-0 px-3 py-2 fw-semibold"><i class="bi bi-clock me-1"></i> {{ $reserva->horario }}</span>
                                        <span class="badge bg-success bg-opacity-10 text-success border-0 px-3 py-2 fw-semibold"><i class="bi bi-currency-dollar me-1"></i> {{ ucfirst($reserva->metodo_pagamento) }} - R$ {{ number_format($reserva->valor_total, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 text-md-end">
                                    @switch($statusCalc)
                                        @case('cancelado')
                                            <span class="badge bg-danger text-white px-3 py-2 rounded-pill fw-semibold d-inline-block mb-3 w-100">
                                                <i class="bi bi-x-circle me-1"></i> Cancelado
                                            </span>
                                            @break
                                        @case('em_jogo')
                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-semibold d-inline-block mb-2 w-100">
                                                <i class="bi bi-controller me-1"></i> Em Jogo
                                            </span>
                                            @break
                                        @case('finalizado')
                                            <span class="badge bg-secondary text-white px-3 py-2 rounded-pill fw-semibold d-inline-block mb-2 w-100">
                                                <i class="bi bi-check2-all me-1"></i> Finalizado
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-semibold d-inline-block mb-2 w-100">
                                                <i class="bi bi-hourglass-split me-1"></i> A Iniciar
                                            </span>

                                            <button type="button" class="btn btn-sm btn-outline-danger w-100 py-2 fw-bold rounded-pill mt-1" data-bs-toggle="modal" data-bs-target="#modalCancelar{{ $reserva->id }}">
                                                Cancelar Jogo
                                            </button>

                                            <div class="modal fade" id="modalCancelar{{ $reserva->id }}" tabindex="-1" aria-labelledby="modalCancelarLabel{{ $reserva->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow-lg rounded-4 text-center p-3">
                                                        <div class="modal-header border-0 pb-0 justify-content-end">
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body pb-4">
                                                            <i class="bi bi-exclamation-circle text-danger display-1 mb-3 d-block"></i>
                                                            <h4 class="fw-bold mb-3">Cancelar Reserva?</h4>
                                                            <p class="text-muted mb-1">Tem certeza que deseja cancelar o jogo na <strong>{{ $reserva->arena->nome ?? 'Quadra' }}</strong>?</p>
                                                            <p class="text-muted mb-4">O horário de <strong>{{ $reserva->horario }}</strong> será liberado imediatamente.</p>

                                                            <div class="d-flex gap-2">
                                                                <button type="button" class="btn btn-light fw-bold rounded-pill w-50 py-2" data-bs-dismiss="modal">Não, voltar</button>

                                                                <form action="/reservas/{{ $reserva->id }}/cancelar" method="POST" class="w-50">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger fw-bold rounded-pill w-100 py-2">
                                                                        Sim, cancelar
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center text-muted">
                            <i class="bi bi-calendar-x display-4 d-block mb-3 opacity-25"></i>
                            <p class="mb-0 fw-semibold">Nenhum jogo futuro programado.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <h5 class="fw-bold mb-3 text-muted"><i class="bi bi-clock-history me-2"></i> Histórico Passado</h5>
            
            <div class="card card-stratos p-0 border-0 shadow-sm opacity-75 rounded-3 overflow-hidden">
                <div class="list-group list-group-flush">
                    @forelse($historico as $reserva)
                        <div class="list-group-item p-4">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center border-end border-light mb-3 mb-md-0">
                                    <h4 class="fw-bold text-secondary mb-0">{{ \Carbon\Carbon::parse($reserva->data_reserva)->format('d') }}</h4>
                                    <span class="text-muted text-uppercase small">{{ \Carbon\Carbon::parse($reserva->data_reserva)->translatedFormat('M Y') }}</span>
                                </div>
                                <div class="col-md-7 ps-md-4 mb-3 mb-md-0">
                                    <h6 class="fw-bold text-secondary mb-1">{{ $reserva->arena->nome ?? 'Quadra Removida' }}</h6>
                                    <p class="text-muted mb-2 small"><i class="bi bi-circle-fill text-secondary opacity-50 me-1" style="font-size: 0.5rem;"></i> {{ $reserva->arena->tipo_esporte ?? 'Esporte' }}</p>
                                    <div class="d-flex gap-2 mt-2">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border-0 px-2 py-1">{{ $reserva->horario }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3 text-md-end">
                                    @if($reserva->status_calculado === 'cancelado')
                                        <span class="text-danger fw-bold d-block mb-2"><i class="bi bi-x-circle me-1"></i> Cancelado</span>
                                    @else
                                        <span class="text-muted fw-bold d-block mb-2"><i class="bi bi-check2-all me-1"></i> Finalizado</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            <p class="mb-0 small">Nenhum histórico passado.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
</div>
@endsection