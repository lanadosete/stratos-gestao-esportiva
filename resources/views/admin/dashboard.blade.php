@extends('layouts.app')

@section('conteudo')
<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-md-2 p-4 sidebar-stratos border-end" style="min-height: 90vh;">
            <div class="mb-5 text-center text-md-start">
                <img src="{{ asset('img/logo-stratos.svg') }}" alt="Logo Stratos" style="max-width: 80px; height: auto;">
            </div>
            
            <ul class="nav flex-column">
                <li class="nav-item mb-3"><a href="#" class="nav-link text-dark fw-bold">Dashboard</a></li>
                <li class="nav-item mb-3"><a href="#" class="nav-link text-muted">Arenas</a></li>
                <li class="nav-item mb-3"><a href="#" class="nav-link text-muted">Financeiro</a></li>
                <li class="nav-item mb-3"><a href="#" class="nav-link text-muted">Usuários</a></li>
            </ul>
        </div>

        <div class="col-md-10 p-5 bg-gradient-stratos">
            <h3 class="fw-bold mb-4">Painel Administrativo</h3>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card card-stratos p-4 border-0 shadow-sm text-center">
                        <small class="text-muted text-uppercase">Faturamento Total</small>
                        <h4 class="text-success mt-2">R$ 198.750,00</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stratos p-4 border-0 shadow-sm text-center">
                        <small class="text-muted text-uppercase">Reservas no Período</small>
                        <h4 class="mt-2">2.842</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stratos p-4 border-0 shadow-sm text-center">
                        <small class="text-muted text-uppercase">Arenas Cadastradas</small>
                        <h4 class="mt-2">48</h4>
                    </div>
                </div>
            </div>

            <div class="card card-stratos p-4 border-0 shadow-sm">
                <h5 class="mb-3">Arenas com mais reservas</h5>
                <table class="table table-hover mt-2">
                    <thead>
                        <tr>
                            <th>Arena</th>
                            <th>Reservas</th>
                            <th>Faturamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Arena Sol Nascente</td>
                            <td>342</td>
                            <td>R$ 28.050,00</td>
                        </tr>
                        <tr>
                            <td>Arena Praia Sul</td>
                            <td>290</td>
                            <td>R$ 24.100,00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection