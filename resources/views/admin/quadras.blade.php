@extends('layouts.admin')

@section('admin-content')
<div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Minhas Arenas</h3>
        <p class="text-muted mb-0">Gerencie os espaços do seu complexo.</p>
    </div>
    <a href="/admin/arenas/nova" class="btn btn-verde fw-bold rounded-pill px-4 shadow-sm text-nowrap flex-shrink-0">
        <i class="bi bi-plus-lg me-1"></i> Nova Arena
    </a>
</div>

<div class="d-flex flex-wrap gap-4">
    @forelse($arenas as $arena)
        @php $nomesEsportes = $arena->esportes->where('ativo', true)->pluck('nome')->join(', '); @endphp
        <div style="flex: 1 1 280px; min-width: 280px; max-width: 100%;">
            <div class="card card-stratos border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 120px;">
                    <i class="bi bi-image display-4 opacity-25"></i>
                </div>
                <div class="card-body p-4">
                    <span class="badge bg-success bg-opacity-10 text-success mb-2 px-3 py-2 rounded-pill">
                        <i class="bi bi-trophy me-1"></i> {{ $nomesEsportes ?: 'Sem esporte configurado' }}
                    </span>
                    <h5 class="fw-bold mb-1">{{ $arena->nome }}</h5>
                    <p class="text-muted small mb-4">Configuração por turno e esporte.</p>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="/admin/arenas/{{ $arena->id }}/editar" class="btn btn-outline-dark fw-bold rounded-pill flex-fill text-nowrap">
                            <i class="bi bi-pencil-square me-1"></i> Editar
                        </a>

                        <form action="/admin/arenas/{{ $arena->id }}/excluir" method="POST" class="flex-fill" onsubmit="return confirm('Tem certeza que deseja apagar esta arena definitivamente?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger fw-bold rounded-pill w-100 text-nowrap">
                                <i class="bi bi-trash3 me-1"></i> Excluir
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5 text-muted w-100">
            <i class="bi bi-inbox display-1 d-block mb-3 opacity-25"></i>
            <h5>Nenhuma arena cadastrada</h5>
            <p>Clique no botão "Nova Arena" acima para começar.</p>
        </div>
    @endforelse
</div>
@endsection
