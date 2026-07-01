@extends('layouts.app')

@section('conteudo')
<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <span class="text-success fw-bold">● BEM-VINDO AO STRATOS</span>
            <h1 class="display-4 fw-bold mt-2">Gestão inteligente para o <span class="text-success">esporte.</span></h1>
            <p class="text-muted mt-3 mb-4">O Stratos é o sistema completo para gerenciar arenas, reservas e horários de forma simples, rápida e eficiente.</p>
            <div class="d-flex gap-2">
                <button class="btn btn-success px-4">Buscar arenas</button>
                <button class="btn btn-outline-secondary px-4">Como funciona</button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-stratos border-0 shadow-lg">
                <img src="https://images.unsplash.com/photo-1595435934242-4763e0202283?q=80&w=800" class="card-img-top" style="height: 300px; object-fit: cover;">
                <div class="card-body d-flex justify-content-around py-3">
                    <div class="text-center"><h5>+50</h5><small>Arenas</small></div>
                    <div class="text-center"><h5>+1.000</h5><small>Reservas</small></div>
                    <div class="text-center"><h5>+500</h5><small>Usuários</small></div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <h2 class="fw-bold">Encontre a arena ideal para você</h2>
        <p class="text-muted">Diversas modalidades, horários flexíveis e os melhores espaços.</p>
    </div>

    <div class="row mt-4 g-4">
        @foreach(['Futevôlei', 'Vôlei', 'Beach Tênis'] as $esporte)
        <div class="col-md-4">
            <div class="card card-stratos p-4 text-center border-0 shadow-sm">
                <div class="mb-3 text-success">🎨</div> <h5>{{ $esporte }}</h5>
                <p class="small text-muted">Quadras de {{ strtolower($esporte) }} com estrutura completa.</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection