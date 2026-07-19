@extends('layouts.app')

@section('conteudo')
<div class="container py-5">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Minhas Arenas</h3>
            <p class="text-muted mb-0">Gerencie os espaços do seu complexo.</p>
        </div>
        <a href="/admin/arenas/nova" class="btn btn-verde fw-bold rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Nova Arena
        </a>
    </div>

    <div class="row g-4">
        @forelse($arenas as $arena)
            <div class="col-md-4">
                <div class="card card-stratos border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 120px;">
                        <i class="bi bi-image display-4 opacity-25"></i>
                    </div>
                    <div class="card-body p-4">
                        <span class="badge bg-success bg-opacity-10 text-success mb-2 px-3 py-2 rounded-pill"><i class="bi bi-trophy me-1"></i> {{ $arena->tipo_esporte }}</span>
                        <h5 class="fw-bold mb-1">{{ $arena->nome }}</h5>
                        <p class="text-muted small mb-4">Configuração por turno e esporte.</p>
                        
                        <div class="d-flex gap-2">
                            <a href="/admin/arenas/{{ $arena->id }}/editar" class="btn btn-outline-dark fw-bold rounded-pill w-50">
                                <i class="bi bi-pencil-square me-1"></i> Editar
                            </a>
                            
                            <form action="/admin/arenas/{{ $arena->id }}/excluir" method="POST" class="w-50" onsubmit="return confirm('Tem certeza que deseja apagar esta arena definitivamente?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger fw-bold rounded-pill w-100">
                                    <i class="bi bi-trash3 me-1"></i> Excluir
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-inbox display-1 d-block mb-3 opacity-25"></i>
                <h5>Nenhuma arena cadastrada</h5>
                <p>Clique no botão "Nova Arena" acima para começar.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection