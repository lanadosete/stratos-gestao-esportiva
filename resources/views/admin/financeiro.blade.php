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
                <li class="nav-item mb-3"><a href="/admin/arenas" class="nav-link text-muted">Arenas</a></li>
                <li class="nav-item mb-3"><a href="/admin/financeiro" class="nav-link text-dark fw-bold">Financeiro</a></li>
                <li class="nav-item mb-3"><a href="#" class="nav-link text-muted">Usuários</a></li>
            </ul>
        </div>

        <div class="col-md-10 p-5 bg-gradient-stratos">
            <h3 class="fw-bold mb-4">Financeiro Global</h3>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card card-stratos p-4 border-0 shadow-sm">
                        <small class="text-muted text-uppercase">Volume Transacionado (Mês)</small>
                        <h4 class="mt-2 fw-bold">R$ 45.200,00</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stratos p-4 border-0 shadow-sm">
                        <small class="text-muted text-uppercase">Receita Stratos (Taxas)</small>
                        <h4 class="text-success mt-2 fw-bold">R$ 4.520,00</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stratos p-4 border-0 shadow-sm">
                        <small class="text-muted text-uppercase">Repasses Pendentes</small>
                        <h4 class="text-warning mt-2 fw-bold">R$ 12.800,00</h4>
                    </div>
                </div>
            </div>

            <div class="card card-stratos p-4 border-0 shadow-sm">
                <h5 class="mb-3">Últimas Transações</h5>
                <table class="table table-hover align-middle">
                    <thead class="text-muted small text-uppercase">
                        <tr>
                            <th>Data</th>
                            <th>Arena</th>
                            <th>Cliente</th>
                            <th>Valor Total</th>
                            <th>Taxa Stratos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>06/07/2026</td>
                            <td>Arena Praia Sul</td>
                            <td>Lucas A.</td>
                            <td>R$ 120,00</td>
                            <td class="text-success fw-bold">+ R$ 12,00</td>
                        </tr>
                        <tr>
                            <td>05/07/2026</td>
                            <td>Arena Sol Nascente</td>
                            <td>Mariana S.</td>
                            <td>R$ 150,00</td>
                            <td class="text-success fw-bold">+ R$ 15,00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection