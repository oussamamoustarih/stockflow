<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="{{ now()->toRfc7231String() }}">
    <title>{{ config('app.name') }} - @yield('title', 'Tableau de Bord')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="icon" href="/assets/img/logo.svg" type="image/svg+xml">

    <style>
        :root {
            --sidebar-width: 250px;
            --topbar-height: 60px;
        }

        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        body {
            background-color: #f8f9fa;
        }

        #sidebar {
            height: 100vh;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 200;
            transition: transform 0.3s ease;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #334155 #1e293b;
        }

        #sidebar .sidebar-brand {
            padding: 22px 20px;
            color: #fff;
            font-size: 1.25rem;
            font-weight: 800;
            border-bottom: 1px solid #334155;
            letter-spacing: -0.3px;
        }

        #sidebar .sidebar-brand i {
            color: #3b82f6;
            margin-right: 8px;
        }

        #sidebar .nav-link {
            color: #94a3b8;
            padding: 10px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.2s;
            font-size: 0.9rem;
            font-weight: 500;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            color: #fff;
            background-color: #334155;
        }

        #sidebar .nav-link i {
            margin-right: 10px;
            width: 18px;
            font-size: 1rem;
        }

        #sidebar .nav-section {
            color: #cbd5e1;
            font-size: 0.7rem;
            text-transform: uppercase;
            padding: 15px 20px 5px;
            letter-spacing: 1.5px;
            font-weight: 700;
            border-top: 1px solid #334155;
            margin-top: 5px;
        }

        #sidebarBackdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
            z-index: 180;
        }

        #sidebarBackdrop.show {
            opacity: 1;
            visibility: visible;
        }

        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            z-index: 150;
            background-color: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 25px;
            min-height: var(--topbar-height);
        }

        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding-top: var(--topbar-height);
        }

        .content-wrapper {
            padding: 1.5rem;
            max-width: 100%;
        }

        .page-title {
            min-width: 0;
        }

        .page-title-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-actions {
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .topbar-user-link {
            max-width: 100%;
        }

        .topbar-user-link .user-name {
            display: inline-block;
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .mobile-menu-toggle {
            display: none;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            max-width: 100%;
        }

        .card[style*="max-width"] {
            width: 100%;
            margin-inline: auto;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
            background-color: #fff;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
        }

        .badge-alerte {
            background-color: #ef4444;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive > .table {
            min-width: 640px;
        }

        .table-responsive > .table.table-sm {
            min-width: 0;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            font-size: 0.875rem;
            color: #475569;
        }

        .card-header .d-flex {
            flex-wrap: wrap;
        }

        .card .btn,
        .card .form-control,
        .card .form-select,
        .card img,
        .card canvas {
            max-width: 100%;
        }

        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
                box-shadow: 0 20px 40px rgba(15, 23, 42, 0.3);
            }

            #sidebar.is-open {
                transform: translateX(0);
            }

            #topbar {
                left: 0;
                padding: 0.875rem 1rem;
            }

            #main-content {
                margin-left: 0;
            }

            .content-wrapper {
                padding: 1rem;
            }

            .mobile-menu-toggle {
                display: inline-flex;
            }
        }

        @media (max-width: 767.98px) {
            :root {
                --topbar-height: 74px;
            }

            #topbar {
                padding: 0.75rem 0.875rem;
            }

            .content-wrapper {
                padding: 0.875rem;
            }

            .page-title {
                width: 100%;
            }

            .page-title-text {
                white-space: normal;
            }

            .topbar-actions {
                width: 100%;
                justify-content: space-between;
                gap: 0.5rem;
            }

            .topbar-user-link {
                flex: 1 1 auto;
                min-width: 0;
            }

            .topbar-user-link .user-name {
                max-width: 120px;
            }

            .table-responsive > .table {
                min-width: 560px;
            }

            .table {
                font-size: 0.875rem;
            }

            .card .d-flex.gap-1,
            .card .d-flex.gap-2,
            .card .d-flex.gap-3 {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 575.98px) {
            .card .d-flex.gap-2,
            .card .d-flex.gap-3 {
                flex-direction: column;
                align-items: stretch;
            }

            .card .d-flex.gap-2 > a,
            .card .d-flex.gap-2 > button,
            .card .d-flex.gap-2 > form,
            .card .d-flex.gap-3 > a,
            .card .d-flex.gap-3 > button,
            .card .d-flex.gap-3 > form {
                width: 100%;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

<div id="sidebarBackdrop"></div>

<div id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-boxes"></i> StockFlow
    </div>

    <nav class="mt-3">
        @php
            $role = auth()->user()->role;
        @endphp

        <div class="nav-section">Principal</div>

        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2" style="color: #3b82f6;"></i> Tableau de bord
        </a>

        @if($role === 'admin')
        <a href="{{ route('users.index') }}"
           class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill" style="color: #a855f7;"></i> Utilisateurs
        </a>
        @endif

        @if(in_array($role, ['admin', 'gestionnaire']))
        <div class="nav-section">Catalogue</div>
        <a href="{{ route('produits.index') }}"
           class="nav-link {{ request()->routeIs('produits.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam" style="color: #f97316;"></i> Produits
        </a>
        <a href="{{ route('categories.index') }}"
           class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags" style="color: #eab308;"></i> Categories
        </a>
        <a href="{{ route('marques.index') }}"
           class="nav-link {{ request()->routeIs('marques.*') ? 'active' : '' }}">
            <i class="bi bi-award" style="color: #06b6d4;"></i> Marques
        </a>
        @endif

        @if(in_array($role, ['admin', 'gestionnaire', 'vendeur']))
        <div class="nav-section">Personnes</div>

            @if(in_array($role, ['admin', 'gestionnaire']))
            <a href="{{ route('fournisseurs.index') }}"
               class="nav-link {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}">
                <i class="bi bi-truck" style="color: #10b981;"></i> Fournisseurs
            </a>
            @endif

            <a href="{{ route('clients.index') }}"
               class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                <i class="bi bi-people" style="color: #34d399;"></i> Clients
            </a>
        @endif

        <div class="nav-section">Operations</div>

        @if(in_array($role, ['admin', 'gestionnaire']))
        <a href="{{ route('approvisionnements.index') }}"
           class="nav-link {{ request()->routeIs('approvisionnements.*') ? 'active' : '' }}">
            <i class="bi bi-cart-plus" style="color: #f97316;"></i> Approvisionnements
        </a>
        @endif

        @if(in_array($role, ['admin', 'gestionnaire', 'vendeur']))
        <a href="{{ route('ventes.index') }}"
           class="nav-link {{ request()->routeIs('ventes.*') ? 'active' : '' }}">
            <i class="bi bi-receipt" style="color: #22c55e;"></i> Ventes
        </a>
        @endif

        <a href="{{ route('commandes.index') }}"
           class="nav-link {{ request()->routeIs('commandes.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard-check" style="color: #38bdf8;"></i> Commandes
        </a>

        @if(in_array($role, ['admin', 'gestionnaire']))
        <div class="nav-section">Stock</div>
        <a href="{{ route('mouvements.index') }}"
           class="nav-link {{ request()->routeIs('mouvements.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right" style="color: #ef4444;"></i> Mouvements
        </a>
        @endif
    </nav>
</div>

<div id="main-content">
    <div id="topbar" class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div class="d-flex align-items-center gap-2 page-title">
            <button type="button"
                    class="btn btn-outline-secondary btn-sm mobile-menu-toggle"
                    id="sidebarToggle"
                    aria-label="Ouvrir le menu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h6 class="mb-0 text-muted page-title-text">@yield('title', 'Tableau de bord')</h6>
        </div>
        <div class="d-flex align-items-center gap-2 gap-sm-3 topbar-actions">
            <a href="{{ route('profil.index') }}"
               class="text-muted small text-decoration-none topbar-user-link"
               style="cursor: pointer;">
                <i class="bi bi-person-circle"></i>
                <span class="user-name">{{ auth()->user()->name }}</span>
                <span class="badge bg-secondary ms-1">{{ auth()->user()->role }}</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button type="button"
                        class="btn btn-sm btn-outline-danger"
                        id="btnLogout">
                    <i class="bi bi-box-arrow-right"></i> Deconnexion
                </button>
            </form>
        </div>
    </div>

    <div class="content-wrapper">
        @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: {!! json_encode(session('success')) !!},
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                });
            });
        </script>
        @endif

        @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: {!! json_encode(session('error')) !!},
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                });
            });
        </script>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
function setSidebarState(isOpen) {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');

    if (!sidebar || !backdrop) {
        return;
    }

    sidebar.classList.toggle('is-open', isOpen);
    backdrop.classList.toggle('show', isOpen);
    document.body.classList.toggle('overflow-hidden', isOpen && window.innerWidth < 992);
}

document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const logoutButton = document.getElementById('btnLogout');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const isOpen = !sidebar.classList.contains('is-open');
            setSidebarState(isOpen);
        });
    }

    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', function() {
            setSidebarState(false);
        });
    }

    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
            setSidebarState(false);
        }
    });

    document.querySelectorAll('.card-body > table, .card-body > .table').forEach(function(table) {
        if (table.parentElement.classList.contains('table-responsive')) {
            return;
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'table-responsive';
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);
    });

    if (logoutButton) {
        logoutButton.addEventListener('click', function() {
            Swal.fire({
                title: 'Deconnexion',
                text: 'Etes-vous sur de vouloir vous deconnecter ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Oui, deconnecter',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    }

    document.querySelectorAll('.btn-confirm').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const form = this.closest('.confirm-form');

            let title = 'Confirmer l\'action';
            let text = 'Etes-vous sur ?';
            let confirmText = 'Oui';
            let icon = 'warning';

            if (this.classList.contains('btn-delete')) {
                title = 'Confirmer la suppression';
                text = 'Cette action est irreversible.';
                confirmText = 'Oui, supprimer';
            } else if (this.classList.contains('btn-valider')) {
                title = 'Valider cet approvisionnement ?';
                text = 'Le stock sera mis a jour.';
                confirmText = 'Oui, valider';
                icon = 'question';
            } else if (this.classList.contains('btn-annuler')) {
                title = 'Annuler cette action ?';
                text = 'Etes-vous sur d\'annuler ?';
                confirmText = 'Oui, annuler';
            } else if (this.classList.contains('btn-livrer')) {
                title = 'Confirmer la livraison ?';
                text = 'Le stock sera mis a jour.';
                confirmText = 'Oui, livrer';
                icon = 'info';
            }

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmText,
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>

<script>
(function() {
    fetch('/auth/status', {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (!data.authenticated) {
            window.location.replace('/login');
        }
    })
    .catch(() => {
        window.location.replace('/login');
    });
})();
</script>

@stack('scripts')
</body>
</html>
