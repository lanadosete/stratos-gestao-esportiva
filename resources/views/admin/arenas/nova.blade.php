@extends('layouts.app')

@section('conteudo')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <!-- Botão de Voltar -->
            <div class="mb-4">
                <a href="/admin/dashboard" class="text-decoration-none text-muted fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Voltar para o Dashboard
                </a>
            </div>

            <div class="card card-stratos p-5 shadow-sm border-0">
                
                <div class="mb-4">
                    <h4 class="text-success fw-bold mb-1">Adicionar Nova Quadra</h4>
                    <p class="text-muted">Cadastre as informações da quadra para disponibilizar aos seus clientes.</p>
                </div>

                <!-- Exibição de Erros de Validação -->
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm small py-3 mb-4 rounded-3 text-danger bg-danger bg-opacity-10">
                        <ul class="mb-0 ps-3 fw-semibold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/admin/arenas/salvar" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Nome / Identificação da Quadra</label>
                        <input type="text" name="nome" class="form-control form-control-lg bg-light border-0 shadow-sm" placeholder="Ex: Quadra 1 - Areia" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Tipo de Esporte</label>
                        <input type="text" name="tipo_esporte" class="form-control form-control-lg bg-light border-0 shadow-sm" placeholder="Ex: Beach Tennis, Vôlei, Society" required>
                        <div class="form-text text-muted small mt-1"><i class="bi bi-info-circle me-1"></i> Digite a principal modalidade dessa quadra.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Preço por Hora (R$)</label>
                        <div class="input-group input-group-lg shadow-sm rounded-3">
                            <span class="input-group-text border-0 bg-success bg-opacity-10 text-success fw-bold">R$</span>
                            <input type="number" step="0.01" name="preco_hora" class="form-control border-0 bg-light" placeholder="0,00" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-verde w-100 py-3 fw-bold rounded-pill shadow-sm mt-3">
                        <i class="bi bi-check2-circle me-2"></i> Salvar Nova Quadra
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection