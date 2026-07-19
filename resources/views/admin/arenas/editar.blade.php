@extends('layouts.app')

@section('conteudo')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="d-flex align-items-center mb-4">
                <a href="/admin/arenas" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h3 class="fw-bold mb-0">Editar Arena</h3>
                    <p class="text-muted mb-0">Atualize os dados da sua arena.</p>
                </div>
            </div>

            <div class="card card-stratos border-0 shadow-sm rounded-4 p-4 p-md-5">
                <form action="/admin/arenas/{{ $arena->id }}/atualizar" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">Nome da Arena</label>
                        <input type="text" name="nome" value="{{ $arena->nome }}" class="form-control form-control-lg bg-light border-0 shadow-none rounded-3" required>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <label class="form-label fw-semibold text-muted">Modalidade</label>
                            <select name="tipo_esporte" class="form-select form-select-lg bg-light border-0 shadow-none rounded-3" required>
                                <option value="Beach Vôlei" {{ $arena->tipo_esporte == 'Beach Vôlei' ? 'selected' : '' }}>Beach Vôlei</option>
                                <option value="Beach Tênis" {{ $arena->tipo_esporte == 'Beach Tênis' ? 'selected' : '' }}>Beach Tênis</option>
                                <option value="Futevôlei" {{ $arena->tipo_esporte == 'Futevôlei' ? 'selected' : '' }}>Futevôlei</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Preço por Hora (R$)</label>
                            <input type="number" step="0.01" name="preco_hora" value="{{ $arena->preco_hora }}" class="form-control form-control-lg bg-light border-0 shadow-none rounded-3" required>
                        </div>
                    </div>
                    
                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-verde py-3 fs-5 fw-bold rounded-pill shadow-sm">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
@endsection