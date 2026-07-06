@extends('layouts.app')

@section('conteudo')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-day.selected { background: #28a745 !important; border-color: #28a745 !important; }
    
    /* Estilos minimalistas para os botões de Modalidade */
    .esporte-radio { display: none; }
    .esporte-label {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background-color: #fff;
        color: #6c757d;
        width: 100%;
        display: block;
    }
    .esporte-label:hover { border-color: #adb5bd; }
    .esporte-radio:checked + .esporte-label {
        border-color: #28a745;
        background-color: #f0fdf4; /* Fundo verde clarinho */
        color: #28a745;
        font-weight: bold;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.1);
    }
</style>

<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <div class="d-flex flex-wrap gap-2 mb-5">
                <span class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal">✓ Arena</span>
                <span class="badge bg-success px-4 py-2 rounded-pill fs-6 fw-normal shadow-sm">2. Data e Hora</span>
                <span class="badge bg-light text-secondary border px-4 py-2 rounded-pill fs-6 fw-normal">3. Pagamento</span>
            </div>

            <div class="mb-4">
                <h3 class="fw-bold mb-1">Configure o seu Jogo</h3>
                <p class="text-muted">Selecione o desporto, data e horário para a <strong class="text-dark">Arena Praia Sul</strong>.</p>
            </div>

            <div class="card card-stratos p-4 shadow-sm border-0 mb-4">
                <h5 class="mb-3">1. O que você vai jogar?</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="radio" name="modalidade" id="volei" class="esporte-radio" value="100" onchange="atualizarResumo()" checked>
                        <label for="volei" class="esporte-label">
                            <span class="fs-4 d-block mb-1">🏐</span>
                            Vôlei de Praia
                            <small class="d-block text-muted fw-normal mt-1">R$ 100/hora</small>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <input type="radio" name="modalidade" id="beachtennis" class="esporte-radio" value="150" onchange="atualizarResumo()">
                        <label for="beachtennis" class="esporte-label">
                            <span class="fs-4 d-block mb-1">🎾</span>
                            Beach Tennis
                            <small class="d-block text-muted fw-normal mt-1">R$ 150/hora</small>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <input type="radio" name="modalidade" id="futevolei" class="esporte-radio" value="120" onchange="atualizarResumo()">
                        <label for="futevolei" class="esporte-label">
                            <span class="fs-4 d-block mb-1">⚽</span>
                            Futevôlei
                            <small class="d-block text-muted fw-normal mt-1">R$ 120/hora</small>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5 mb-4 mb-md-0">
                    <div class="card card-stratos p-4 shadow-sm border-0 h-100">
                        <h5 class="mb-4">2. Escolha o dia</h5>
                        <input type="text" id="data-reserva" class="form-control form-control-lg border-0 bg-light text-center fw-bold text-success shadow-none" style="border-radius: 8px; cursor: pointer;" placeholder="Clique para selecionar">
                        <hr class="my-4 text-muted">
                        <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i> Dias em cinza não estão disponíveis para reserva ou a arena encontra-se fechada.</p>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="card card-stratos p-4 shadow-sm border-0">
                        <h5 class="mb-4">3. Escolha os horários</h5>
                        
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <input type="checkbox" class="btn-check horario-checkbox" name="horarios[]" value="18:00" id="h18" autocomplete="off" onchange="atualizarResumo()">
                            <label class="btn btn-outline-success rounded p-3 text-center" style="min-width: 100px;" for="h18">18:00</label>

                            <input type="checkbox" class="btn-check horario-checkbox" name="horarios[]" value="19:00" id="h19" autocomplete="off" onchange="atualizarResumo()">
                            <label class="btn btn-outline-success rounded p-3 text-center" style="min-width: 100px;" for="h19">19:00</label>

                            <input type="checkbox" class="btn-check" id="h20" autocomplete="off" disabled>
                            <label class="btn btn-outline-secondary rounded p-3 text-center opacity-50" style="min-width: 100px;" for="h20">20:00<br><small>Ocupado</small></label>

                            <input type="checkbox" class="btn-check horario-checkbox" name="horarios[]" value="21:00" id="h21" autocomplete="off" onchange="atualizarResumo()">
                            <label class="btn btn-outline-success rounded p-3 text-center" style="min-width: 100px;" for="h21">21:00</label>
                        </div>

                        <div class="bg-success bg-opacity-10 rounded p-4 mb-4 d-none" id="box-resumo">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-success small fw-bold text-uppercase d-block mb-1" id="texto-esporte">Vôlei de Praia</span>
                                    <strong id="texto-horarios" class="text-dark fs-5">-</strong>
                                </div>
                                <div class="text-end border-start border-success border-opacity-25 ps-4">
                                    <span class="text-success small fw-bold text-uppercase d-block mb-1">Total Estimado</span>
                                    <h4 class="text-success fw-bold mb-0" id="texto-total">R$ 0,00</h4>
                                </div>
                            </div>
                        </div>

                        <a href="/agendamento/pagamento" class="btn btn-verde w-100 py-3 fw-bold disabled" id="btn-avancar">
                            Confirmar Horários e Ir para Pagamento
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>

<script>
    flatpickr("#data-reserva", {
        locale: "pt",
        minDate: "today",
        dateFormat: "d/m/Y",
        inline: true,
        disable: [
            function(date) { return (date.getDay() === 0); }
        ]
    });

    function atualizarResumo() {
        const checkboxes = document.querySelectorAll('.horario-checkbox:checked');
        const esporteSelecionado = document.querySelector('.esporte-radio:checked');
        const boxResumo = document.getElementById('box-resumo');
        const textoHorarios = document.getElementById('texto-horarios');
        const textoTotal = document.getElementById('texto-total');
        const textoEsporte = document.getElementById('texto-esporte');
        const btnAvancar = document.getElementById('btn-avancar');
        
        // Pega o valor da hora direto do botão de esporte selecionado
        const valorHora = parseFloat(esporteSelecionado.value); 
        
        let horariosSelecionados = [];
        checkboxes.forEach(cb => { horariosSelecionados.push(cb.value); });

        if (horariosSelecionados.length > 0) {
            boxResumo.classList.remove('d-none');
            btnAvancar.classList.remove('disabled');
            
            // Atualiza o texto do esporte no resumo verde
            textoEsporte.textContent = esporteSelecionado.nextElementSibling.innerText.split('\n')[1]; 
            textoHorarios.textContent = horariosSelecionados.join(', ');
            
            // Calcula o total com base no esporte escolhido
            const total = horariosSelecionados.length * valorHora;
            textoTotal.textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
        } else {
            boxResumo.classList.add('d-none');
            btnAvancar.classList.add('disabled');
        }
    }
</script>
@endsection