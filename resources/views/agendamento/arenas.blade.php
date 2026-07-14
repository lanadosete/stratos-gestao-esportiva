@extends('layouts.app')

@section('conteudo')
@php
    // Busca todas as arenas ativas no sistema para o cliente escolher.
    // Traz também o complexo dono daquela arena para mostrar o endereço.
    $arenas = \App\Models\Arena::with('complexo')->get();
@endphp

<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <!-- Barra de Progresso -->
            <div class="d-flex flex-wrap gap-2 mb-5 mt-3">
                <span class="badge bg-success px-4 py-2 rounded-pill fs-6 fw-normal shadow-sm">1. Escolher Quadra</span>
                <span class="badge bg-light text-secondary border px-4 py-2 rounded-pill fs-6 fw-normal">2. Data e Hora</span>
                <span class="badge bg-light text-secondary border px-4 py-2 rounded-pill fs-6 fw-normal">3. Pagamento</span>
            </div>

            <div class="mb-4">
                <h3 class="fw-bold mb-1">Selecione uma Quadra</h3>
                <p class="text-muted">Escolha o local ideal para o seu jogo.</p>
            </div>

            <div class="row g-4">
                @forelse($arenas as $arena)
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-stratos p-4 shadow-sm border-0 h-100 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">{{ $arena->nome }}</h5>
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt-fill text-success me-1"></i> 
                                        {{ $arena->complexo->nome ?? 'Complexo Stratos' }}
                                    </small>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success border-0 px-3 py-2 fw-semibold">
                                    {{ $arena->tipo_esporte }}
                                </span>
                            </div>
                            
                            <!-- Placeholder da Imagem -->
                            <div class="bg-light rounded mb-3 flex-grow-1 d-flex align-items-center justify-content-center text-muted" style="min-height: 180px;">
                                <i class="bi bi-image text-secondary opacity-50 display-1"></i>
                            </div>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center border-top pt-3">
                                <div>
                                    <small class="text-muted d-block">Valor da hora</small>
                                    <h4 class="text-success fw-bold mb-0">R$ {{ number_format($arena->preco_hora, 2, ',', '.') }}</h4>
                                </div>
                                <!-- Passa o ID da arena na URL para o próximo passo -->
                                <a href="/agendamento/data?arena_id={{ $arena->id }}" class="btn btn-verde px-4 py-2 fw-bold rounded-pill shadow-sm">
                                    Selecionar <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3 text-muted" style="font-size: 4rem;"><i class="bi bi-emoji-frown"></i></div>
                        <h4 class="fw-bold text-dark">Nenhuma quadra disponível no momento</h4>
                        <p class="text-muted">As arenas estão sendo cadastradas pelos administradores. Volte em breve!</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
@endsection