@php
    $souAdmin = Auth::user()->tipo_conta === 'admin';
@endphp
@extends($souAdmin ? 'layouts.admin' : 'layouts.app')

@section($souAdmin ? 'admin-content' : 'conteudo')
<div class="bg-gradient-stratos" style="min-height: 100vh;">
<div class="container py-5">

    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Agenda</h3>
            <p class="text-muted mb-0">Dias com reservas ficam destacados. Clique em um dia para ver os jogos marcados.</p>
        </div>
    </div>

    <style>
        /* O FullCalendar aplica a classe .fc no PRÓPRIO elemento alvo (#calendario-agenda),
           não em um filho separado — por isso os seletores abaixo não repetem ".fc". */
        #calendario-agenda { --fc-border-color: #eee; }
        #calendario-agenda .fc-toolbar-title { font-size: 1.1rem; font-weight: 700; }

        /* Nomes dos dias da semana e números dos dias são renderizados como <a>, sem
           resetar aparência — herdam o azul/sublinhado padrão do Bootstrap. */
        #calendario-agenda a,
        #calendario-agenda a:hover,
        #calendario-agenda a:focus {
            text-decoration: none !important;
            color: inherit;
        }

        #calendario-agenda .fc-col-header-cell-cushion {
            padding: 8px 4px;
            color: #6c757d;
            text-transform: uppercase;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        #calendario-agenda .fc-daygrid-day-number {
            padding: 8px;
            color: #343a40;
            font-weight: 500;
            cursor: pointer;
        }

        #calendario-agenda .fc-daygrid-day-frame:hover .fc-daygrid-day-number {
            color: #198754;
        }

        #calendario-agenda .fc-daygrid-day.dia-com-reserva .fc-daygrid-day-frame {
            background: rgba(25, 135, 84, 0.12);
            border-radius: 8px;
        }
        #calendario-agenda .fc-daygrid-day.dia-com-reserva .fc-daygrid-day-number {
            font-weight: 700;
            color: #146c2e;
        }
        #calendario-agenda .fc-daygrid-day.dia-selecionada .fc-daygrid-day-frame {
            background: #198754 !important;
            border-radius: 8px;
        }
        #calendario-agenda .fc-daygrid-day.dia-selecionada .fc-daygrid-day-number {
            color: #fff;
            font-weight: 700;
        }
        #calendario-agenda .fc-day-today .fc-daygrid-day-frame {
            background: rgba(25, 135, 84, 0.06);
        }
    </style>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card card-stratos border-0 shadow-sm rounded-4 p-4">
                <div id="calendario-agenda"></div>
                <div class="d-flex align-items-center gap-2 mt-3 small text-muted">
                    <span class="d-inline-block rounded-circle" style="width:10px;height:10px;background: rgba(25,135,84,0.5);"></span>
                    Dias com reservas
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card card-stratos border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0" id="titulo-dia-selecionado">Reservas de hoje</h5>
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2" id="contador-reservas">0</span>
                </div>

                <div id="lista-reservas-dia">
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x display-4 d-block mb-3 opacity-25"></i>
                        Carregando...
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales/pt-br.global.min.js"></script>

<script>
    let diasComReservas = new Set(@json($diasComReservas));
    let diaSelecionadoEl = null;
    let primeiraRenderizacao = true;

    const statusLabels = {
        a_iniciar: { texto: 'A Iniciar', classe: 'bg-amber-soft text-amber' },
        em_jogo: { texto: 'Em Jogo', classe: 'bg-success bg-opacity-10 text-success' },
        finalizado: { texto: 'Finalizado', classe: 'bg-secondary bg-opacity-10 text-secondary' },
        cancelado: { texto: 'Cancelado', classe: 'bg-danger bg-opacity-10 text-danger' },
    };

    function formatarDataTitulo(dataStr) {
        const [ano, mes, dia] = dataStr.split('-');
        return dia + '/' + mes + '/' + ano;
    }

    function paraChaveData(date) {
        const ano = date.getFullYear();
        const mes = String(date.getMonth() + 1).padStart(2, '0');
        const dia = String(date.getDate()).padStart(2, '0');
        return ano + '-' + mes + '-' + dia;
    }

    function escapeHtml(texto) {
        const div = document.createElement('div');
        div.textContent = texto;
        return div.innerHTML;
    }

    function renderizarReservas(dataStr, reservas) {
        document.getElementById('titulo-dia-selecionado').textContent = 'Reservas de ' + formatarDataTitulo(dataStr);
        document.getElementById('contador-reservas').textContent = reservas.length;

        const container = document.getElementById('lista-reservas-dia');
        container.innerHTML = '';

        if (reservas.length === 0) {
            container.innerHTML = '<div class="text-center py-5 text-muted"><i class="bi bi-calendar-x display-4 d-block mb-3 opacity-25"></i>Nenhuma reserva neste dia.</div>';
            return;
        }

        reservas.forEach(function (reserva) {
            const status = statusLabels[reserva.status_calculado] || { texto: reserva.status_calculado, classe: 'bg-light text-muted' };

            const botaoContato = reserva.whatsapp
                ? '<a href="' + reserva.whatsapp + '" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success rounded-pill px-3 mt-2">' +
                    '<i class="bi bi-whatsapp me-1"></i> Entrar em contato' +
                  '</a>'
                : '';

            const item = document.createElement('div');
            item.className = 'd-flex justify-content-between align-items-center border rounded-3 p-3 mb-2';
            item.innerHTML =
                '<div>' +
                    '<span class="fw-bold d-block">' + escapeHtml(reserva.horario) + '</span>' +
                    '<span class="text-muted small">' + escapeHtml(reserva.quadra) + ' — ' + escapeHtml(reserva.esporte) + '</span>' +
                    '<span class="text-muted small d-block">' + escapeHtml(reserva.cliente) + '</span>' +
                    botaoContato +
                '</div>' +
                '<span class="badge rounded-pill px-3 py-2 ' + status.classe + '">' + status.texto + '</span>';
            container.appendChild(item);
        });
    }

    function carregarReservasDoDia(dataStr) {
        fetch('/agenda/reservas-do-dia?data=' + dataStr)
            .then(function (r) { return r.json(); })
            .then(function (json) { renderizarReservas(dataStr, json.reservas); })
            .catch(function () { renderizarReservas(dataStr, []); });
    }

    function carregarDiasComReservas(mesStr) {
        fetch('/agenda/dias-com-reservas?mes=' + mesStr)
            .then(function (r) { return r.json(); })
            .then(function (json) {
                diasComReservas = new Set(json.dias);
                calendario.render();
            })
            .catch(function () {});
    }

    function selecionarDia(dataStr, cellEl) {
        if (diaSelecionadoEl) {
            diaSelecionadoEl.classList.remove('dia-selecionada');
        }
        if (cellEl) {
            cellEl.classList.add('dia-selecionada');
            diaSelecionadoEl = cellEl;
        }
        carregarReservasDoDia(dataStr);
    }

    const hojeStr = @json(now()->format('Y-m-d'));

    const calendario = new FullCalendar.Calendar(document.getElementById('calendario-agenda'), {
        locale: 'pt-br',
        initialView: 'dayGridMonth',
        initialDate: hojeStr,
        height: 'auto',
        headerToolbar: { left: 'prev', center: 'title', right: 'next' },
        dayCellClassNames: function (arg) {
            return diasComReservas.has(paraChaveData(arg.date)) ? ['dia-com-reserva'] : [];
        },
        dateClick: function (info) {
            selecionarDia(info.dateStr, info.dayEl);
        },
        datesSet: function (info) {
            // Na primeira renderização os dias já vêm carregados do servidor (evita
            // um flash sem destaque + uma requisição repetida logo ao abrir a página).
            if (primeiraRenderizacao) {
                primeiraRenderizacao = false;
            } else {
                const mesStr = paraChaveData(info.view.currentStart).substring(0, 7);
                carregarDiasComReservas(mesStr);
            }

            if (!diaSelecionadoEl) {
                const celulaHoje = document.querySelector('#calendario-agenda .fc-day-today');
                if (celulaHoje) {
                    selecionarDia(hojeStr, celulaHoje);
                }
            }
        },
    });

    calendario.render();

    // O FullCalendar mede a largura do container na primeira renderização e não
    // observa mudanças de layout sozinho (sem ResizeObserver). Se nesse instante a
    // largura ainda não estiver definitiva (ex.: fontes/ícones carregando de forma
    // assíncrona), o calendário fica largo demais para o card. Força um recálculo
    // assim que a página e as fontes realmente terminarem de carregar.
    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(function () { calendario.updateSize(); });
    }
    window.addEventListener('load', function () { calendario.updateSize(); });
</script>
@endsection
