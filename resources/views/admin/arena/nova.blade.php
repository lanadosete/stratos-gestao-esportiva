@extends('layouts.app')

@section('conteudo')
<div class="bg-gradient-stratos" style="min-height: 100vh;">
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card card-stratos p-5 shadow-sm border-0" style="width: 100%; max-width: 600px;">

        <div class="d-flex flex-wrap gap-2 justify-content-center mb-4">
            <span class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal"><i class="bi bi-check2 me-1"></i> 1. Identificação</span>
            <span class="badge bg-success px-4 py-2 rounded-pill fs-6 fw-normal shadow-sm">2. Arena</span>
        </div>

        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle mb-3" style="width: 70px; height: 70px;">
                <i class="bi bi-building fs-1"></i>
            </div>
            <h3 class="text-dark fw-bold mb-1">Bem-vindo(a) ao Stratos!</h3>
            <p class="text-muted">Vamos registrar o seu <strong class="text-success">Espaço Esportivo</strong> para começarmos.</p>
        </div>

        <form action="/admin/arena/salvar" method="POST" novalidate>
            @csrf

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Nome da Arena / Estabelecimento</label>
                <div class="input-group input-group-lg shadow-sm rounded-3">
                    <span class="input-group-text border-0 bg-light"><i class="bi bi-fonts text-muted"></i></span>
                    <input type="text" name="nome" class="form-control border-0 bg-light" value="{{ old('nome') }}" placeholder="Ex: Arena Sol Nascente">
                </div>
                @error('nome')
                    <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Endereço Completo</label>
                <div class="input-group input-group-lg shadow-sm rounded-3">
                    <span class="input-group-text border-0 bg-light"><i class="bi bi-geo-alt text-muted"></i></span>
                    <input type="text" name="endereco" class="form-control border-0 bg-light" value="{{ old('endereco') }}" placeholder="Rua, Número, Bairro, Cidade">
                </div>
                @error('endereco')
                    <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Telefone / WhatsApp</label>
                <div class="input-group input-group-lg shadow-sm rounded-3">
                    <span class="input-group-text border-0 bg-light"><i class="bi bi-telephone text-muted"></i></span>
                    <input type="text" name="telefone" class="form-control border-0 bg-light" value="{{ old('telefone') }}" placeholder="(00) 00000-0000">
                </div>
                @error('telefone')
                    <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="card bg-light border-0 rounded-3 p-3 mb-4">
                <h6 class="fw-bold text-dark mb-2">Dias de funcionamento geral</h6>
                <p class="text-muted small mb-3">Marque os dias em que a arena opera. Os horários definidos abaixo valerão para todos os dias selecionados.</p>
                <div class="mb-3">
                    <div class="d-flex flex-wrap gap-2">
                        @php $dias = [['value' => '0', 'label' => 'Dom'], ['value' => '1', 'label' => 'Seg'], ['value' => '2', 'label' => 'Ter'], ['value' => '3', 'label' => 'Qua'], ['value' => '4', 'label' => 'Qui'], ['value' => '5', 'label' => 'Sex'], ['value' => '6', 'label' => 'Sáb']]; @endphp
                        @foreach($dias as $dia)
                            <label class="border rounded-2 px-2 py-1 bg-light d-flex align-items-center gap-1 small fw-semibold" for="dia-{{ $dia['value'] }}">
                                <input class="form-check-input mt-0" type="checkbox" name="dias_semana[]" value="{{ $dia['value'] }}" id="dia-{{ $dia['value'] }}" {{ (is_array(old('dias_semana')) && in_array($dia['value'], old('dias_semana'))) ? 'checked' : '' }}>
                                <span>{{ $dia['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('dias_semana')
                        <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label small text-muted">Horário de abertura</label>
                        <select name="hora_abertura" class="form-select form-select-sm rounded-3">
                            @for ($i = 0; $i < 24; $i++)
                                @php $hora = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                                <option value="{{ $hora }}" {{ old('hora_abertura') == $hora ? 'selected' : '' }}>{{ $hora }}</option>
                            @endfor
                        </select>
                        @error('hora_abertura')
                            <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label small text-muted">Horário de fechamento</label>
                        <select name="hora_fechamento" class="form-select form-select-sm rounded-3">
                            @for ($i = 0; $i < 24; $i++)
                                @php $hora = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                                <option value="{{ $hora }}" {{ old('hora_fechamento') == $hora ? 'selected' : '' }}>{{ $hora }}</option>
                            @endfor
                        </select>
                        @error('hora_fechamento')
                            <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-verde w-100 py-3 fw-bold rounded-pill shadow-sm mt-2">
                Criar Espaço e Continuar <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </form>

    </div>
</div>
</div>
@endsection