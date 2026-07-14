@extends('layouts.app')

@section('conteudo')
<div class="container-fluid p-0 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="card card-stratos p-5 shadow-sm border-0" style="width: 100%; max-width: 500px;">
        
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-person-plus fs-2"></i>
            </div>
            <h3 class="text-dark fw-bold mb-1">Crie sua conta</h3>
            <p class="text-muted">Junte-se ao <strong class="text-success">STRATOS</strong></p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm small py-2 mb-4 bg-danger bg-opacity-10 text-danger rounded-3">
                <ul class="mb-0 ps-3 fw-semibold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/cadastro" method="POST">
            @csrf 
            
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Nome Completo</label>
                <div class="input-group input-group-lg shadow-sm rounded-3">
                    <span class="input-group-text border-0 bg-light"><i class="bi bi-person text-muted"></i></span>
                    <input type="text" name="name" class="form-control border-0 bg-light" placeholder="Ex: João da Silva" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">E-mail</label>
                <div class="input-group input-group-lg shadow-sm rounded-3">
                    <span class="input-group-text border-0 bg-light"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-0 bg-light" placeholder="seu@email.com" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Senha</label>
                <div class="input-group input-group-lg shadow-sm rounded-3">
                    <span class="input-group-text border-0 bg-light"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-0 bg-light" placeholder="••••••••" required>
                </div>
            </div>

            <!-- Seleção de Tipo de Conta -->
            <div class="mb-4">
                <div class="card bg-light border-0 shadow-sm rounded-3">
                    <div class="card-body py-3 px-4 d-flex align-items-center">
                        <div class="form-check form-switch fs-5 mb-0 me-3">
                            <input class="form-check-input" type="checkbox" name="is_admin" value="1" id="tipoConta">
                        </div>
                        <label class="form-check-label" for="tipoConta" style="cursor: pointer;">
                            <span class="fw-bold text-dark d-block" style="font-size: 0.95rem;">Sou proprietário de um espaço</span>
                            <span class="text-muted small d-block" style="font-size: 0.8rem;">Marque para gerenciar quadras e reservas.</span>
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-verde w-100 py-3 fw-bold rounded-pill shadow-sm">
                Criar Conta <i class="bi bi-arrow-right ms-2"></i>
            </button>
            
            <div class="text-center mt-4">
                <p class="text-muted small">Já tem uma conta? <a href="/login" class="text-success fw-bold text-decoration-none">Faça login</a></p>
            </div>
        </form>

    </div>
</div>
@endsection