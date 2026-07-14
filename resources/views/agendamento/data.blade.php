@extends('layouts.app')

@section('conteudo')
@php
    // Captura qual quadra o cliente clicou no Passo 1
    $arenaId = request('arena_id');
    $arena = \App\Models\Arena::find($arenaId);
    
    // Se por acaso alguém tentar acessar essa página direto pela URL sem escolher quadra, manda de volta
    if (!$arena) {
        echo "<script>window.location.href = '/agendamento';</script>";
        exit;
    }
@endphp

<!-- CSS do Flatpickr Oficial -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
<style>
    .flatpickr-day.selected { background: #28a745 !important; border-color: #28a745 !important; }
</style>

<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <!-- Barra de Progresso -->
            <div class="d-flex flex-wrap gap-2 mb-5 mt-3">
                <a href="/agendamento" class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal text-decoration-none"><i class="bi bi-check2 me-1"></i> 1. Quadra</a>
                <span class="badge bg-success px-4 py-2 rounded-pill fs-6 fw-normal shadow-sm">2. Data e Hora</span>
                <span class="badge bg-light text-secondary border px-4 py-2 rounded-pill fs-6 fw-normal">3. Pagamento</span>
            </div>

            <div class="mb-4">
                <h3 class="fw-bold mb-1">Configure o seu Jogo</h3>
                <p class="text-muted">Selecione a data e o horário para jogar na <strong class="text-success">{{ $arena->nome }}</strong>.</p>
            </div>

            <!-- Resumo da Modalidade (Gerado dinamicamente com os dados do banco) -->
            <div class="card bg-light p-4 shadow-sm border-0 mb-4 rounded-3 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="badge bg-success text-white mb-2 px-3 py-2 fw-semibold"><i class="bi bi-trophy me-1"></i> {{ $arena->tipo_esporte }}</span>
                    <h5 class="mb-0 fw-bold text-dark">Preço tabelado por hora</h5>
                </div>
                <div class="text-end">
                    <h3 class="text-success fw-bold mb-0">R$ {{ number_format($arena->preco_hora, 2, ',', '.') }}</h3>
                </div>
            </div>

            <div class="row">
                <!-- Coluna do Calendário -->
                <div class="col-md-5 mb-4 mb-md-0">
                    <div class="card card-stratos p-4 shadow-sm border-0 h-100">
                        <h5 class="mb-4 fw-bold">1. Escolha o dia</h5>
                        <input type="text" id="data-reserva" class="form-control form-control-lg border-0 bg-light text-center fw-bold text-success shadow-none" style="border-radius: 8px; cursor: pointer;" placeholder="Clique para selecionar o dia">
                        <hr class="my-4 text-muted">
                        <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i> Dias em cinza representam datas passadas ou indisponíveis no complexo.</p>
                    </div>
                </div>

                <!-- Coluna dos Horários -->
                <div class="col-md-7">
                    <div class="card card-stratos p-4 shadow-sm border-0">
                        <h5 class="mb-4 fw-bold">2. Escolha os horários</h5>
                        
                        <!-- Simulador de horários (No futuro buscaremos do banco também) -->
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <input type="checkbox" class="btn-check horario-checkbox" name="horarios[]" value="18:00" id="h18" autocomplete="off" onchange="atualizarResumo()">
                            <label class="btn btn-outline-success rounded p-3 text-center fw-semibold" style="min-width: 100px;" for="h18">18:00</label>

                            <input type="checkbox" class="btn-check horario-checkbox" name="horarios[]" value="19:00" id="h19" autocomplete="off" onchange="atualizarResumo()">
                            <label class="btn btn-outline-success rounded p-3 text-center fw-semibold" style="min-width: 100px;" for="h19">19:00</label>

                            <input type="checkbox" class="btn-check" id="h20" autocomplete="off" disabled>
                            <label class="btn btn-outline-secondary rounded p-3 text-center opacity-50" style="min-width: 100px;" for="h20">20:00<br><small class="fw-normal">Ocupado</small></label>

                            <input type="checkbox" class="btn-check horario-checkbox" name="horarios[]" value="21:00" id="h21" autocomplete="off" onchange="atualizarResumo()">
                            <label class="btn btn-outline-success rounded p-3 text-center fw-semibold" style="min-width: 100px;" for="h21">21:00</label>
                        </div>

                        <!-- Resumo Financeiro Dinâmico -->
                        <div class="bg-success bg-opacity-10 rounded p-4 mb-4 d-none" id="box-resumo">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-success small fw-bold text-uppercase d-block mb-1">Você selecionou:</span>
                                    <strong id="texto-horarios" class="text-dark fs-5">-</strong>
                                </div>
                                <div class="text-end border-start border-success border-opacity-25 ps-4">
                                    <span class="text-success small fw-bold text-uppercase d-block mb-1">Total Estimado</span>
                                    <h4 class="text-success fw-bold mb-0" id="texto-total">R$ 0,00</h4>
                                </div>
                            </div>
                        </div>

                        <a href="/agendamento/pagamento?arena_id={{ $arena->id }}" class="btn btn-verde w-100 py-3 fw-bold rounded-pill shadow-sm disabled" id="btn-avancar">
                            Confirmar Horários e Ir para Pagamento <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Scripts Flatpickr Oficiais -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/pt.js"></script>

<script>
    // Inicialização do Calendário
    flatpickr("#data-reserva", {
        locale: "pt",
        minDate: "today",
        dateFormat: "d/m/Y",
        inline: true
    });

    // Lógica para calcular o preço em tempo real
    function atualizarResumo() {
        const checkboxes = document.querySelectorAll('.horario-checkbox:checked');
        const boxResumo = document.getElementById('box-resumo');
        const textoHorarios = document.getElementById('texto-horarios');
        const textoTotal = document.getElementById('texto-total');
        const btnAvancar = document.getElementById('btn-avancar');
        
        // Puxa o valor da hora direto do backend (PHP)
        const valorHora = {{ $arena->preco_hora }}; 
        
        let horariosSelecionados = [];
        checkboxes.forEach(cb => { horariosSelecionados.push(cb.value); });

        if (horariosSelecionados.length > 0) {
            boxResumo.classList.remove('d-none');
            btnAvancar.classList.remove('disabled');
            
            textoHorarios.textContent = horariosSelecionados.join(' | ');
            
            // Multiplica o valor da hora pela quantidade de "caixinhas" marcadas
            const total = horariosSelecionados.length * valorHora;
            textoTotal.textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
        } else {
            boxResumo.classList.add('d-none');
            btnAvancar.classList.add('disabled');
        }
    }
</script>
@endsection