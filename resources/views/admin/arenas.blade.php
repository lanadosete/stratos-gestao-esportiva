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
                <li class="nav-item mb-3"><a href="/admin/arenas" class="nav-link text-dark fw-bold">Arenas</a></li>
                <li class="nav-item mb-3"><a href="/admin/financeiro" class="nav-link text-muted">Financeiro</a></li>
                <li class="nav-item mb-3"><a href="#" class="nav-link text-muted">Usuários</a></li>
            </ul>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-10 p-5 bg-gradient-stratos">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">Gestão de Arenas</h3>
                <button class="btn btn-verde px-4 fw-bold">+ Nova Arena</button>
            </div>

            <div class="card card-stratos p-4 border-0 shadow-sm">
                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <h5 class="mb-0">Arenas na Plataforma</h5>
                    <input type="text" class="form-control w-25 border-0 bg-light" placeholder="Buscar arena...">
                </div>
                <table class="table table-hover align-middle mt-2">
                    <thead class="text-muted small text-uppercase">
                        <tr>
                            <th>Nome da Arena</th>
                            <th>Proprietário</th>
                            <th>Cidade</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Arena Praia Sul</strong></td>
                            <td>Carlos Mendes</td>
                            <td>Recife, PE</td>
                            <td><span class="badge bg-success bg-opacity-10 text-success border-0 px-2 py-1">Ativa</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-outline-secondary">Gerenciar</button></td>
                        </tr>
                        <tr>
                            <td><strong>Arena Sol Nascente</strong></td>
                            <td>Juliana Costa</td>
                            <td>João Pessoa, PB</td>
                            <td><span class="badge bg-success bg-opacity-10 text-success border-0 px-2 py-1">Ativa</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-outline-secondary">Gerenciar</button></td>
                        </tr>
                        <tr>
                            <td><strong>Quadra Central</strong></td>
                            <td>Roberto Almeida</td>
                            <td>São Paulo, SP</td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary border-0 px-2 py-1">Em Análise</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-outline-secondary">Gerenciar</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection