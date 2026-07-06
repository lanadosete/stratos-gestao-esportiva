@extends('layouts.app')

@section('conteudo')
<div class="container-fluid p-5">
    <h3 class="fw-bold mb-4">Painel de Recepção</h3>
    <div class="card card-stratos p-4 border-0 shadow-sm">
        <table class="table table-hover align-middle">
            <thead class="text-muted small text-uppercase">
                <tr>
                    <th>Horário</th>
                    <th>Arena</th>
                    <th>Cliente</th>
                    <th>Pagamento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>18:00</strong></td>
                    <td>Arena Sol Nascente</td>
                    <td>Mariana S.</td>
                    <td><span class="badge bg-success bg-opacity-10 text-success">Confirmado</span></td>
                    <td><button class="btn btn-sm btn-verde">Check-in</button></td>
                </tr>
                <tr>
                    <td><strong>19:00</strong></td>
                    <td>Arena Praia Sul</td>
                    <td>Lucas A.</td>
                    <td><span class="badge bg-warning bg-opacity-10 text-warning">Pendente</span></td>
                    <td><button class="btn btn-sm btn-outline-warning">Confirmar Pagamento</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection