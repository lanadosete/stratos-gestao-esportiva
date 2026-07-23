@extends('layouts.app')

@section('conteudo')
@php
    $esportesAtivos = $arena->esportes->where('ativo', true)->values();
    $precosMap = $arena->precosTurno->groupBy('esporte')->map(function ($itens) {
        return $itens->pluck('valor_hora', 'turno');
    });
    $precoMinimo = $arena->precosTurno->min('valor_hora');

    $dataInicial = now()->format('Y-m-d');
    $funcionamentoInicial = $arena->complexo->funcionamento
        ->where('dia_semana', now()->dayOfWeek)
        ->where('ativo', true)
        ->first();

    $horariosIniciais = [];
    if ($funcionamentoInicial) {
        $abertura = (int) substr($funcionamentoInicial->hora_abertura, 0, 2);
        $fechamento = (int) substr($funcionamentoInicial->hora_fechamento, 0, 2);

        $ocupados = \App\Models\Reserva::where('arena_id', $arena->id)
            ->where('data_reserva', $dataInicial)
            ->where('status', '!=', 'cancelado')
            ->pluck('horario')
            ->flatMap(fn($h) => array_map('trim', explode('|', $h)))
            ->toArray();

        $horaAtual = now()->hour;

        for ($h = $abertura; $h < $fechamento; $h++) {
            $hora = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            $horariosIniciais[] = [
                'hora' => $hora,
                'ocupado' => in_array($hora, $ocupados),
                'passado' => $h <= $horaAtual, // bloco inicial é sempre para o dia de hoje
            ];
        }
    }
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
<style>
    .flatpickr-day.selected { background: #28a745 !important; border-color: #28a745 !important; }
</style>

<div class="bg-gradient-stratos" style="min-height: 100vh;">
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="d-flex flex-wrap gap-2 mb-5 mt-3">
                <a href="/agendamento" class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal text-decoration-none"><i class="bi bi-check2 me-1"></i> 1. Complexo</a>
                <a href="/agendamento/arenas?complexo_id={{ $arena->complexo_id }}" class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal text-decoration-none"><i class="bi bi-check2 me-1"></i> 2. Quadra</a>
                <span class="badge bg-success px-4 py-2 rounded-pill fs-6 fw-normal shadow-sm">3. Data e Hora</span>
                <span class="badge bg-light text-secondary border px-4 py-2 rounded-pill fs-6 fw-normal">4. Pagamento</span>
            </div>

            <div class="mb-4">
                <h3 class="fw-bold mb-1">Configure o seu Jogo</h3>
                <p class="text-muted">Selecione o esporte, a data e o horário para jogar na <strong class="text-success">{{ $arena->nome }}</strong>.</p>
            </div>

            <div class="card bg-light p-4 shadow-sm border-0 mb-4 rounded-3 d-flex flex-row align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h5 class="mb-1 fw-bold text-dark">{{ $arena->nome }}</h5>
                    <small class="text-muted">O valor varia por esporte e turno</small>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block">A partir de</small>
                    <h3 class="text-success fw-bold mb-0">R$ {{ number_format($precoMinimo ?? 0, 2, ',', '.') }}</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5 mb-4 mb-md-0">
                    <div class="card card-stratos p-4 shadow-sm border-0 h-100">
                        <h5 class="mb-4 fw-bold">1. Escolha o dia</h5>
                        <input type="text" id="data-reserva" class="form-control form-control-lg border-0 bg-light text-center fw-bold text-success shadow-none" style="border-radius: 8px; cursor: pointer;" placeholder="Clique para selecionar o dia">
                        <hr class="my-4 text-muted">
                        <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i> Dias em cinza representam datas passadas. Se o complexo não funcionar no dia escolhido, os horários não ficam disponíveis.</p>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="card card-stratos p-4 shadow-sm border-0">

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">2. Escolha a modalidade</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($esportesAtivos as $i => $esporte)
                                    <input type="radio" class="btn-check" name="esporte" id="esporte-{{ $i }}" value="{{ $esporte->nome }}" autocomplete="off" {{ $i === 0 ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold" for="esporte-{{ $i }}">{{ $esporte->nome }}</label>
                                @endforeach
                            </div>
                        </div>

                        <h5 class="mb-4 fw-bold">3. Escolha os horários</h5>

                        <div id="aviso-fechado" class="alert alert-secondary small d-none">
                            <i class="bi bi-info-circle me-1"></i> O complexo não funciona neste dia. Escolha outra data.
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-4" id="grade-horarios"></div>

                        <div class="bg-success bg-opacity-10 rounded p-4 mb-4 d-none" id="box-resumo">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <span class="text-success small fw-bold text-uppercase d-block mb-1">Você selecionou:</span>
                                    <strong id="texto-horarios" class="text-dark fs-5">-</strong>
                                </div>
                                <div class="text-end border-start border-success border-opacity-25 ps-4">
                                    <span class="text-success small fw-bold text-uppercase d-block mb-1">Total Estimado</span>
                                    <h4 class="text-success fw-bold mb-0" id="texto-total">R$ 0,00</h4>
                                </div>
                            </div>
                            <div id="aviso-preco" class="alert alert-warning small mt-3 mb-0 border-0 d-none">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Preço não configurado para essa modalidade em algum dos horários selecionados.
                            </div>
                        </div>

                        <a href="#" class="btn btn-verde w-100 py-3 fw-bold rounded-pill shadow-sm disabled" id="btn-avancar">
                            Confirmar Horários e Ir para Pagamento <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/pt.js"></script>

<script>
    const arenaId = {{ $arena->id }};
    const precosPorEsporte = @json($precosMap);
    const horariosIniciais = @json($horariosIniciais);
    const abertoInicial = {{ $funcionamentoInicial ? 'true' : 'false' }};

    function detectarTurno(hora) {
        const h = parseInt(hora.substring(0, 2), 10);
        if (h < 12) return 'Manhã';
        if (h < 18) return 'Tarde';
        return 'Noite';
    }

    function esporteAtual() {
        const el = document.querySelector('input[name="esporte"]:checked');
        return el ? el.value : null;
    }

    function precoHora(hora) {
        const esporte = esporteAtual();
        const turno = detectarTurno(hora);
        if (!esporte || !precosPorEsporte[esporte]) return null;
        const preco = precosPorEsporte[esporte][turno];
        return preco !== undefined ? parseFloat(preco) : null;
    }

    function renderHorarios(horarios, aberto) {
        const container = document.getElementById('grade-horarios');
        const avisoFechado = document.getElementById('aviso-fechado');
        container.innerHTML = '';

        if (!aberto || horarios.length === 0) {
            container.classList.add('d-none');
            avisoFechado.classList.remove('d-none');
            atualizarResumo();
            return;
        }

        container.classList.remove('d-none');
        avisoFechado.classList.add('d-none');

        horarios.forEach(function (item, index) {
            const id = 'h-' + index;
            const indisponivel = item.ocupado || item.passado;

            const input = document.createElement('input');
            input.type = 'checkbox';
            input.className = 'btn-check horario-checkbox';
            input.id = id;
            input.value = item.hora;
            input.autocomplete = 'off';
            input.disabled = indisponivel;
            input.addEventListener('change', atualizarResumo);

            const label = document.createElement('label');
            label.className = indisponivel
                ? 'btn btn-outline-secondary rounded p-3 text-center opacity-50'
                : 'btn btn-outline-success rounded p-3 text-center fw-semibold';
            label.style.minWidth = '100px';
            label.htmlFor = id;
            if (item.ocupado) {
                label.innerHTML = item.hora + '<br><small class="fw-normal">Ocupado</small>';
            } else if (item.passado) {
                label.innerHTML = item.hora + '<br><small class="fw-normal">Horário passado</small>';
            } else {
                label.innerHTML = item.hora;
            }

            container.appendChild(input);
            container.appendChild(label);
        });

        atualizarResumo();
    }

    function atualizarResumo() {
        const checkboxes = document.querySelectorAll('.horario-checkbox:checked');
        const boxResumo = document.getElementById('box-resumo');
        const textoHorarios = document.getElementById('texto-horarios');
        const textoTotal = document.getElementById('texto-total');
        const btnAvancar = document.getElementById('btn-avancar');
        const avisoPreco = document.getElementById('aviso-preco');

        const dataSelecionada = document.getElementById('data-reserva').value;
        const esporte = esporteAtual();

        let horariosSelecionados = [];
        let total = 0;
        let precoIndisponivel = false;

        checkboxes.forEach(function (cb) {
            horariosSelecionados.push(cb.value);
            const preco = precoHora(cb.value);
            if (preco === null) {
                precoIndisponivel = true;
            } else {
                total += preco;
            }
        });

        if (horariosSelecionados.length > 0 && esporte) {
            boxResumo.classList.remove('d-none');
            const horariosStr = horariosSelecionados.join(' | ');
            textoHorarios.textContent = horariosStr;
            textoTotal.textContent = 'R$ ' + total.toFixed(2).replace('.', ',');

            if (precoIndisponivel) {
                avisoPreco.classList.remove('d-none');
                btnAvancar.classList.add('disabled');
                btnAvancar.href = '#';
            } else {
                avisoPreco.classList.add('d-none');
                btnAvancar.classList.remove('disabled');
                const urlBase = `/agendamento/pagamento?arena_id=${arenaId}`;
                btnAvancar.href = `${urlBase}&data=${dataSelecionada}&horario=${encodeURIComponent(horariosStr)}&esporte=${encodeURIComponent(esporte)}`;
            }
        } else {
            boxResumo.classList.add('d-none');
            btnAvancar.classList.add('disabled');
            btnAvancar.href = '#';
        }
    }

    function carregarHorarios(data) {
        fetch(`/agendamento/horarios-disponiveis?arena_id=${arenaId}&data=${data}`)
            .then(function (r) { return r.json(); })
            .then(function (json) { renderHorarios(json.horarios, json.aberto); })
            .catch(function () { renderHorarios([], false); });
    }

    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#data-reserva", {
            locale: "pt",
            minDate: "today",
            defaultDate: "today",
            dateFormat: "Y-m-d",
            inline: true,
            onChange: function (selectedDates, dateStr) {
                carregarHorarios(dateStr);
            }
        });

        document.querySelectorAll('input[name="esporte"]').forEach(function (radio) {
            radio.addEventListener('change', atualizarResumo);
        });

        renderHorarios(horariosIniciais, abertoInicial);
    });
</script>
@endsection
