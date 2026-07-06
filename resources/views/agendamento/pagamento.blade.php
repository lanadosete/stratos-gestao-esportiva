@extends('layouts.app')

@section('conteudo')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <div class="d-flex flex-wrap gap-2 mb-5">
                <span class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal">✓ Arena</span>
                <span class="badge bg-success bg-opacity-25 text-success px-4 py-2 rounded-pill fs-6 fw-normal">✓ Data e Hora</span>
                <span class="badge bg-success px-4 py-2 rounded-pill fs-6 fw-normal shadow-sm">3. Pagamento</span>
            </div>

            <div class="row">
                <div class="col-md-8 mb-4 mb-md-0">
                    <h3 class="mb-4"><strong>Pagamento</strong></h3>
                    <p class="text-muted">Finalize sua reserva escolhendo a forma de pagamento.</p>

                    <div class="card card-stratos p-4 shadow-sm border-0 mb-4">
                        <h5 class="mb-3">Forma de pagamento</h5>
                        <div class="list-group">
                            <label class="list-group-item p-3 d-flex align-items-center" style="border-radius: 8px 8px 0 0; cursor: pointer;">
                                <input type="radio" name="pagamento" value="pix" class="me-3" checked onchange="updateButton()">
                                <div><strong>Pix</strong><br><small class="text-muted">Pagamento instantâneo e seguro.</small></div>
                            </label>
                            <label class="list-group-item p-3 d-flex align-items-center" style="border-radius: 0 0 8px 8px; cursor: pointer;">
                                <input type="radio" name="pagamento" value="local" class="me-3" onchange="updateButton()">
                                <div><strong>Pagar no Local</strong><br><small class="text-muted">Pague em dinheiro ou cartão ao chegar na arena.</small></div>
                            </label>
                        </div>
                        
                        <button type="button" id="btn-pagar" class="btn btn-verde mt-4 w-100 py-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalConfirmacao">
                            Pagar R$ 80,00 via Pix
                        </button>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-stratos p-4 shadow-sm border-0">
                        <h5 class="mb-3">Detalhes da Reserva</h5>
                        <img src="https://images.unsplash.com/photo-1595435934242-4763e0202283?q=80&w=400" class="img-fluid rounded mb-3" alt="Imagem da Arena">
                        <p class="mb-1"><strong>Arena Sol Nascente</strong></p>
                        <p class="text-muted small">Beach Tênis | Recife, PE</p>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Data</span>
                            <span class="fw-bold">05/07/2026</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Horário</span>
                            <span class="fw-bold">18:00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Valor da reserva</span>
                            <span class="fw-bold">R$ 80,00</span>
                        </div>
                        <h4 class="text-success mt-4 fw-bold">Total: R$ 80,00</h4>
                        <div id="msg-garantia" class="alert alert-success small mt-3 mb-0 border-0 bg-success bg-opacity-10 text-success">
                            Reserva garantida após confirmação do Pix.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirmacao" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalConfirmacaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-body p-5 text-center">
                
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle" style="width: 80px; height: 80px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                        </svg>
                    </div>
                </div>
                
                <h3 class="fw-bold text-success mb-2">Reserva Confirmada!</h3>
                <p class="text-muted mb-4">Seu pagamento foi processado e o horário já está garantido.</p>

                <div class="bg-light rounded p-3 text-start mb-4 text-sm border">
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="text-muted">Local:</span>
                        <span class="fw-bold">Arena Sol Nascente</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="text-muted">Data/Hora:</span>
                        <span class="fw-bold">05/07/2026 às 18:00</span>
                    </div>
                    <div class="d-flex justify-content-between pt-1">
                        <span class="text-muted">Total:</span>
                        <span class="fw-bold text-success">R$ 80,00</span>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 mt-4">
                    <a href="/cliente/agendamentos" class="btn btn-verde w-100 py-3 fw-bold rounded-pill">
                        Ver Minhas Reservas
                    </a>
                    <a href="/" class="btn btn-outline-success w-100 py-3 fw-bold rounded-pill">
                        Criar Nova Reserva
                    </a>
                    <a href="/" class="btn btn-light text-muted w-100 py-3 fw-bold rounded-pill border">
                        Voltar à Página Inicial
                    </a>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
    function updateButton() {
        const btn = document.getElementById('btn-pagar');
        const msg = document.getElementById('msg-garantia');
        const isPix = document.querySelector('input[name="pagamento"]:checked').value === 'pix';
        
        if (isPix) {
            btn.textContent = 'Pagar R$ 80,00 via Pix';
            msg.textContent = 'Reserva garantida após confirmação do Pix.';
        } else {
            btn.textContent = 'Confirmar Reserva';
            msg.textContent = 'Reserva confirmada. O pagamento será realizado na recepção da arena.';
        }
    }
</script>
@endsection