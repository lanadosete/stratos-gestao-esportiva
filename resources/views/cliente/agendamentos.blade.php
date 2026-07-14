@extends('layouts.app')

@section('conteudo')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 border-bottom border-success border-opacity-25 pb-4">
                <div>
                    <!-- Cumprimenta o usuário pelo primeiro nome -->
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
            
            <!-- Card de Reserva (Exemplo Visual) -->
            <div class="card card-stratos p-0 border-0 shadow-sm mb-5 rounded-3 overflow-hidden">
                <div class="list-group list-group-flush">
                    <div class="list-group-item p-4 border-bottom border-light">
                        <div class="row align-items-center">
                            <!-- Data -->
                            <div class="col-md-2 text-center border-end border-light mb-3 mb-md-0">
                                <h2 class="fw-bold text-success mb-0">18</h2>
                                <span class="text-muted text-uppercase small fw-bold">Jul 2026</span>
                            </div>
                            
                            <!-- Detalhes do Jogo -->
                            <div class="col-md-7 ps-md-4 mb-3 mb-md-0">
                                <h5 class="fw-bold mb-1 text-dark">Arena Sol Nascente</h5>
                                <p class="text-muted mb-3 small">
                                    <i class="bi bi-geo-alt-fill text-success opacity-75 me-1"></i> Recife, PE 
                                    <span class="mx-2 text-light-subtle">|</span> 
                                    <i class="bi bi-trophy text-warning opacity-75 me-1"></i> Beach Tennis
                                </p>
                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="badge bg-success bg-opacity-10 text-success border-0 px-3 py-2 fw-semibold"><i class="bi bi-clock me-1"></i> 18:00</span>
                                    <span class="badge bg-success bg-opacity-10 text-success border-0 px-3 py-2 fw-semibold"><i class="bi bi-lightning-charge-fill me-1"></i> Pix - R$ 80,00</span>
                                </div>
                            </div>
                            
                            <!-- Ações / Status -->
                            <div class="col-md-3 text-md-end">
                                <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-semibold d-inline-block mb-3">
                                    <i class="bi bi-check-circle me-1"></i> Confirmado
                                </span>
                                <button class="btn btn-sm btn-outline-secondary w-100 py-2 fw-bold rounded-pill">Ver Detalhes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mb-3 text-muted"><i class="bi bi-clock-history me-2"></i> Histórico Passado</h5>
            
            <!-- Histórico (Exemplo Visual) -->
            <div class="card card-stratos p-0 border-0 shadow-sm opacity-75 rounded-3 overflow-hidden">
                <div class="list-group list-group-flush">
                    <div class="list-group-item p-4">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center border-end border-light mb-3 mb-md-0">
                                <h4 class="fw-bold text-secondary mb-0">28</h4>
                                <span class="text-muted text-uppercase small">Jun 2026</span>
                            </div>
                            <div class="col-md-7 ps-md-4 mb-3 mb-md-0">
                                <h6 class="fw-bold text-secondary mb-1">Arena Praia Sul</h6>
                                <p class="text-muted mb-2 small"><i class="bi bi-circle-fill text-secondary opacity-50 me-1" style="font-size: 0.5rem;"></i> Vôlei de Praia</p>
                                <div class="d-flex gap-2 mt-2">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border-0 px-2 py-1">19:00 às 21:00</span>
                                </div>
                            </div>
                            <div class="col-md-3 text-md-end">
                                <span class="text-muted fw-bold d-block mb-2"><i class="bi bi-check2-all me-1"></i> Finalizado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection