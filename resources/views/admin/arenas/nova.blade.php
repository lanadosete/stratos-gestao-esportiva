@extends('layouts.app')

@section('conteudo')
<div class="bg-gradient-stratos" style="min-height: 100vh;">
<div class="container py-5">
<div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <!-- Botão de Voltar -->
            <div class="mb-4">
                <a href="/admin/dashboard" class="text-decoration-none text-muted fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Voltar para o Dashboard
                </a>
            </div>

            <div class="card card-stratos p-5 shadow-sm border-0">
                
                <div class="mb-4">
                    <h4 class="text-success fw-bold mb-1">Adicionar Nova Arena</h4>
                    <p class="text-muted">Cadastre as informações da arena para disponibilizar aos seus clientes.</p>
                </div>

                <!-- Exibição de Erros de Validação -->
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm small py-3 mb-4 rounded-3 text-danger bg-danger bg-opacity-10">
                        <ul class="mb-0 ps-3 fw-semibold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/admin/arenas/salvar" method="POST">
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
                        <label class="form-label small fw-bold text-muted text-uppercase">Nome / Identificação da Arena</label>
                        <input type="text" name="nome" class="form-control form-control-lg bg-light border-0 shadow-sm" placeholder="Ex: Arena 1 - Areia" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Esportes que funcionarão na arena</label>
                        <div class="border rounded-3 p-3 bg-light">
                            @php $esportesFixos = ['Beach Vôlei', 'Beach Tênis', 'Futevôlei']; @endphp
                            @foreach($esportesFixos as $esporte)
                                @php $idEsporte = 'esporte-' . Str::slug($esporte); @endphp
                                <div class="border rounded-3 p-3 mb-3 bg-white">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input esporte-checkbox" type="checkbox" name="esportes[]" value="{{ $esporte }}" id="{{ $idEsporte }}" data-target="turnos-{{ Str::slug($esporte) }}">
                                        <label class="form-check-label fw-semibold" for="{{ $idEsporte }}">{{ $esporte }}</label>
                                    </div>
                                    <div class="row g-2 turnos-container" id="turnos-{{ Str::slug($esporte) }}" hidden>
                                        <div class="col-12">
                                            <div class="small fw-semibold text-success mb-2">Valor por horário</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted">Manhã</label>
                                            <input type="number" step="0.01" name="precos[{{ $esporte }}][Manhã]" class="form-control form-control-sm" placeholder="0,00">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted">Tarde</label>
                                            <input type="number" step="0.01" name="precos[{{ $esporte }}][Tarde]" class="form-control form-control-sm" placeholder="0,00">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted">Noite</label>
                                            <input type="number" step="0.01" name="precos[{{ $esporte }}][Noite]" class="form-control form-control-sm" placeholder="0,00">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-text text-muted small mt-2"><i class="bi bi-info-circle me-1"></i> Marque os esportes que irão funcionar e defina o preço por turno para cada um.</div>
                    </div>

                    <button type="submit" class="btn btn-verde w-100 py-3 fw-bold rounded-pill shadow-sm mt-3">
                        <i class="bi bi-check2-circle me-2"></i> Salvar Nova Arena
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
</div>
@endsection