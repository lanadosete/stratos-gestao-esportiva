@extends('layouts.app')

@section('conteudo')
<div class="container py-5" style="max-width: 600px;">
    <h3 class="fw-bold mb-4 text-center">Meu Perfil</h3>
    <div class="card card-stratos p-4 border-0 shadow-sm">
        <form>
            <div class="text-center mb-4">
                <div class="bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">📷</div>
            </div>
            <div class="mb-3">
                <label class="small fw-bold text-muted">Nome</label>
                <input type="text" class="form-control" value="Lucas A.">
            </div>
            <div class="mb-3">
                <label class="small fw-bold text-muted">E-mail</label>
                <input type="email" class="form-control" value="lucas@email.com">
            </div>
            <button class="btn btn-verde w-100 fw-bold">Salvar Alterações</button>
        </form>
    </div>
</div>
@endsection