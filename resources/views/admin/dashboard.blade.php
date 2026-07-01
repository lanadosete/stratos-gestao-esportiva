@extends('layouts.app')

@section('conteudo')
<div class="container-fluid">
    <h2 class="text-success mb-4">Painel Administrativo</h2>
    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-3 border-success text-center">
                <h5>Faturamento Total</h5>
                <h3>R$ 198.750,00</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 border-success text-center">
                <h5>Reservas no Período</h5>
                <h3>2.842</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 border-success text-center">
                <h5>Arenas Cadastradas</h5>
                <h3>48</h3>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <h4>Arenas com mais reservas</h4>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Arena</th>
                    <th>Reservas</th>
                    <th>Faturamento</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Arena Sol Nascente</td>
                    <td>342</td>
                    <td>R$ 28.050,00</td>
                </tr>
                <tr>
                    <td>Arena Praia Sul</td>
                    <td>290</td>
                    <td>R$ 24.100,00</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection