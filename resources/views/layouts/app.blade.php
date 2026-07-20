<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stratos - Gestão Esportiva</title>
    <link rel="icon" type="image/svg+xml" href="/img/logo-stratos.svg">

    <!-- Fontes do Google -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Estilos Globais Stratos -->
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f6; }
        .card-stratos { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .btn-verde { background-color: #28a745; color: white; transition: 0.2s ease-in-out; }
        .btn-verde:hover { background-color: #218838; color: white; transform: translateY(-1px); }
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .bg-gradient-stratos { background: linear-gradient(135deg, #e8f5e9 0%, #ffffff 100%); }
        .sidebar-stratos { background: linear-gradient(180deg, #dcedc8 0%, #ffffff 100%); }
        .text-success { color: #28a745 !important; }
        .border-success { border-color: #28a745 !important; }
    </style>
</head>
<body>
    
    <!-- Menu Superior (Navbar) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom border-success border-3">
        <div class="container-fluid px-4">
            <!-- Logo ou Nome do Sistema -->
            <a class="navbar-brand text-success fw-bold d-flex align-items-center" href="/">
                <img src="/img/logo-stratos.svg" alt="Stratos" height="32" class="me-2">
                STRATOS
            </a>
            
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center gap-3">
                    
                    <!-- Visitantes (Não logados) -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link fw-semibold text-muted" href="/login">Entrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-verde px-4 rounded-pill fw-bold" href="/cadastro">Criar Conta</a>
                        </li>
                    @endguest

                    <!-- Usuários Logados -->
                    @auth
                        <!-- Botões específicos do Admin -->
                        @if(Auth::user()->tipo_conta === 'admin')
                            <li class="nav-item">
                                <a class="nav-link fw-bold text-success" href="/admin/dashboard"><i class="bi bi-speedometer2 me-1"></i> Meu Painel</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-muted" href="/recepcao">Recepção</a>
                            </li>
                        @endif

                        <!-- Botões específicos do Funcionário -->
                        @if(Auth::user()->tipo_conta === 'funcionario')
                            <li class="nav-item">
                                <a class="nav-link fw-bold text-success" href="/recepcao"><i class="bi bi-pc-display-horizontal me-1"></i> Painel de Recepção</a>
                            </li>
                        @endif

                        <!-- Botões específicos do Cliente (Jogador) -->
                        @if(Auth::user()->tipo_conta === 'cliente')
                            <li class="nav-item">
                                <a class="nav-link fw-bold text-success" href="/agendamento"><i class="bi bi-calendar-plus me-1"></i> Nova Reserva</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold text-muted" href="/cliente/agendamentos">Minhas Reservas</a>
                            </li>
                        @endif

                        <!-- Dropdown de Perfil (Aparece para todos que estão logados) -->
                        <li class="nav-item dropdown ms-2 border-start ps-3">
                            <a class="nav-link dropdown-toggle fw-bold text-dark d-flex align-items-center" href="#" id="menuUsuario" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="bg-success bg-opacity-25 text-success rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                Olá, {{ explode(' ', Auth::user()->name)[0] }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3" aria-labelledby="menuUsuario">
                                <li><a class="dropdown-item fw-semibold text-muted mb-1" href="/perfil"><i class="bi bi-person me-2"></i> Meu Perfil</a></li>

                                <li>
                                    <form action="/logout" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger fw-bold"><i class="bi bi-box-arrow-right me-2"></i> Sair da Conta</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                    
                </ul>
            </div>
        </div>
    </nav>

    <!-- Onde o conteúdo das outras páginas é injetado -->
    <main>
        @yield('conteudo')
    </main>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>