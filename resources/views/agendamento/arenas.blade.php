@extends('layouts.app')

@section('conteudo')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <div class="d-flex flex-wrap gap-2 mb-5">
                <span class="badge bg-success px-4 py-2 rounded-pill fs-6 fw-normal shadow-sm">1. Escolher Arena</span>
                <span class="badge bg-light text-secondary border px-4 py-2 rounded-pill fs-6 fw-normal">2. Data e Hora</span>
                <span class="badge bg-light text-secondary border px-4 py-2 rounded-pill fs-6 fw-normal">3. Pagamento</span>
            </div>

            <div class="mb-4">
                <h3 class="fw-bold mb-1">Selecione uma Arena</h3>
                <p class="text-muted">Escolha o local ideal para o seu jogo.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card card-stratos p-4 shadow-sm border-0 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">Arena Praia Sul</h5>
                                <small class="text-muted">📍 Av. Beira Mar, 1000</small>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success border-0 px-3 py-2">Quadra de Areia</span>
                        </div>
                        <img src="https://images.unsplash.com/photo-1595435934242-4763e0202283?q=80&w=600" class="img-fluid rounded mb-3 flex-grow-1" style="object-fit: cover; max-height: 200px;" alt="Arena Praia Sul">
                        <p class="text-muted small mb-4">Estrutura completa com vestiários, bar e iluminação de LED para jogos noturnos.</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center border-top pt-3">
                            <div>
                                <small class="text-muted d-block">Valor da hora</small>
                                <h4 class="text-success fw-bold mb-0">R$ 120,00</h4>
                            </div>
                            <a href="/agendamento/data" class="btn btn-verde px-5 py-2 fw-bold">Selecionar</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-stratos p-4 shadow-sm border-0 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">Arena Sol Nascente</h5>
                                <small class="text-muted">📍 Rua das Palmeiras, 45</small>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success border-0 px-3 py-2">Grama Sintética</span>
                        </div>
                        <div class="bg-light rounded mb-3 flex-grow-1 d-flex align-items-center justify-content-center text-muted" style="min-height: 200px;">
                            <small>Imagem da Arena</small>
                        </div>
                        <p class="text-muted small mb-4">Excelente cobertura para dias de chuva e grama de alto padrão certificada.</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center border-top pt-3">
                            <div>
                                <small class="text-muted d-block">Valor da hora</small>
                                <h4 class="text-success fw-bold mb-0">R$ 150,00</h4>
                            </div>
                            <a href="/agendamento/data" class="btn btn-verde px-5 py-2 fw-bold">Selecionar</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection