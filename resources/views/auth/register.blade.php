@extends('layouts.app')

@section('conteudo')
<div class="container-fluid p-0 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="card card-stratos p-5 shadow border-0" style="width: 100%; max-width: 500px;">
        
        <div class="text-center mb-4">
            <h3 class="text-success fw-bold">STRATOS</h3>
            <p class="text-muted">Crie sua conta para começar a jogar</p>
        </div>

        <form>
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Nome Completo</label>
                <input type="text" class="form-control form-control-lg border-0 bg-light shadow-sm" placeholder="Ex: João da Silva">
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">E-mail</label>
                <input type="email" class="form-control form-control-lg border-0 bg-light shadow-sm" placeholder="seu@email.com">
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Senha</label>
                <input type="password" class="form-control form-control-lg border-0 bg-light shadow-sm" placeholder="••••••••">
            </div>

            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="tipoConta">
                    <label class="form-check-label text-muted small" for="tipoConta">Sou proprietário de uma arena</label>
                </div>
            </div>

            <button type="submit" class="btn btn-verde w-100 py-3 fw-bold">Criar Conta</button>
            
            <div class="text-center mt-4">
                <p class="text-muted small">Já tem uma conta? <a href="#" class="text-success fw-bold text-decoration-none">Faça login</a></p>
            </div>
        </form>

    </div>
</div>
@endsection