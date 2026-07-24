@extends('layouts.app')

@section('conteudo')
<div class="bg-gradient-stratos" style="min-height: 100vh;">
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <!-- Barra de Progresso -->
            <div class="d-flex flex-wrap gap-2 mb-5 mt-3">
                @if(Auth::check() && in_array(Auth::user()->tipo_conta, ['admin', 'funcionario']))
                    <span class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal">1. Arena</span>
                @else
                    <a href="/agendamento" class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal text-decoration-none"><i class="bi bi-check2 me-1"></i> 1. Arena</a>
                @endif
                <span class="badge bg-success px-4 py-2 rounded-pill fs-6 fw-normal shadow-sm">2. Quadra</span>
                <span class="badge bg-light text-secondary border px-4 py-2 rounded-pill fs-6 fw-normal">3. Data e Hora</span>
                <span class="badge bg-light text-secondary border px-4 py-2 rounded-pill fs-6 fw-normal">4. Pagamento</span>
            </div>

            <div class="mb-4">
                <h3 class="fw-bold mb-1">{{ $arena->nome }}</h3>
                <p class="text-muted mb-0"><i class="bi bi-geo-alt-fill text-success me-1"></i> {{ $arena->endereco }}</p>
            </div>

            <div class="row g-4 pb-5">
                @forelse($quadras as $quadra)
                    @php
                        $esportesAtivos = $quadra->esportes->where('ativo', true);
                        $menorPreco = $quadra->precosTurno->min('valor_hora');
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-stratos p-4 shadow-sm border-0 h-100 d-flex flex-column">
                            <div class="mb-3">
                                <h5 class="fw-bold mb-2 text-dark">{{ $quadra->nome }}</h5>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($esportesAtivos as $esporte)
                                        <span class="badge bg-success bg-opacity-10 text-success border-0 px-3 py-2 fw-semibold">
                                            {{ $esporte->nome }}
                                        </span>
                                    @empty
                                        <span class="badge bg-light text-secondary border px-3 py-2 fw-semibold">
                                            Sem esporte configurado
                                        </span>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Placeholder da Imagem -->
                            <div class="bg-light rounded mb-3 flex-grow-1 d-flex align-items-center justify-content-center text-muted" style="min-height: 180px;">
                                <i class="bi bi-image text-secondary opacity-50 display-1"></i>
                            </div>

                            <div class="mt-auto d-flex justify-content-between align-items-center border-top pt-3">
                                <div>
                                    <small class="text-muted d-block">Valor da hora</small>
                                    <h4 class="text-success fw-bold mb-0">
                                        {{ $menorPreco ? 'R$ ' . number_format($menorPreco, 2, ',', '.') : 'A definir' }}
                                    </h4>
                                </div>
                                <a href="/agendamento/data?quadra_id={{ $quadra->id }}" class="btn btn-verde px-4 py-2 fw-bold rounded-pill shadow-sm {{ $esportesAtivos->isEmpty() || !$menorPreco ? 'disabled' : '' }}">
                                    Selecionar <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3 text-muted" style="font-size: 4rem;"><i class="bi bi-emoji-frown"></i></div>
                        <h4 class="fw-bold text-dark">Nenhuma quadra cadastrada nesta arena</h4>
                        <p class="text-muted">Volte e escolha outra arena, ou tente novamente mais tarde.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
</div>
@endsection
