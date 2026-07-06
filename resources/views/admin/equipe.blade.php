@extends('layouts.app')

@section('conteudo')
<div class="container-fluid p-5">
    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">Equipe</h3>
        <button class="btn btn-verde fw-bold">+ Novo Funcionário</button>
    </div>
    <div class="card card-stratos p-4 border-0 shadow-sm">
        <table class="table align-middle">
            <thead>
                <tr><th>Nome</th><th>E-mail</th><th>Cargo</th><th>Ações</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>João Recepção</td>
                    <td>joao@stratos.com</td>
                    <td>Funcionário</td>
                    <td><button class="btn btn-sm btn-outline-danger">Remover</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection