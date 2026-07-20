@extends('layouts.admin')

@section('admin-content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 bg-white p-4 rounded-4 shadow-sm border-start border-success border-5">
    <div>
        <span class="text-uppercase text-success fw-bold small">Painel Geral</span>
        <h3 class="fw-bold mb-0 mt-1 text-dark">{{ $complexo->nome }}</h3>
        <p class="text-muted mt-1 mb-0"><i class="bi bi-geo-alt text-success me-1"></i> {{ $complexo->endereco }}</p>
    </div>

    <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2">
        <a href="/admin/complexo/{{ $complexo->id }}/editar" class="btn btn-outline-success px-4 py-2 fw-bold shadow-sm rounded-pill text-nowrap">
            Editar Complexo
        </a>
        <a href="/admin/arenas/nova" class="btn btn-success px-4 py-2 fw-bold shadow-sm rounded-pill text-nowrap">
            + Nova Arena
        </a>
    </div>
</div>

<!-- Cartões de Resumo com Dados Reais -->
<div class="d-flex flex-wrap gap-4 mb-5">
    <div style="flex: 1 1 220px; min-width: 220px; max-width: 100%;">
        <div class="card card-stratos p-4 border-0 shadow-sm text-center h-100 rounded-4">
            <div class="d-flex justify-content-center align-items-center mb-3 text-success">
                <i class="bi bi-geo-alt-fill display-6"></i>
            </div>
            <small class="text-muted text-uppercase fw-bold">Arenas Ativas</small>
            <h2 class="text-dark fw-bold mt-2 mb-0">{{ $arenas->count() }}</h2>
        </div>
    </div>
    <div style="flex: 1 1 220px; min-width: 220px; max-width: 100%;">
        <div class="card card-stratos p-4 border-0 shadow-sm text-center h-100 rounded-4">
            <div class="d-flex justify-content-center align-items-center mb-3 text-primary">
                <i class="bi bi-calendar-check-fill display-6"></i>
            </div>
            <small class="text-muted text-uppercase fw-bold">Total de Agendamentos</small>
            <h2 class="text-dark fw-bold mt-2 mb-0">{{ $totalReservas }}</h2>
        </div>
    </div>
    <div style="flex: 1 1 220px; min-width: 220px; max-width: 100%;">
        <div class="card card-stratos p-4 border-0 shadow-sm text-center h-100 rounded-4 bg-success text-white">
            <div class="d-flex justify-content-center align-items-center mb-3">
                <i class="bi bi-check-circle-fill display-6 text-white-50"></i>
            </div>
            <small class="text-white-50 text-uppercase fw-bold">Sistema</small>
            <h2 class="text-white fw-bold mt-2 mb-0">Online</h2>
        </div>
    </div>
</div>

<!-- Tabela Rápida de Quadras -->
<div class="card card-stratos p-0 border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom py-4 px-4 d-flex flex-wrap gap-2 justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-list-task me-2"></i> Minhas Arenas Cadastradas</h5>
        <a href="/admin/arenas" class="btn btn-sm btn-light border rounded-pill px-3 text-nowrap flex-shrink-0">Gerenciar Todas</a>
    </div>

    @if($arenas->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="py-3 px-4">Nome da Arena</th>
                        <th class="py-3">Esportes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arenas as $arena)
                    <tr>
                        <td class="px-4 py-3 fw-bold text-dark">{{ $arena->nome }}</td>
                        <td class="py-3">
                            @php $nomesEsportes = $arena->esportes->where('ativo', true)->pluck('nome')->join(', '); @endphp
                            @if($nomesEsportes)
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2">{{ $nomesEsportes }}</span>
                            @else
                                <span class="badge bg-light text-muted border px-2">Sem esporte configurado</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-cone-striped display-4 text-muted opacity-25 mb-3 d-block"></i>
            <h5 class="fw-bold text-dark">Nenhuma arena cadastrada</h5>
            <p class="text-muted">Seu complexo está vazio. Adicione uma arena para começar a receber reservas.</p>
            <a href="/admin/arenas/nova" class="btn btn-success fw-bold rounded-pill px-4 mt-2">Adicionar Primeira Arena</a>
        </div>
    @endif
</div>
@endsection
