@extends('layouts.app')

@section('conteudo')
<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar Admin -->
        <div class="col-md-2 p-4 sidebar-stratos border-end" style="min-height: 90vh;">
            <div class="mb-5 text-center text-md-start">
                <img src="{{ asset('img/logo-stratos.svg') }}" alt="Logo Stratos" style="max-width: 80px; height: auto;">
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-3"><a href="/admin/dashboard" class="nav-link text-muted">Dashboard</a></li>
                <li class="nav-item mb-3"><a href="/admin/arenas" class="nav-link text-muted">Arenas (Quadras)</a></li>
                <li class="nav-item mb-3"><a href="/admin/financeiro" class="nav-link text-dark fw-bold">Financeiro</a></li>
                <li class="nav-item mb-3"><a href="/admin/equipe" class="nav-link text-muted">Equipe</a></li>
            </ul>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-10 p-5 bg-gradient-stratos">
            
            <h3 class="fw-bold mb-1">Painel Financeiro</h3>
            <p class="text-muted mb-4">Acompanhe as receitas das suas quadras neste mês.</p>
            
            <div class="row mb-4 g-4">
                <div class="col-md-4">
                    <div class="card card-stratos p-4 border-0 shadow-sm h-100 border-bottom border-success border-3">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted text-uppercase fw-bold">Faturamento (Mês)</small>
                            <i class="bi bi-graph-up-arrow text-success"></i>
                        </div>
                        <h3 class="mt-2 fw-bold text-dark">R$ 12.450,00</h3>
                        <small class="text-success"><i class="bi bi-arrow-up-short"></i> +15% vs mês passado</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stratos p-4 border-0 shadow-sm h-100">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted text-uppercase fw-bold">Recebido via Pix</small>
                            <i class="bi bi-phone text-muted"></i>
                        </div>
                        <h3 class="mt-2 fw-bold text-dark">R$ 8.200,00</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stratos p-4 border-0 shadow-sm h-100">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted text-uppercase fw-bold">A Receber (No Local)</small>
                            <i class="bi bi-cash-coin text-muted"></i>
                        </div>
                        <h3 class="text-warning mt-2 fw-bold">R$ 4.250,00</h3>
                    </div>
                </div>
            </div>

            <div class="card card-stratos p-4 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">Últimas Transações (Exemplo)</h5>
                    <button class="btn btn-sm btn-outline-success rounded-pill"><i class="bi bi-download me-1"></i> Exportar</button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mt-2">
                        <thead class="text-muted small text-uppercase">
                            <tr>
                                <th>Data</th>
                                <th>Quadra</th>
                                <th>Cliente</th>
                                <th>Forma de Pagto</th>
                                <th class="text-end">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Hoje, 18:00</td>
                                <td>Quadra 1 - Areia</td>
                                <td>Lucas A.</td>
                                <td><span class="badge bg-success bg-opacity-10 text-success border-0"><i class="bi bi-lightning-charge-fill me-1"></i> Pix</span></td>
                                <td class="text-end fw-bold text-dark">R$ 120,00</td>
                            </tr>
                            <tr>
                                <td>Hoje, 19:00</td>
                                <td>Quadra 2 - Society</td>
                                <td>Mariana S.</td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary border-0"><i class="bi bi-wallet2 me-1"></i> No Local</span></td>
                                <td class="text-end fw-bold text-dark">R$ 150,00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection