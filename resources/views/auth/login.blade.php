@extends('layouts.app')

@section('conteudo')
<div class="container-fluid p-0 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="card card-stratos p-5 shadow border-0" style="width: 100%; max-width: 450px;">
        
        <div class="text-center mb-4">
            <h3 class="text-success fw-bold">STRATOS</h3>
            <p class="text-muted">Bem-vindo de volta, acesse sua conta</p>
        </div>

        <form action="/login" method="POST">
            @csrf <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">E-mail</label>
                <input type="email" name="email" class="form-control form-control-lg border-0 bg-light shadow-sm" placeholder="seu@email.com" required>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label class="form-label small fw-bold text-muted text-uppercase">Senha</label>
                    <a href="#" class="text-success small text-decoration-none">Esqueceu?</a>
                </div>
                <input type="password" name="password" class="form-control form-control-lg border-0 bg-light shadow-sm" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-verde w-100 py-3 fw-bold">Entrar</button>
            
            <div class="text-center mt-4">
                <p class="text-muted small">Não tem uma conta? <a href="/cadastro" class="text-success fw-bold text-decoration-none">Criar conta</a></p>
            </div>
        </form>

    </div>
</div>
@endsection