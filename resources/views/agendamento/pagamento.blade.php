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
@endphp

<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="d-flex flex-wrap gap-2 mb-5 mt-3">
                <a href="/agendamento" class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal text-decoration-none"><i class="bi bi-check2 me-1"></i> 1. Quadra</a>
                <a href="/agendamento/data?arena_id={{ $arena->id }}" class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal text-decoration-none"><i class="bi bi-check2 me-1"></i> 2. Data e Hora</a>
                <span class="badge bg-success px-4 py-2 rounded-pill fs-6 fw-normal shadow-sm">3. Pagamento</span>
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

                    <div class="card card-stratos p-4 shadow-sm border-0 mb-4 rounded-3">
                        <h5 class="mb-3 fw-bold">Forma de pagamento</h5>

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
                        </form>
                    </div>
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
                        <div id="msg-garantia" class="alert alert-success small mt-4 mb-0 border-0 bg-success bg-opacity-10 text-success fw-semibold">
                            <i class="bi bi-shield-check me-1"></i> Reserva garantida após o pagamento.
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
</script>
@endsection
