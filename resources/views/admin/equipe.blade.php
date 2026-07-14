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
                <li class="nav-item mb-3"><a href="/admin/financeiro" class="nav-link text-muted">Financeiro</a></li>
                <li class="nav-item mb-3"><a href="/admin/equipe" class="nav-link text-dark fw-bold">Equipe</a></li>
            </ul>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-10 p-5 bg-gradient-stratos">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Minha Equipe</h3>
                    <p class="text-muted mb-0">Gerencie os funcionários que têm acesso ao Painel de Recepção.</p>
                </div>
                <button class="btn btn-verde px-4 py-2 fw-bold shadow-sm rounded-pill">
                    <i class="bi bi-person-plus me-1"></i> Novo Funcionário
                </button>
            </div>

            <div class="card card-stratos p-4 border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mt-2">
                        <thead class="text-muted small text-uppercase">
                            <tr>
                                <th>Nome do Funcionário</th>
                                <th>E-mail de Acesso</th>
                                <th>Cargo/Permissão</th>
                                <th>Status</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3 text-muted" style="width: 40px; height: 40px;">JR</div>
                                        João Recepção
                                    </div>
                                </td>
                                <td>joao@stratos.com</td>
                                <td><span class="badge bg-primary bg-opacity-10 text-primary border-0">Funcionário / Recepção</span></td>
                                <td><span class="badge bg-success bg-opacity-10 text-success border-0">Ativo</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-danger rounded-pill"><i class="bi bi-trash me-1"></i> Remover</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection