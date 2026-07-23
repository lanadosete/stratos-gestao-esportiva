@extends('layouts.app')

@section('conteudo')
<div class="admin-shell">
    <aside class="admin-sidebar" id="adminSidebar">
        <button type="button" class="admin-sidebar-toggle" id="adminSidebarToggle" title="Recolher menu">
            <i class="bi bi-chevron-left"></i>
        </button>

        <a href="/perfil" class="admin-sidebar-profile">
            <span class="admin-sidebar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            <span class="admin-sidebar-name">{{ Auth::user()->name }}</span>
        </a>

        <nav class="admin-sidebar-nav">
            <a href="/admin/dashboard" class="admin-nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> <span>Painel Geral</span>
            </a>
            <a href="/admin/arenas" class="admin-nav-link {{ (request()->is('admin/arenas*') || request()->is('admin/arena/*')) ? 'active' : '' }}">
                <i class="bi bi-geo-alt-fill"></i> <span>Minhas Arenas</span>
            </a>
            <a href="/admin/financeiro" class="admin-nav-link {{ request()->is('admin/financeiro*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> <span>Financeiro</span>
            </a>
            <a href="/admin/equipe" class="admin-nav-link {{ request()->is('admin/equipe*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> <span>Equipe</span>
            </a>
        </nav>

        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="admin-sidebar-logout">
                <i class="bi bi-box-arrow-right"></i> <span>Sair da Conta</span>
            </button>
        </form>
    </aside>

    <div class="admin-main">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('admin-content')
    </div>
</div>

<style>
    .admin-shell {
        display: flex;
        align-items: stretch;
        min-height: calc(100vh - 72px);
    }

    .admin-sidebar {
        position: relative;
        width: 260px;
        flex-shrink: 0;
        background: #ffffff;
        border-right: 1px solid rgba(0, 0, 0, 0.06);
        padding: 2rem 1.25rem;
        transition: width 0.25s ease, padding 0.25s ease;
    }

    .admin-sidebar.is-collapsed {
        width: 88px;
        padding: 2rem 0.65rem;
    }

    .admin-sidebar-toggle {
        position: absolute;
        top: 1.75rem;
        right: -14px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid rgba(0, 0, 0, 0.08);
        background: #fff;
        color: #28a745;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        z-index: 5;
        transition: transform 0.15s ease;
    }

    .admin-sidebar-toggle:hover {
        transform: scale(1.08);
    }

    .admin-sidebar-profile {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        text-decoration: none;
        padding-bottom: 1.25rem;
        margin-top: 0.5rem;
        margin-bottom: 0.75rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    }

    .admin-sidebar-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: rgba(40, 167, 69, 0.15);
        color: #28a745;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.4rem;
        margin-bottom: 0.6rem;
        flex-shrink: 0;
        transition: width 0.25s ease, height 0.25s ease, font-size 0.25s ease;
    }

    .admin-sidebar-name {
        font-weight: 700;
        font-size: 0.9rem;
        color: #212529;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    .admin-sidebar.is-collapsed .admin-sidebar-name {
        display: none;
    }

    .admin-sidebar.is-collapsed .admin-sidebar-avatar {
        width: 40px;
        height: 40px;
        font-size: 1rem;
        margin-bottom: 0;
    }

    .admin-sidebar-nav {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        margin-top: 0.5rem;
    }

    .admin-nav-link {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.7rem 0.9rem;
        border-radius: 10px;
        color: #6c757d;
        font-weight: 600;
        font-size: 0.92rem;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
        transition: background 0.15s ease, color 0.15s ease;
    }

    .admin-nav-link i {
        font-size: 1.05rem;
        width: 20px;
        text-align: center;
        flex-shrink: 0;
    }

    .admin-nav-link:hover {
        background: rgba(40, 167, 69, 0.08);
        color: #218838;
    }

    .admin-nav-link.active {
        background: #28a745;
        color: #fff;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.25);
    }

    .admin-sidebar.is-collapsed .admin-nav-link {
        justify-content: center;
        padding: 0.7rem;
    }

    .admin-sidebar.is-collapsed .admin-nav-link span {
        display: none;
    }

    .admin-sidebar-logout {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        width: 100%;
        margin-top: 1rem;
        padding: 0.9rem 0.9rem 0;
        border: none;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
        background: transparent;
        color: #dc3545;
        font-weight: 600;
        font-size: 0.92rem;
        text-align: left;
        white-space: nowrap;
        overflow: hidden;
        cursor: pointer;
    }

    .admin-sidebar-logout:hover {
        color: #b02a37;
    }

    .admin-sidebar-logout i {
        font-size: 1.05rem;
        width: 20px;
        text-align: center;
        flex-shrink: 0;
    }

    .admin-sidebar.is-collapsed .admin-sidebar-logout {
        justify-content: center;
    }

    .admin-sidebar.is-collapsed .admin-sidebar-logout span {
        display: none;
    }

    .admin-main {
        flex: 1;
        min-width: 0;
        padding: 2.5rem;
        background: linear-gradient(135deg, #e8f5e9 0%, #ffffff 100%);
    }

    @media (max-width: 767.98px) {
        .admin-main {
            padding: 1.5rem;
        }
    }
</style>

<script>
    (function () {
        var sidebar = document.getElementById('adminSidebar');
        var toggleBtn = document.getElementById('adminSidebarToggle');
        var toggleIcon = toggleBtn.querySelector('i');
        var storageKey = 'stratosAdminSidebarCollapsed';

        if (localStorage.getItem(storageKey) === '1') {
            sidebar.classList.add('is-collapsed');
            toggleIcon.className = 'bi bi-chevron-right';
        }

        toggleBtn.addEventListener('click', function () {
            var isCollapsed = sidebar.classList.toggle('is-collapsed');
            localStorage.setItem(storageKey, isCollapsed ? '1' : '0');
            toggleIcon.className = isCollapsed ? 'bi bi-chevron-right' : 'bi bi-chevron-left';
            toggleBtn.title = isCollapsed ? 'Abrir menu' : 'Recolher menu';
        });
    })();
</script>
@endsection
