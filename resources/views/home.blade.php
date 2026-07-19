@extends('layouts.app')

@section('conteudo')
<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <span class="text-success fw-bold">● BEM-VINDO AO STRATOS</span>
            <h1 class="display-4 fw-bold mt-2 text-dark">Gestão inteligente para o <span class="text-success">esporte.</span></h1>
            <p class="text-muted mt-3 mb-4 fs-5">O Stratos é o sistema completo para gerenciar arenas, reservas e horários de forma simples, rápida e eficiente.</p>
            
            <div class="d-flex gap-2 flex-wrap">
                <!-- BOTÃO INTELIGENTE: Muda de acordo com quem está acessando -->
                @guest
                    <a href="/login" class="btn btn-verde px-4 py-3 fw-bold rounded-pill shadow-sm">Buscar arenas</a>
                @endguest
                
                @auth
                    @if(Auth::user()->tipo_conta === 'cliente')
                        <a href="/agendamento" class="btn btn-verde px-4 py-3 fw-bold rounded-pill shadow-sm">Fazer uma Reserva</a>
                    @elseif(Auth::user()->tipo_conta === 'admin')
                        <a href="/admin/dashboard" class="btn btn-verde px-4 py-3 fw-bold rounded-pill shadow-sm">Ir para meu Dashboard</a>
                    @else
                        <a href="/recepcao" class="btn btn-verde px-4 py-3 fw-bold rounded-pill shadow-sm">Acessar Recepção</a>
                    @endif

                    <a href="/perfil" class="btn btn-outline-success px-4 py-3 fw-bold rounded-pill shadow-sm">
                        <i class="bi bi-person me-1"></i> Meu Perfil
                    </a>
                @endauth

                <a href="#como-funciona" class="btn btn-outline-secondary px-4 py-3 fw-bold rounded-pill shadow-sm">Como funciona</a>
            </div>
        </div>
        
        <div class="col-md-6 mt-5 mt-md-0">
            <div class="card card-stratos border-0 shadow-lg rounded-4 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1595435934242-4763e0202283?q=80&w=800" class="card-img-top" style="height: 300px; object-fit: cover;">
                <div class="card-body d-flex justify-content-around py-4 bg-white">
                    <div class="text-center"><h4 class="fw-bold text-success mb-0">+50</h4><small class="text-muted fw-semibold">Arenas</small></div>
                    <div class="text-center"><h4 class="fw-bold text-success mb-0">+1.000</h4><small class="text-muted fw-semibold">Reservas</small></div>
                    <div class="text-center"><h4 class="fw-bold text-success mb-0">+500</h4><small class="text-muted fw-semibold">Usuários</small></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Âncora para o botão 'Como Funciona' -->
    <div class="text-center mt-5 pt-5" id="como-funciona">
        <h2 class="fw-bold text-dark">Encontre a arena ideal para você</h2>
        <p class="text-muted fs-5">Diversas modalidades, horários flexíveis e os melhores espaços.</p>
    </div>

    <div class="row mt-4 g-4 pb-5">
        <!-- Substituindo os emojis por ícones reais -->
        @php
            $esportes = [
                ['nome' => 'Futevôlei', 'icone' => 'bi-dribbble'],
                ['nome' => 'Vôlei', 'icone' => 'bi-circle'],
                ['nome' => 'Beach Tennis', 'icone' => 'bi-trophy']
            ];
        @endphp
        
        @foreach($esportes as $esporte)
        <div class="col-md-4">
            <div class="card card-stratos p-4 text-center border-0 shadow-sm h-100 rounded-4">
                <div class="mb-3 text-success display-5"><i class="bi {{ $esporte['icone'] }}"></i></div> 
                <h5 class="fw-bold text-dark">{{ $esporte['nome'] }}</h5>
                <p class="small text-muted mb-0">Quadras de {{ strtolower($esporte['nome']) }} com estrutura completa.</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection