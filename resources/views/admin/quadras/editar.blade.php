@extends('layouts.admin')

@section('admin-content')
<div class="row justify-content-center">
        <div class="col-md-8">

            <div class="d-flex align-items-center mb-4">
                <a href="/admin/quadras" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h3 class="fw-bold mb-0">Editar Quadra</h3>
                    <p class="text-muted mb-0">Atualize os dados da sua quadra.</p>
                </div>
            </div>

            <div class="card card-stratos border-0 shadow-sm rounded-4 p-4 p-md-5">

                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm small py-3 mb-4 rounded-3 text-danger bg-danger bg-opacity-10">
                        <ul class="mb-0 ps-3 fw-semibold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/admin/quadras/{{ $quadra->id }}/atualizar" method="POST">
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            document.querySelectorAll('.esporte-checkbox').forEach(function (checkbox) {
                                const targetId = checkbox.getAttribute('data-target');
                                const target = document.getElementById(targetId);

                                const toggle = function () {
                                    if (target) {
                                        target.hidden = !checkbox.checked;
                                    }
                                };

                                checkbox.addEventListener('change', toggle);
                                toggle();
                            });
                        });
                    </script>
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Nome / Identificação da Quadra</label>
                        <input type="text" name="nome" value="{{ old('nome', $quadra->nome) }}" class="form-control form-control-lg bg-light border-0 shadow-sm" placeholder="Ex: Quadra 1 - Areia" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Esportes que funcionarão na quadra <span class="text-danger">*</span></label>
                        <div class="border rounded-3 p-3 bg-light">
                            @php
                                $esportesFixos = ['Beach Vôlei', 'Beach Tênis', 'Futevôlei'];
                                $esportesAtivos = $quadra->esportes->pluck('nome')->toArray();
                                $precosAtuais = $quadra->precosTurno->groupBy('esporte');
                            @endphp
                            @foreach($esportesFixos as $esporte)
                                @php
                                    $idEsporte = 'esporte-' . Str::slug($esporte);
                                    $marcado = in_array($esporte, $esportesAtivos);
                                    $precosEsporte = $precosAtuais->get($esporte, collect())->keyBy('turno');
                                @endphp
                                <div class="border rounded-3 p-3 mb-3 bg-white">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input esporte-checkbox" type="checkbox" name="esportes[]" value="{{ $esporte }}" id="{{ $idEsporte }}" data-target="turnos-{{ Str::slug($esporte) }}" {{ $marcado ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="{{ $idEsporte }}">{{ $esporte }}</label>
                                    </div>
                                    <div class="row g-2 turnos-container" id="turnos-{{ Str::slug($esporte) }}" hidden>
                                        <div class="col-12">
                                            <div class="small fw-semibold text-success mb-2">Valor por horário</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted">Manhã</label>
                                            <input type="number" step="0.01" name="precos[{{ $esporte }}][Manhã]" class="form-control form-control-sm" placeholder="0,00" value="{{ optional($precosEsporte->get('Manhã'))->valor_hora }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted">Tarde</label>
                                            <input type="number" step="0.01" name="precos[{{ $esporte }}][Tarde]" class="form-control form-control-sm" placeholder="0,00" value="{{ optional($precosEsporte->get('Tarde'))->valor_hora }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted">Noite</label>
                                            <input type="number" step="0.01" name="precos[{{ $esporte }}][Noite]" class="form-control form-control-sm" placeholder="0,00" value="{{ optional($precosEsporte->get('Noite'))->valor_hora }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('esportes')
                            <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted small mt-2"><i class="bi bi-info-circle me-1"></i> Marque os esportes que irão funcionar e defina o preço por turno para cada um.</div>
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-verde py-3 fs-5 fw-bold rounded-pill shadow-sm">
                            <i class="bi bi-check2-circle me-2"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
