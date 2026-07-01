@extends('layouts.app')

@section('conteudo')
<div class="container py-5">
    <div class="row mb-5 text-center">
        <div class="col"><div class="p-2 border-bottom border-success text-success">1. Arena</div></div>
        <div class="col"><div class="p-2 border-bottom border-success text-success">2. Data</div></div>
        <div class="col"><div class="p-2 border-bottom border-success text-success fw-bold">3. Pagamento</div></div>
        <div class="col"><div class="p-2 border-bottom text-muted">4. Confirmação</div></div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <h3 class="mb-4">Pagamento</h3>
            <p class="text-muted">Finalize sua reserva escolhendo a forma de pagamento.</p>

            <div class="card card-stratos p-4 shadow-sm border-0 mb-4">
                <h5 class="mb-3">Forma de pagamento</h5>
                <div class="list-group">
                    <label class="list-group-item p-3 d-flex align-items-center">
                        <input type="radio" name="pagamento" value="pix" class="me-3" checked onchange="updateButton()">
                        <div><strong>Pix</strong><br><small class="text-muted">Pagamento instantâneo e seguro.</small></div>
                    </label>
                    <label class="list-group-item p-3 d-flex align-items-center">
                        <input type="radio" name="pagamento" value="local" class="me-3" onchange="updateButton()">
                        <div><strong>Pagar no Local</strong><br><small class="text-muted">Pague em dinheiro ou cartão ao chegar na arena.</small></div>
                    </label>
                </div>
                <button id="btn-pagar" class="btn btn-success mt-4 w-100 py-3">Pagar R$ 80,00 via Pix</button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-stratos p-4 shadow-sm border-0">
                <h5 class="mb-3">Detalhes da Reserva</h5>
                <img src="https://images.unsplash.com/photo-1595435934242-4763e0202283?q=80&w=400" class="img-fluid rounded mb-3">
                <p><strong>Arena Sol Nascente</strong><br>Beach Tênis | Recife, PE</p>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Valor da reserva</span>
                    <span>R$ 80,00</span>
                </div>
                <h4 class="text-success mt-2">Total: R$ 80,00</h4>
                <div id="msg-garantia" class="alert alert-success small mt-3">Reserva garantida após confirmação do Pix.</div>
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
            msg.textContent = 'Reserva confirmada. Pagamento será realizado na arena.';
        }
    }
</script>
@endsection