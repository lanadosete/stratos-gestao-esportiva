@extends('layouts.app')

@section('conteudo')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 border-bottom pb-4">
                <div>
                    <h3 class="fw-bold mb-1">Meus Agendamentos</h3>
                    <p class="text-muted mb-0">Gerencie suas próximas partidas e veja seu histórico.</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="/agendamento" class="btn btn-verde px-4 py-2 fw-bold">+ Nova Reserva</a>
                </div>
            </div>

            <h5 class="fw-bold mb-3">Próximos Jogos</h5>
            <div class="card card-stratos p-0 border-0 shadow-sm mb-5">
                <div class="list-group list-group-flush rounded-3">
                    <div class="list-group-item p-4 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center border-end mb-3 mb-md-0">
                                <h3 class="fw-bold text-success mb-0">05</h3>
                                <span class="text-muted text-uppercase small fw-bold">Jul 2026</span>
                            </div>
                            <div class="col-md-7 ps-md-4 mb-3 mb-md-0">
                                <h5 class="fw-bold mb-1">Arena Sol Nascente</h5>
                                <p class="text-muted mb-3 small">📍 Recife, PE <span class="mx-2 text-light-subtle">|</span> 🎾 Beach Tennis</p>
                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="badge bg-success bg-opacity-10 text-success border-0 px-3 py-2">18:00</span>
                                    <span class="badge bg-success bg-opacity-10 text-success border-0 px-3 py-2">Pix - R$ 80,00</span>
                                </div>
                            </div>
                            <div class="col-md-3 text-md-end">
                                <span class="text-success fw-bold d-block mb-3">● Confirmado</span>
                                <button class="btn btn-sm btn-outline-secondary w-100 py-2 fw-bold">Ver Detalhes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mb-3 text-muted">Histórico</h5>
            <div class="card card-stratos p-0 border-0 shadow-sm opacity-75">
                <div class="list-group list-group-flush rounded-3">
                    <div class="list-group-item p-4">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center border-end mb-3 mb-md-0">
                                <h4 class="fw-bold text-secondary mb-0">28</h4>
                                <span class="text-muted text-uppercase small">Jun 2026</span>
                            </div>
                            <div class="col-md-7 ps-md-4 mb-3 mb-md-0">
                                <h6 class="fw-bold text-secondary mb-1">Arena Praia Sul</h6>
                                <p class="text-muted mb-2 small" style="font-size: 0.8rem;">🏐 Vôlei de Praia</p>
                                <div class="d-flex gap-2 mt-2">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border-0 px-2 py-1">19:00 às 21:00</span>
                                </div>
                            </div>
                            <div class="col-md-3 text-md-end">
                                <span class="text-muted fw-bold d-block mb-2">Finalizado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection