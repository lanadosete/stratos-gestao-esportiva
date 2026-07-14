@extends('layouts.app')

@section('conteudo')
<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-md-2 p-4 sidebar-stratos border-end" style="min-height: 90vh;">
            <div class="mb-5 text-center text-md-start">
                <img src="{{ asset('img/logo-stratos.svg') }}" alt="Logo Stratos" style="max-width: 80px; height: auto;">
            </div>
            
            <ul class="nav flex-column">
                <li class="nav-item mb-3"><a href="/admin/dashboard" class="nav-link text-dark fw-bold">Dashboard</a></li>
                <li class="nav-item mb-3"><a href="/admin/arenas" class="nav-link text-muted">Minhas Quadras</a></li>
                <li class="nav-item mb-3"><a href="#" class="nav-link text-muted">Financeiro</a></li>
            </ul>
        </div>

        <div class="col-md-10 p-5 bg-gradient-stratos">
            
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">{{ $complexo->nome }}</h3>
                    <p class="text-muted mt-1 mb-0">{{ $complexo->endereco }} | Tel: {{ $complexo->telefone }}</p>
                </div>
                
                <a href="/admin/arenas/nova" class="btn btn-verde px-4 py-2 fw-bold shadow-sm rounded-pill">
                    + Adicionar Quadra
                </a>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card card-stratos p-4 border-0 shadow-sm text-center">
                        <small class="text-muted text-uppercase">Total de Quadras</small>
                        <h4 class="text-success mt-2">{{ $arenas->count() }}</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stratos p-4 border-0 shadow-sm text-center">
                        <small class="text-muted text-uppercase">Reservas (Em breve)</small>
                        <h4 class="mt-2">0</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stratos p-4 border-0 shadow-sm text-center">
                        <small class="text-muted text-uppercase">Status</small>
                        <h4 class="mt-2 text-primary">Ativo</h4>
                    </div>
                </div>
            </div>

            <div class="card card-stratos p-4 border-0 shadow-sm">
                <h5 class="mb-3">Minhas Quadras Cadastradas</h5>
                
                @if($arenas->count() > 0)
                    <table class="table table-hover mt-2">
                        <thead>
                            <tr>
                                <th>Nome da Quadra</th>
                                <th>Tipo de Esporte</th>
                                <th>Preço/Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($arenas as $arena)
                            <tr>
                                <td>{{ $arena->nome }}</td>
                                <td>{{ $arena->tipo_esporte }}</td>
                                <td>R$ {{ number_format($arena->preco_hora, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">Nenhuma quadra cadastrada neste complexo.</p>
                        <a href="/admin/arenas/nova" class="btn btn-outline-success">Adicionar primeira quadra</a>
                    </div>
                @endif
            </div>
            
        </div>
    </div>
</div>
@endsection