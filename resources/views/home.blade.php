@extends('layouts.app')

@section('conteudo')
<div class="container py-5">
    @guest
        <div class="d-flex justify-content-end mb-4">
            <a href="/login/administrativo" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-semibold">
                <i class="bi bi-shield-lock me-1"></i> Sou proprietário ou funcionário
            </a>
        </div>
    @endguest

    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <img src="/img/logo-stratos.svg" alt="Stratos" class="mb-3" style="height: 56px;">
            <span class="text-success fw-bold d-block">● BEM-VINDO AO STRATOS</span>
            <h1 class="display-4 fw-bold mt-2 text-dark">Gestão inteligente para o <span class="text-success">esporte.</span></h1>
            <p class="text-muted mt-3 mb-4 fs-5">O Stratos é o sistema completo para gerenciar arenas, reservas e horários de forma simples, rápida e eficiente.</p>
            
            <div class="d-flex gap-2 flex-wrap">
                <!-- BOTÃO INTELIGENTE: Muda de acordo com quem está acessando -->
                @guest
                    <a href="/login?redirect=/agendamento" class="btn btn-verde px-4 py-3 fw-bold rounded-pill shadow-sm">Buscar arenas</a>
                @endguest
                
                @auth
                    @if(Auth::user()->tipo_conta === 'cliente')
                        <a href="/agendamento" class="btn btn-verde px-4 py-3 fw-bold rounded-pill shadow-sm">Fazer uma Reserva</a>
                    @elseif(Auth::user()->tipo_conta === 'admin')
                        <a href="/admin/dashboard" class="btn btn-verde px-4 py-3 fw-bold rounded-pill shadow-sm">Ir para meu Dashboard</a>
                    @else
                        <a href="/recepcao" class="btn btn-verde px-4 py-3 fw-bold rounded-pill shadow-sm">Acessar Recepção</a>
                    @endif
                @endauth

                <a href="#como-funciona" class="btn btn-outline-secondary px-4 py-3 fw-bold rounded-pill shadow-sm">Como funciona</a>
            </div>
        </div>
        
        <div class="col-md-6 mt-5 mt-md-0">
            <div class="card card-stratos border-0 shadow-lg rounded-4 overflow-hidden">
                <div id="carouselHome" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="/img/slideshow/slide-1.jpg" class="d-block w-100" style="height: 300px; object-fit: cover;" alt="Stratos">
                        </div>
                        <div class="carousel-item">
                            <img src="/img/slideshow/slide-2.jpg" class="d-block w-100" style="height: 300px; object-fit: cover;" alt="Stratos">
                        </div>
                        <div class="carousel-item">
                            <img src="/img/slideshow/slide-3.jpg" class="d-block w-100" style="height: 300px; object-fit: cover;" alt="Stratos">
                        </div>
                        <div class="carousel-item">
                            <img src="/img/slideshow/slide-4.jpg" class="d-block w-100" style="height: 300px; object-fit: cover;" alt="Stratos">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselHome" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselHome" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselHome" data-bs-slide-to="0" class="active" aria-current="true"></button>
                        <button type="button" data-bs-target="#carouselHome" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#carouselHome" data-bs-slide-to="2"></button>
                        <button type="button" data-bs-target="#carouselHome" data-bs-slide-to="3"></button>
                    </div>
                </div>
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
        @php
            $esportes = [
                ['nome' => 'Futevôlei', 'bola' => 'futevolei'],
                ['nome' => 'Vôlei', 'bola' => 'volei'],
                ['nome' => 'Beach Tennis', 'bola' => 'beach-tenis'],
            ];
        @endphp

        @foreach($esportes as $esporte)
        <div class="col-md-4">
            <div class="card card-stratos p-4 text-center border-0 shadow-sm h-100 rounded-4">
                <div class="mb-3 text-success" style="font-size: 3rem;">
                    @switch($esporte['bola'])
                        @case('volei')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9.3"/>
                                <path d="M12 12C10.3 9.2 10.3 5.6 12 2.7"/>
                                <path d="M12 12C10.3 9.2 10.3 5.6 12 2.7" transform="rotate(120 12 12)"/>
                                <path d="M12 12C10.3 9.2 10.3 5.6 12 2.7" transform="rotate(240 12 12)"/>
                            </svg>
                            @break
                        @case('futevolei')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9.3"/>
                                <path d="M12 7.8 16 10.7 14.5 15.4 9.5 15.4 8 10.7Z" stroke-linejoin="round"/>
                                <path d="M12 7.8V3M16 10.7l4.6-1.5M14.5 15.4l2.8 3.9M9.5 15.4l-2.8 3.9M8 10.7 3.4 9.2"/>
                            </svg>
                            @break
                        @case('beach-tenis')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9.3"/>
                                <path d="M3.6 7c3 2 3 8 0 10"/>
                                <path d="M20.4 7c-3 2-3 8 0 10"/>
                            </svg>
                            @break
                    @endswitch
                </div>
                <h5 class="fw-bold text-dark">{{ $esporte['nome'] }}</h5>
                <p class="small text-muted mb-0">Quadras de {{ strtolower($esporte['nome']) }} com estrutura completa.</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection