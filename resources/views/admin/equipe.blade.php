@extends('layouts.app')

@section('conteudo')
<div class="bg-gradient-stratos" style="min-height: 100vh;">
<div class="container py-5">
<div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="fw-bold mb-4">Minha Equipe</h3>

            <div class="card card-stratos p-4 border-0 shadow-sm rounded-4 mb-5">
                <h5 class="fw-bold mb-3">Cadastrar Novo Funcionário</h5>
                <form action="/admin/equipe/salvar" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="name" class="form-control" placeholder="Nome" required>
                        </div>
                        <div class="col-md-4">
                            <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                        </div>
                        <div class="col-md-4">
                            <input type="password" name="password" class="form-control" placeholder="Senha inicial" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-verde mt-3 w-100 fw-bold">Cadastrar Funcionário</button>
                </form>
            </div>

            <h5 class="fw-bold mb-3">Equipe Atual</h5>
            <div class="card card-stratos p-0 border-0 shadow-sm rounded-4">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Nome</th>
                            <th>E-mail</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\User::where('tipo_conta', 'funcionario')->get() as $func)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $func->name }}</td>
                            <td>{{ $func->email }}</td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-danger">Remover</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection