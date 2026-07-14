@extends('layouts.app')

@section('conteudo')
<div class="container py-5">
    
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

    <!-- Indicadores Rápidos -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card card-stratos p-3 border-0 shadow-sm border-start border-success border-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase fw-bold">Total de Reservas (Hoje)</small>
                        <h3 class="mb-0 fw-bold text-dark mt-1">12</h3>
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
                        <h3 class="mb-0 fw-bold text-dark mt-1">4</h3>
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
                        <h3 class="mb-0 fw-bold text-dark mt-1">5</h3>
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
            <div class="input-group shadow-sm rounded-3" style="max-width: 300px;">
                <span class="input-group-text border-0 bg-light"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control border-0 bg-light" placeholder="Buscar cliente...">
            </div>
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
                    <!-- Exemplo 1: Cliente já pagou via Pix -->
                    <tr>
                        <td><h5 class="mb-0 fw-bold text-dark">18:00</h5></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex justify-content-center align-items-center me-2 fw-bold" style="width: 35px; height: 35px;">
                                    M
                                </div>
                                <span class="fw-semibold text-dark">Mariana S.</span>
                            </div>
                        </td>
                        <td>
                            <span class="d-block fw-bold text-dark">Arena Sol Nascente</span>
                            <small class="text-muted">Beach Tennis</small>
                        </td>
                        <td>
                            <span class="badge bg-success bg-opacity-10 text-success border-0 px-2 py-1">
                                <i class="bi bi-check-circle me-1"></i> Pix Pago
                            </span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-verde rounded-pill px-3 fw-bold shadow-sm">
                                Liberar Entrada <i class="bi bi-door-open ms-1"></i>
                            </button>
                        </td>
                    </tr>
                    
                    <!-- Exemplo 2: Cliente escolheu pagar no local -->
                    <tr>
                        <td><h5 class="mb-0 fw-bold text-dark">19:00</h5></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex justify-content-center align-items-center me-2 fw-bold" style="width: 35px; height: 35px;">
                                    L
                                </div>
                                <span class="fw-semibold text-dark">Lucas A.</span>
                            </div>
                        </td>
                        <td>
                            <span class="d-block fw-bold text-dark">Arena Praia Sul</span>
                            <small class="text-muted">Vôlei de Praia</small>
                        </td>
                        <td>
                            <span class="badge bg-warning bg-opacity-10 text-warning border-0 px-2 py-1">
                                <i class="bi bi-clock me-1"></i> R$ 120 (Pendente)
                            </span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-bold shadow-sm">
                                Receber e Liberar <i class="bi bi-cash-coin ms-1"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Exemplo 3: Cliente já fez check-in -->
                    <tr class="opacity-50">
                        <td><h5 class="mb-0 fw-bold text-muted">16:00</h5></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex justify-content-center align-items-center me-2 fw-bold" style="width: 35px; height: 35px;">
                                    R
                                </div>
                                <span class="fw-semibold text-muted">Roberto C.</span>
                            </div>
                        </td>
                        <td>
                            <span class="d-block fw-bold text-muted">Quadra Central</span>
                            <small class="text-muted">Futevôlei</small>
                        </td>
                        <td>
                            <span class="badge bg-success bg-opacity-10 text-success border-0 px-2 py-1">
                                <i class="bi bi-check-circle me-1"></i> Cartão
                            </span>
                        </td>
                        <td class="text-end">
                            <span class="text-muted fw-bold small"><i class="bi bi-check2-all me-1"></i> Em Jogo</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
</div>
@endsection