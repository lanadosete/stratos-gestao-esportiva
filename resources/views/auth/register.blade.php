@extends('layouts.app')

@section('conteudo')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-stratos p-4">
            <h2 class="text-success text-center mb-4">Criar conta no STRATOS</h2>
            
            <form action="#" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nome Completo</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Senha</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-verde w-100">Cadastrar-se</button>
            </form>
        </div>
    </div>
</div>
@endsection