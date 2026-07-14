@extends('layouts.app')

@section('conteudo')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card card-stratos p-5 shadow-sm border-0" style="width: 100%; max-width: 600px;">
        
        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle mb-3" style="width: 70px; height: 70px;">
                <i class="bi bi-building fs-1"></i>
            </div>
            <h3 class="text-dark fw-bold mb-1">Bem-vindo(a) ao Stratos!</h3>
            <p class="text-muted">Vamos registrar o seu <strong class="text-success">Espaço Esportivo</strong> para começarmos.</p>
        </div>

        <!-- Exibição de Erros de Validação -->
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm small py-3 mb-4 rounded-3 text-danger bg-danger bg-opacity-10">
                <div class="d-flex align-items-center mb-2 fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Verifique os erros abaixo:
                </div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/admin/complexo/salvar" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Nome do Complexo / Estabelecimento</label>
                <div class="input-group input-group-lg shadow-sm rounded-3">
                    <span class="input-group-text border-0 bg-light"><i class="bi bi-fonts text-muted"></i></span>
                    <input type="text" name="nome" class="form-control border-0 bg-light" placeholder="Ex: Arena Sol Nascente" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Endereço Completo</label>
                <div class="input-group input-group-lg shadow-sm rounded-3">
                    <span class="input-group-text border-0 bg-light"><i class="bi bi-geo-alt text-muted"></i></span>
                    <input type="text" name="endereco" class="form-control border-0 bg-light" placeholder="Rua, Número, Bairro, Cidade" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Telefone / WhatsApp</label>
                <div class="input-group input-group-lg shadow-sm rounded-3">
                    <span class="input-group-text border-0 bg-light"><i class="bi bi-telephone text-muted"></i></span>
                    <input type="text" name="telefone" class="form-control border-0 bg-light" placeholder="(00) 00000-0000" required>
                </div>
            </div>

            <button type="submit" class="btn btn-verde w-100 py-3 fw-bold rounded-pill shadow-sm mt-2">
                Criar Espaço e Continuar <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </form>

    </div>
</div>
@endsection