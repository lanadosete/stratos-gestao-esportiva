@extends('layouts.app')

@section('conteudo')
@php
    $arenaId = request('arena_id');
    $arena = \App\Models\Arena::with('complexo')->find($arenaId);

    if (!$arena) {
        echo "<script>window.location.href = '/agendamento';</script>";
        exit;
    }

    // Captura os dados da requisição
    $dataReserva = request('data') ?? request('data_reserva') ?? date('Y-m-d');
    $horario = request('horario') ?? request('hora') ?? '18:00';
    // horario pode vir como "18:00 | 19:00" (múltiplos horários juntos) — separa em array
    $horariosArray = array_values(array_filter(array_map('trim', explode('|', $horario))));
    // Se o esporte não vier, usamos o tipo_esporte padrão da quadra
    $esporte = request('esporte') ?? $arena->tipo_esporte;

    // Se acabamos de finalizar o pagamento, carregamos a reserva para exibir a modal de confirmação
    // (o id só chega aqui via flash de sessão, criado no mesmo request que gerou a reserva —
    // inclusive quando um admin/funcionário reserva em nome de um cliente, então não faz sentido
    // exigir que o user_id da reserva seja o do usuário logado)
    $reservaConfirmada = null;
    if (session('reserva_confirmada')) {
        $reservaConfirmada = \App\Models\Reserva::with('arena.complexo')->find(session('reserva_confirmada'));
    }
@endphp

<div class="bg-gradient-stratos" style="min-height: 100vh;">
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="d-flex flex-wrap gap-2 mb-5 mt-3">
                <a href="/agendamento" class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal text-decoration-none"><i class="bi bi-check2 me-1"></i> 1. Complexo</a>
                <a href="/agendamento/arenas?complexo_id={{ $arena->complexo_id }}" class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal text-decoration-none"><i class="bi bi-check2 me-1"></i> 2. Quadra</a>
                <a href="/agendamento/data?arena_id={{ $arena->id }}" class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal text-decoration-none"><i class="bi bi-check2 me-1"></i> 3. Data e Hora</a>
                @if ($reservaConfirmada)
                    <span class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal"><i class="bi bi-check2 me-1"></i> 4. Pagamento</span>
                @else
                    <span class="badge bg-success px-4 py-2 rounded-pill fs-6 fw-normal shadow-sm">4. Pagamento</span>
                @endif
            </div>

            <div class="row">
                <div class="col-md-8 mb-4 mb-md-0">
                    <h3 class="mb-1 fw-bold">Finalizar Reserva</h3>
                    <p class="text-muted mb-4">Escolha a forma de pagamento para garantir seu horário.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm border-0 mb-4 rounded-3">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    @if ($reservaConfirmada)
                        <div class="card card-stratos p-4 shadow-sm border-0 mb-4 rounded-3 text-center">
                            <i class="bi bi-check-circle-fill text-success display-4 mb-3 d-block"></i>
                            <h5 class="fw-bold mb-1">Reserva confirmada!</h5>
                            <p class="text-muted mb-0">Confira os detalhes na janela de confirmação.</p>
                        </div>
                    @else
                    <form action="/agendamento/finalizar" method="POST">
                        @csrf
                        <input type="hidden" name="arena_id" value="{{ $arena->id }}">
                        <input type="hidden" name="data_reserva" value="{{ $dataReserva }}">

                        <!-- CORREÇÃO 1: Enviar como array para o controller (um input por horário) -->
                        @foreach ($horariosArray as $h)
                            <input type="hidden" name="horarios[]" value="{{ $h }}">
                        @endforeach

                        <!-- CORREÇÃO 2: Enviar o esporte -->
                        <input type="hidden" name="esporte" value="{{ $esporte }}">

                        @if(in_array(Auth::user()->tipo_conta, ['admin', 'funcionario']))
                            <div class="card card-stratos p-4 shadow-sm border-0 mb-4 rounded-3">
                                <h5 class="mb-3 fw-bold">Esta reserva é para</h5>

                                <div class="btn-group w-100 mb-3" role="group">
                                    <input type="radio" class="btn-check" name="modo-cliente" id="modo-cadastrado" checked onchange="toggleModoCliente()">
                                    <label class="btn btn-outline-success" for="modo-cadastrado">Cliente cadastrado</label>

                                    <input type="radio" class="btn-check" name="modo-cliente" id="modo-avulso" onchange="toggleModoCliente()">
                                    <label class="btn btn-outline-success" for="modo-avulso">Sem cadastro</label>
                                </div>

                                <div id="bloco-cliente-cadastrado">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Buscar cliente (nome ou e-mail)</label>
                                    <div class="position-relative">
                                        <input type="text" id="busca-cliente" class="form-control form-control-lg bg-light border-0" placeholder="Digite para buscar..." autocomplete="off">
                                        <div id="resultados-cliente" class="list-group position-absolute w-100 mt-1 shadow-sm rounded-3 overflow-auto" style="z-index: 10; max-height: 220px; display: none;"></div>
                                    </div>
                                    <div id="cliente-selecionado" class="alert alert-success d-none mt-2 mb-0 py-2 px-3 d-flex justify-content-between align-items-center">
                                        <span id="cliente-selecionado-nome" class="small fw-semibold"></span>
                                        <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="limparClienteSelecionado()">trocar</button>
                                    </div>
                                    <input type="hidden" name="cliente_id" id="input-cliente-id">
                                </div>

                                <div id="bloco-sem-cadastro" class="d-none">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Reservado para o(a):</label>
                                    <input type="text" name="reservado_para" id="input-reservado-para" class="form-control form-control-lg bg-light border-0" placeholder="Nome do cliente" disabled>
                                </div>
                            </div>
                        @endif

                        <div class="card card-stratos p-4 shadow-sm border-0 mb-4 rounded-3">
                            <h5 class="mb-3 fw-bold">Forma de pagamento</h5>

                            <!-- O valor_total não é estritamente necessário no form, pois o Controller recalcula,
                                 mas mantive para não quebrar sua lógica de front, se precisar -->
                            <input type="hidden" name="metodo_pagamento" id="input-metodo" value="pix">

                            <div class="list-group">
                                <label class="list-group-item p-3 d-flex align-items-center" style="border-radius: 8px 8px 0 0; cursor: pointer;">
                                    <input type="radio" name="pagamento" value="pix" class="form-check-input me-3 mt-0 fs-5" checked onchange="updateButton()">
                                    <div>
                                        <strong class="d-block"><i class="bi bi-lightning-charge-fill text-success me-1"></i> Pix</strong>
                                        <small class="text-muted">Aprovação instantânea.</small>
                                    </div>
                                </label>

                                <label class="list-group-item p-3 d-flex align-items-center" style="border-radius: 0 0 8px 8px; cursor: pointer;">
                                    <input type="radio" name="pagamento" value="local" class="form-check-input me-3 mt-0 fs-5" onchange="updateButton()">
                                    <div>
                                        <strong class="d-block"><i class="bi bi-shop me-1"></i> Pagar no Local</strong>
                                        <small class="text-muted">Pague na recepção do complexo.</small>
                                    </div>
                                </label>
                            </div>

                            <button type="submit" id="btn-pagar" class="btn btn-verde mt-4 w-100 py-3 fw-bold rounded-pill shadow-sm fs-5">
                                Finalizar Reserva
                            </button>
                        </div>
                    </form>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="card card-stratos p-4 shadow-sm border-0 rounded-3">
                        <h5 class="mb-3 fw-bold">Resumo da Reserva</h5>
                        <p class="mb-1 fw-bold fs-5">{{ $arena->nome }}</p>
                        <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill text-success me-1"></i> {{ $arena->complexo->nome ?? 'Complexo Stratos' }}</p>
                        <hr class="text-muted">

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Data</span>
                            <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($dataReserva)->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Horário</span>
                            <span class="fw-semibold text-dark">{{ $horario }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Modalidade</span>
                            <span class="fw-semibold text-dark">{{ $esporte }}</span>
                        </div>
                        <hr class="text-muted">
                        @if ($reservaConfirmada)
                            <div class="alert alert-success small mt-4 mb-0 border-0 bg-success bg-opacity-10 text-success fw-semibold">
                                <i class="bi bi-check-circle-fill me-1"></i> Reserva confirmada com sucesso!
                            </div>
                        @else
                            <div id="msg-garantia" class="alert alert-success small mt-4 mb-0 border-0 bg-success bg-opacity-10 text-success fw-semibold">
                                <i class="bi bi-shield-check me-1"></i> Reserva garantida após o pagamento.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    function updateButton() {
        const btn = document.getElementById('btn-pagar');
        const msg = document.getElementById('msg-garantia');
        const inputMetodo = document.getElementById('input-metodo');
        const isPix = document.querySelector('input[name="pagamento"]:checked').value === 'pix';

        inputMetodo.value = isPix ? 'pix' : 'local';

        if (isPix) {
            btn.innerHTML = 'Finalizar com Pix';
            btn.className = "btn btn-verde mt-4 w-100 py-3 fw-bold rounded-pill shadow-sm fs-5";
            msg.innerHTML = '<i class="bi bi-shield-check me-1"></i> Reserva garantida após o Pix.';
        } else {
            btn.innerHTML = 'Confirmar Reserva (Pagar no Local)';
            btn.className = "btn btn-dark mt-4 w-100 py-3 fw-bold rounded-pill shadow-sm fs-5";
            msg.innerHTML = '<i class="bi bi-info-circle me-1"></i> O pagamento será realizado na recepção.';
        }
    }

    function toggleModoCliente() {
        const cadastrado = document.getElementById('modo-cadastrado').checked;
        const inputReservadoPara = document.getElementById('input-reservado-para');

        document.getElementById('bloco-cliente-cadastrado').classList.toggle('d-none', !cadastrado);
        document.getElementById('bloco-sem-cadastro').classList.toggle('d-none', cadastrado);
        inputReservadoPara.disabled = cadastrado;

        if (cadastrado) {
            inputReservadoPara.value = '';
        } else {
            limparClienteSelecionado();
            document.getElementById('busca-cliente').value = '';
        }
    }

    function limparClienteSelecionado() {
        document.getElementById('input-cliente-id').value = '';
        document.getElementById('cliente-selecionado').classList.add('d-none');
    }

    const buscaClienteInput = document.getElementById('busca-cliente');
    if (buscaClienteInput) {
        let buscaTimeout;

        buscaClienteInput.addEventListener('input', function () {
            clearTimeout(buscaTimeout);
            const termo = this.value.trim();
            const resultados = document.getElementById('resultados-cliente');

            document.getElementById('input-cliente-id').value = '';
            document.getElementById('cliente-selecionado').classList.add('d-none');

            if (termo.length < 2) {
                resultados.style.display = 'none';
                resultados.innerHTML = '';
                return;
            }

            buscaTimeout = setTimeout(function () {
                fetch('/agendamento/clientes-busca?q=' + encodeURIComponent(termo))
                    .then(function (r) { return r.json(); })
                    .then(function (clientes) {
                        resultados.innerHTML = '';

                        if (clientes.length === 0) {
                            resultados.style.display = 'none';
                            return;
                        }

                        clientes.forEach(function (c) {
                            const item = document.createElement('button');
                            item.type = 'button';
                            item.className = 'list-group-item list-group-item-action';

                            const strong = document.createElement('strong');
                            strong.textContent = c.name;
                            const small = document.createElement('small');
                            small.className = 'text-muted d-block';
                            small.textContent = c.email;

                            item.appendChild(strong);
                            item.appendChild(small);

                            item.addEventListener('click', function () {
                                document.getElementById('input-cliente-id').value = c.id;
                                document.getElementById('cliente-selecionado-nome').textContent = c.name + ' (' + c.email + ')';
                                document.getElementById('cliente-selecionado').classList.remove('d-none');
                                resultados.style.display = 'none';
                                resultados.innerHTML = '';
                                buscaClienteInput.value = '';
                            });

                            resultados.appendChild(item);
                        });

                        resultados.style.display = 'block';
                    })
                    .catch(function () { resultados.style.display = 'none'; });
            }, 300);
        });
    }
</script>

@php
    $souStaff = in_array(Auth::user()->tipo_conta, ['admin', 'funcionario']);
    $linkAgendamentos = $souStaff ? '/recepcao' : '/cliente/agendamentos';
    $labelAgendamentos = $souStaff ? 'Ver Agenda do Dia' : 'Ver Meus Agendamentos';
@endphp

@if ($reservaConfirmada)
@php
    $ehPix = $reservaConfirmada->metodo_pagamento === 'pix';
@endphp
<!-- Modal de confirmação -->
<div class="modal fade" id="modalConfirmacao" tabindex="-1" aria-labelledby="modalConfirmacaoLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 text-center p-3">
            <div class="modal-body p-4">
                <i class="bi bi-check-circle-fill text-success display-1 mb-3 d-block"></i>
                <h4 class="fw-bold mb-2" id="modalConfirmacaoLabel">
                    {{ $ehPix ? 'Horário marcado e pagamento realizado!' : 'Horário confirmado!' }}
                </h4>
                <p class="text-muted mb-1">
                    A reserva {{ $reservaConfirmada->reservado_para ? 'para ' . $reservaConfirmada->reservado_para : 'na sua conta' }}
                    na <strong>{{ $reservaConfirmada->arena->nome ?? 'quadra' }}</strong>
                    {{ $ehPix ? 'foi confirmada e o pagamento via Pix foi realizado com sucesso.' : 'foi confirmada. O pagamento deverá ser feito na recepção do complexo.' }}
                </p>
                <p class="text-muted mb-4">
                    {{ \Carbon\Carbon::parse($reservaConfirmada->data_reserva)->format('d/m/Y') }} às {{ $reservaConfirmada->horario }}
                </p>

                <div class="d-grid gap-2">
                    <a href="/agendamento" class="btn btn-verde py-3 fw-bold rounded-pill shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Realizar Novo Agendamento
                    </a>

                    <a href="{{ $linkAgendamentos }}" class="btn btn-outline-dark py-3 fw-bold rounded-pill">
                        <i class="bi bi-calendar-check me-2"></i> {{ $labelAgendamentos }}
                    </a>

                    <a href="/" class="btn btn-light py-3 fw-bold rounded-pill">
                        <i class="bi bi-house me-2"></i> Voltar para a Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('modalConfirmacao'));
        modal.show();
    });
</script>
@endif
@endsection
