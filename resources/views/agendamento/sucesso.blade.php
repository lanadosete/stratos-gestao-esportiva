@extends('layouts.app')

@section('conteudo')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <div class="card card-stratos border-0 shadow-lg rounded-4 text-center overflow-hidden">
                
                <div class="bg-success text-white py-5">
                    <i class="bi bi-check-circle-fill display-1 mb-3 d-block"></i>
                    <h2 class="fw-bold mb-0">Reserva Confirmada!</h2>
                    <p class="mb-0 text-white-50">Tudo certo com o seu agendamento.</p>
                </div>

                <div class="card-body p-5">
                    <h5 class="fw-bold text-dark mb-4">{{ $reserva->arena->nome ?? 'Quadra' }}</h5>
                    
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">Data:</span>
                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($reserva->data_reserva)->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">Horário:</span>
                        <span class="fw-semibold">{{ $reserva->horario }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-4 border-bottom pb-2">
                        <span class="text-muted">Total:</span>
                        <span class="fw-bold text-success">R$ {{ number_format($reserva->valor_total, 2, ',', '.') }}</span>
                    </div>

                    <div class="d-grid gap-3 mt-4">
                        <a href="/cliente/agendamentos" class="btn btn-verde py-3 fw-bold rounded-pill shadow-sm">
                            <i class="bi bi-calendar-check me-2"></i> Ver Minhas Reservas
                        </a>
                        
                        <a href="/agendamento" class="btn btn-outline-dark py-3 fw-bold rounded-pill">
                            <i class="bi bi-plus-circle me-2"></i> Fazer Nova Reserva
                        </a>
                        
                        <a href="/" class="text-muted text-decoration-none mt-2 fw-semibold">
                            <i class="bi bi-house me-1"></i> Voltar para a Home
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection