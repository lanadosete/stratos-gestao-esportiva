@extends('layouts.app')

@section('conteudo')
<!-- Busca dinâmica das quadras do usuário logado direto na view -->
@php
    $complexo = \App\Models\Complexo::where('user_id', Auth::id())->first();
    $arenas = $complexo ? $complexo->arenas : collect();
@endphp

<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar Admin -->
        <div class="col-md-2 p-4 sidebar-stratos border-end" style="min-height: 90vh;">
            <div class="mb-5 text-center text-md-start">
                <img src="{{ asset('img/logo-stratos.svg') }}" alt="Logo Stratos" style="max-width: 80px; height: auto;">
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-3"><a href="/admin/dashboard" class="nav-link text-muted">Dashboard</a></li>
                <li class="nav-item mb-3"><a href="/admin/arenas" class="nav-link text-dark fw-bold">Arenas (Quadras)</a></li>
                <li class="nav-item mb-3"><a href="/admin/financeiro" class="nav-link text-muted">Financeiro</a></li>
                <li class="nav-item mb-3"><a href="/admin/equipe" class="nav-link text-muted">Equipe</a></li>
            </ul>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-10 p-5 bg-gradient-stratos">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Gestão de Quadras</h3>
                    <p class="text-muted mb-0">Gerencie todas as modalidades do seu complexo.</p>
                </div>
                <a href="/admin/arenas/nova" class="btn btn-verde px-4 py-2 fw-bold shadow-sm rounded-pill">
                    <i class="bi bi-plus-circle me-1"></i> Nova Quadra
                </a>
            </div>

            <div class="card card-stratos p-4 border-0 shadow-sm">
                
                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <h5 class="mb-0 fw-bold">Quadras Ativas</h5>
                    <div class="input-group w-25 shadow-sm rounded-3">
                        <span class="input-group-text border-0 bg-light"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-0 bg-light" placeholder="Buscar quadra...">
                    </div>
                </div>

                @if($arenas->count() > 0)
                    <div class="table-responsive mt-3">
                        <table class="table table-hover align-middle">
                            <thead class="text-muted small text-uppercase">
                                <tr>
                                    <th>Nome da Quadra</th>
                                    <th>Esporte</th>
                                    <th>Preço (Hora)</th>
                                    <th>Status</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($arenas as $arena)
                                <tr>
                                    <td class="fw-bold">{{ $arena->nome }}</td>
                                    <td>{{ $arena->tipo_esporte }}</td>
                                    <td class="text-success fw-bold">R$ {{ number_format($arena->preco_hora, 2, ',', '.') }}</td>
                                    <td><span class="badge bg-success bg-opacity-10 text-success border-0 px-2 py-1"><i class="bi bi-check-circle me-1"></i> Liberada</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill me-1"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-danger rounded-pill"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <p class="text-muted">Nenhuma quadra cadastrada.</p>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endsection