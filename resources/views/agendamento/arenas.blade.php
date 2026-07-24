@extends('layouts.app')

@section('conteudo')
<div class="bg-gradient-stratos" style="min-height: 100vh;">
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <!-- Barra de Progresso -->
            <div class="d-flex flex-wrap gap-2 mb-5 mt-3">
                <span class="badge bg-success px-4 py-2 rounded-pill fs-6 fw-normal shadow-sm">1. Complexo</span>
                <span class="badge bg-light text-secondary border px-4 py-2 rounded-pill fs-6 fw-normal">2. Quadra</span>
                <span class="badge bg-light text-secondary border px-4 py-2 rounded-pill fs-6 fw-normal">3. Data e Hora</span>
                <span class="badge bg-light text-secondary border px-4 py-2 rounded-pill fs-6 fw-normal">4. Pagamento</span>
            </div>

            <div class="mb-4">
                <h3 class="fw-bold mb-1">Escolha um Complexo Esportivo</h3>
                <p class="text-muted">Selecione o local onde você quer jogar.</p>
            </div>

            <form method="GET" action="/agendamento" class="mb-4">
                <div class="input-group input-group-lg shadow-sm rounded-3">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="busca" value="{{ $busca }}" class="form-control border-0" placeholder="Buscar por nome ou endereço...">
                    @if($busca)
                        <a href="/agendamento" class="btn btn-light border-0"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>

            <div class="row g-4 pb-5">
                @forelse($complexos as $complexo)
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-stratos p-4 shadow-sm border-0 h-100 d-flex flex-column">
                            <div class="mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle mb-3" style="width: 55px; height: 55px;">
                                    <i class="bi bi-building fs-4"></i>
                                </div>
                                <h5 class="fw-bold mb-1 text-dark">{{ $complexo->nome }}</h5>
                                <small class="text-muted d-block mb-1"><i class="bi bi-geo-alt-fill text-success me-1"></i> {{ $complexo->endereco }}</small>
                                <small class="text-muted"><i class="bi bi-grid-3x3-gap-fill text-success me-1"></i> {{ $complexo->arenas_count }} {{ $complexo->arenas_count == 1 ? 'quadra' : 'quadras' }}</small>
                            </div>
                            <a href="/agendamento/arenas?complexo_id={{ $complexo->id }}" class="btn btn-verde w-100 py-2 fw-bold rounded-pill shadow-sm mt-auto">
                                Ver Quadras <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3 text-muted" style="font-size: 4rem;"><i class="bi bi-emoji-frown"></i></div>
                        <h4 class="fw-bold text-dark">Nenhum complexo encontrado</h4>
                        <p class="text-muted">Tente buscar por outro nome ou endereço.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
</div>
@endsection
