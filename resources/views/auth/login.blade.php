@extends('layouts.app')

@section('conteudo')
<div class="container-fluid p-0 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="card card-stratos p-5 shadow-sm border-0" style="width: 100%; max-width: 450px;">
        
        <div class="text-center mb-4">
            <img src="/img/logo-stratos.svg" alt="Stratos" class="mb-3" style="height: 64px;">
            <h3 class="text-dark fw-bold mb-1">Acesse sua conta</h3>
            <p class="text-muted">Bem-vindo de volta ao <strong class="text-success">STRATOS</strong></p>
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

        <form action="/login" method="POST">
            @csrf
            <input type="hidden" name="redirect" value="{{ request('redirect') }}">

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">E-mail</label>
                <div class="input-group input-group-lg shadow-sm rounded-3">
                    <span class="input-group-text border-0 bg-light"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-0 bg-light" placeholder="seu@email.com" required>
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label class="form-label small fw-bold text-muted text-uppercase">Senha</label>
                    <a href="#" class="text-success small text-decoration-none fw-semibold">Esqueceu?</a>
                </div>
                <div class="input-group input-group-lg shadow-sm rounded-3">
                    <span class="input-group-text border-0 bg-light"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-0 bg-light" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-verde w-100 py-3 fw-bold rounded-pill shadow-sm">
                Entrar <i class="bi bi-arrow-right ms-2"></i>
            </button>
            
            <div class="text-center mt-4">
                <p class="text-muted small mb-1">Não tem uma conta? <a href="/cadastro" class="text-success fw-bold text-decoration-none">Criar conta</a></p>
                <p class="text-muted small mb-0">É proprietário ou funcionário? <a href="/login/administrativo" class="text-dark fw-bold text-decoration-none">Acesse por aqui</a></p>
            </div>
        </form>

    </div>
</div>
@endsection