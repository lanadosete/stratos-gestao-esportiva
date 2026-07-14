@extends('layouts.app')

@section('conteudo')
<div class="container py-5 d-flex justify-content-center">
    <div class="col-md-8 col-lg-6">
        
        <div class="d-flex align-items-center mb-4">
            <a href="/cliente/agendamentos" class="text-decoration-none text-muted fw-semibold me-3">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
            <h3 class="fw-bold mb-0 text-dark">Meu Perfil</h3>
        </div>

        <div class="card card-stratos p-5 border-0 shadow-sm rounded-4">
            <form>
                <div class="text-center mb-5">
                    <!-- Mostra a primeira letra do nome como foto de perfil -->
                    <div class="bg-success bg-opacity-10 text-success rounded-circle mx-auto d-flex align-items-center justify-content-center fw-bold display-4 shadow-sm" style="width: 110px; height: 110px;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border-0 fw-semibold">Conta de Jogador</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">Nome Completo</label>
                    <div class="input-group input-group-lg shadow-sm rounded-3">
                        <span class="input-group-text border-0 bg-light"><i class="bi bi-person text-muted"></i></span>
                        <!-- Puxa o nome real -->
                        <input type="text" class="form-control border-0 bg-light" value="{{ Auth::user()->name }}">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="small fw-bold text-muted text-uppercase">E-mail</label>
                    <div class="input-group input-group-lg shadow-sm rounded-3">
                        <span class="input-group-text border-0 bg-light"><i class="bi bi-envelope text-muted"></i></span>
                        <!-- Puxa o e-mail real e deixa como 'readonly' (somente leitura para proteger a conta) -->
                        <input type="email" class="form-control border-0 bg-light text-muted" value="{{ Auth::user()->email }}" readonly>
                    </div>
                    <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i> O e-mail não pode ser alterado por aqui.</small>
                </div>
                
                <hr class="text-muted my-4">

                <button type="button" class="btn btn-verde w-100 py-3 fw-bold rounded-pill shadow-sm">
                    <i class="bi bi-save me-1"></i> Salvar Alterações
                </button>
            </form>
        </div>
        
    </div>
</div>
@endsection