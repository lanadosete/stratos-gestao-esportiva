@extends('layouts.app')

@section('conteudo')
<style>
    /* Estilos específicos para o degradê do Stratos */
    .bg-gradient-stratos {
        background: linear-gradient(135deg, #e8f5e9 0%, #ffffff 100%);
    }
    .sidebar-stratos {
        background: linear-gradient(180deg, #dcedc8 0%, #ffffff 100%);
    }
    .card-stratos {
        border-radius: 12px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Lateral (Fiel ao protótipo, limpa e profissional) -->
        <div class="col-md-2 p-4 sidebar-stratos border-end" style="min-height: 90vh;">
            <h5 class="text-success mb-5 fw-bold">STRATOS</h5>
            <ul class="nav flex-column">
                <li class="nav-item mb-3"><a href="#" class="nav-link text-dark fw-bold">Dashboard</a></li>
                <li class="nav-item mb-3"><a href="#" class="nav-link text-muted">Agenda</a></li>
                <li class="nav-item mb-3"><a href="#" class="nav-link text-muted">Financeiro</a></li>
                <li class="nav-item mb-3"><a href="#" class="nav-link text-muted">Clientes</a></li>
            </ul>
        </div>

        <!-- Conteúdo Principal com degradê suave -->
        <div class="col-md-10 p-5 bg-gradient-stratos">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">Dashboard</h3>
                <button class="btn btn-success px-4">Nova Reserva</button>
            </div>
            
            <!-- Cards de Indicadores (Focados em métricas rápidas) -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card card-stratos p-4 border-0 shadow-sm">
                        <small class="text-muted text-uppercase">Faturamento</small>
                        <h4 class="text-success mt-2">R$ 450,00</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stratos p-4 border-0 shadow-sm">
                        <small class="text-muted text-uppercase">Reservas</small>
                        <h4 class="mt-2">03</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stratos p-4 border-0 shadow-sm">
                        <small class="text-muted text-uppercase">Ocupação</small>
                        <h4 class="mt-2">60%</h4>
                    </div>
                </div>
            </div>

            <!-- Área de Trabalho (Tabela de Agenda) -->
            <div class="card card-stratos p-4 border-0 shadow-sm">
                <h5 class="mb-3">Resumo da Agenda</h5>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Horário</th>
                            <th>Cliente</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>18:00</td>
                            <td>Lucas A.</td>
                            <td><span class="text-success">● Confirmado</span></td>
                            <td><button class="btn btn-sm btn-outline-secondary">Detalhes</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection