@extends('layouts.app')

@section('conteudo')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">Configuração da {{ $arena->nome }}</h3>
                    <p class="text-muted mb-0">Defina funcionamento, esportes e preços por turno com a identidade visual do Stratos.</p>
                </div>
                <a href="/admin/arenas" class="btn btn-outline-success rounded-pill px-4 fw-bold">Voltar às Arenas</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card card-stratos border-0 shadow-sm rounded-4 p-4 h-100">
                        <h5 class="fw-bold text-success mb-3"><i class="bi bi-calendar3 me-2"></i> Dias de funcionamento</h5>
                        <form action="/admin/arena/{{ $arena->id }}/configuracoes/funcionamento" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Dia da semana</label>
                                <select name="dia_semana" class="form-select rounded-3">
                                    <option value="0">Domingo</option>
                                    <option value="1">Segunda</option>
                                    <option value="2">Terça</option>
                                    <option value="3">Quarta</option>
                                    <option value="4">Quinta</option>
                                    <option value="5">Sexta</option>
                                    <option value="6">Sábado</option>
                                </select>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-muted">Abertura</label>
                                    <input type="time" name="hora_abertura" class="form-control rounded-3" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-muted">Fechamento</label>
                                    <input type="time" name="hora_fechamento" class="form-control rounded-3" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-verde w-100 mt-4 rounded-pill fw-bold">Salvar dia</button>
                        </form>

                        <div class="mt-4">
                            @forelse($arena->funcionamento as $func)
                                <div class="d-flex justify-content-between align-items-center border-top py-2">
                                    <span class="fw-semibold text-dark">{{ ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'][$func->dia_semana] }}</span>
                                    <span class="text-muted small">{{ substr($func->hora_abertura, 0, 5) }} - {{ substr($func->hora_fechamento, 0, 5) }}</span>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">Nenhum dia configurado ainda.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-stratos border-0 shadow-sm rounded-4 p-4 h-100">
                        <h5 class="fw-bold text-success mb-3"><i class="bi bi-trophy me-2"></i> Esportes da arena</h5>
                        <form action="/admin/arena/{{ $arena->id }}/configuracoes/esporte" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Quais esportes vão funcionar?</label>
                                <div class="border rounded-3 p-3 bg-light">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="nomes[]" value="Beach Vôlei" id="esporte-beach-volei" {{ $arena->esportes->contains('nome', 'Beach Vôlei') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="esporte-beach-volei">Beach Vôlei</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="nomes[]" value="Beach Tênis" id="esporte-beach-tenis" {{ $arena->esportes->contains('nome', 'Beach Tênis') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="esporte-beach-tenis">Beach Tênis</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="nomes[]" value="Futevôlei" id="esporte-futevolei" {{ $arena->esportes->contains('nome', 'Futevôlei') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="esporte-futevolei">Futevôlei</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-verde w-100 rounded-pill fw-bold">Salvar esportes</button>
                        </form>

                        <div class="mt-4">
                            @forelse($arena->esportes as $esporte)
                                <span class="badge bg-success bg-opacity-10 text-success border-0 px-3 py-2 me-2 mb-2">{{ $esporte->nome }}</span>
                            @empty
                                <p class="text-muted small mb-0">Nenhum esporte cadastrado.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-stratos border-0 shadow-sm rounded-4 p-4 h-100">
                        <h5 class="fw-bold text-success mb-3"><i class="bi bi-currency-dollar me-2"></i> Preços por turno</h5>
                        <form action="/admin/arena/{{ $arena->id }}/configuracoes/preco" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Esporte</label>
                                <select name="esporte" class="form-select rounded-3">
                                    @foreach($arena->esportes as $esporte)
                                        <option value="{{ $esporte->nome }}">{{ $esporte->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Turno</label>
                                <select name="turno" class="form-select rounded-3">
                                    <option value="Manhã">Manhã</option>
                                    <option value="Tarde">Tarde</option>
                                    <option value="Noite">Noite</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Valor por hora</label>
                                <input type="number" step="0.01" name="valor_hora" class="form-control rounded-3" placeholder="0.00" required>
                            </div>
                            <button type="submit" class="btn btn-verde w-100 rounded-pill fw-bold">Salvar preço</button>
                        </form>

                        <div class="mt-4">
                            @forelse($arena->precosTurno as $preco)
                                <div class="d-flex justify-content-between align-items-center border-top py-2">
                                    <span class="fw-semibold text-dark">{{ $preco->esporte }} · {{ $preco->turno }}</span>
                                    <span class="text-success fw-bold">R$ {{ number_format($preco->valor_hora, 2, ',', '.') }}</span>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">Nenhum preço configurado.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
