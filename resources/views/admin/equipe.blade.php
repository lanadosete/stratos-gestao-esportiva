@extends('layouts.admin')

@section('admin-content')
<div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="fw-bold mb-4">Minha Equipe</h3>

            <div class="card card-stratos p-4 border-0 shadow-sm rounded-4 mb-5">
                <h5 class="fw-bold mb-3">Cadastrar Novo Funcionário</h5>
                <form action="/admin/equipe/salvar" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="name" class="form-control" placeholder="Nome" required>
                        </div>
                        <div class="col-md-3">
                            <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="telefone" class="form-control" placeholder="WhatsApp: (11) 99999-9999" required>
                        </div>
                        <div class="col-md-3">
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
                            <th>WhatsApp</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($funcionarios as $func)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $func->name }}</td>
                            <td>{{ $func->email }}</td>
                            <td>
                                @if($func->telefone)
                                    <i class="bi bi-whatsapp text-success me-1"></i>{{ $func->telefone }}
                                @else
                                    <span class="text-muted small">Não informado</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-0" style="width: 32px; height: 32px;" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $func->id }}" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-0" style="width: 32px; height: 32px;" data-bs-toggle="modal" data-bs-target="#modalExcluir{{ $func->id }}" title="Remover">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@foreach($funcionarios as $func)
    <!-- Modal de Edição -->
    <div class="modal fade" id="modalEditar{{ $func->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold">Editar Funcionário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <form action="/admin/equipe/{{ $func->id }}/atualizar" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nome</label>
                            <input type="text" name="name" class="form-control" value="{{ $func->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">E-mail</label>
                            <input type="email" name="email" class="form-control" value="{{ $func->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">WhatsApp</label>
                            <input type="text" name="telefone" class="form-control" value="{{ $func->telefone }}" placeholder="(11) 99999-9999" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nova senha</label>
                            <input type="password" name="password" class="form-control" placeholder="Deixe em branco para manter a atual">
                        </div>
                        <button type="submit" class="btn btn-verde w-100 fw-bold rounded-pill">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Remoção -->
    <div class="modal fade" id="modalExcluir{{ $func->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 text-center p-3">
                <div class="modal-header border-0 pb-0 justify-content-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-4">
                    <i class="bi bi-exclamation-circle text-danger display-1 mb-3 d-block"></i>
                    <h4 class="fw-bold mb-3">Remover Funcionário?</h4>
                    <p class="text-muted mb-4">Tem certeza que deseja remover <strong>{{ $func->name }}</strong> da equipe? Essa ação não pode ser desfeita.</p>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light fw-bold rounded-pill w-50 py-2" data-bs-dismiss="modal">Cancelar</button>
                        <form action="/admin/equipe/{{ $func->id }}/excluir" method="POST" class="w-50">
                            @csrf
                            <button type="submit" class="btn btn-danger fw-bold rounded-pill w-100 py-2">Remover</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection