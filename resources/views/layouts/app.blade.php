<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title', 'Tableau de Bord')</title>

    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="icon" href="/assets/img/logo.svg" type="image/svg+xml">

    <style>
        body {
            background-color: #f8f9fa;
        }
        /* Sidebar */
#sidebar {
    height: 100vh;
    background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
    width: 250px;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 100;
    transition: all 0.3s;
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
        /* Main content */
        #main-content {
            margin-left: 250px;
            min-height: 100vh;
        }
        /* Topbar */
        #topbar {
            background-color: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 25px;
        }
        /* Cards */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
        }
        /* Badges alertes */
        .badge-alerte {
            background-color: #ef4444;
        }
        /* Tables */
        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            font-size: 0.875rem;
            color: #475569;
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<div id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-boxes"></i> StockFlow
    </div>

    <nav class="mt-3">
    {{-- Tableau de bord --}}
    <div class="nav-section">Principal</div>
    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2" style="color: #3b82f6;"></i> Tableau de bord
    </a>

    @if(auth()->user()->role === 'admin')
    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill" style="color: #a855f7;"></i> Utilisateurs
    </a>
    @endif

    {{-- Catalogue --}}
    <div class="nav-section">Catalogue</div>
    <a href="{{ route('produits.index') }}" class="nav-link {{ request()->routeIs('produits.*') ? 'active' : '' }}">
        <i class="bi bi-box-seam" style="color: #f97316;"></i> Produits
    </a>
    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
        <i class="bi bi-tags" style="color: #eab308;"></i> Catégories
    </a>
    <a href="{{ route('marques.index') }}" class="nav-link {{ request()->routeIs('marques.*') ? 'active' : '' }}">
        <i class="bi bi-award" style="color: #06b6d4;"></i> Marques
    </a>

    {{-- Personnes --}}
    <div class="nav-section">Personnes</div>
    <a href="{{ route('fournisseurs.index') }}" class="nav-link {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}">
        <i class="bi bi-truck" style="color: #10b981;"></i> Fournisseurs
    </a>
    <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
        <i class="bi bi-people" style="color: #34d399;"></i> Clients
    </a>

    {{-- Opérations --}}
    <div class="nav-section">Opérations</div>
    <a href="{{ route('approvisionnements.index') }}" class="nav-link {{ request()->routeIs('approvisionnements.*') ? 'active' : '' }}">
        <i class="bi bi-cart-plus" style="color: #f97316;"></i> Approvisionnements
    </a>
    <a href="{{ route('ventes.index') }}" class="nav-link {{ request()->routeIs('ventes.*') ? 'active' : '' }}">
        <i class="bi bi-receipt" style="color: #22c55e;"></i> Ventes
    </a>
    <a href="{{ route('commandes.index') }}" class="nav-link {{ request()->routeIs('commandes.*') ? 'active' : '' }}">
        <i class="bi bi-clipboard-check" style="color: #38bdf8;"></i> Commandes
    </a>

    {{-- Stock --}}
    <div class="nav-section">Stock</div>
    <a href="{{ route('mouvements.index') }}" class="nav-link {{ request()->routeIs('mouvements.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-left-right" style="color: #ef4444;"></i> Mouvements
    </a>
</nav>
</div>

{{-- MAIN CONTENT --}}
<div id="main-content">

    {{-- TOPBAR --}}
    <div id="topbar" class="d-flex justify-content-between align-items-center">
        <h6 class="mb-0 text-muted">@yield('title', 'Tableau de bord')</h6>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small">
                <i class="bi bi-person-circle"></i>
                {{ auth()->user()->name }}
                <span class="badge bg-secondary ms-1">{{ auth()->user()->role }}</span>
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </button>
            </form>
        </div>
    </div>

    {{-- CONTENU --}}
    <div class="p-4">

        {{-- Messages flash --}}
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

<!-- SweetAlert2 JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<!-- Bootstrap JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Alpine.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
// SweetAlert2 - Global confirmation handler
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-confirm').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const form = this.closest('.confirm-form');

            let title = 'Confirmer l\'action';
            let text = 'Êtes-vous sûr ?';
            let confirmText = 'Oui';
            let icon = 'warning';

            if (this.classList.contains('btn-delete')) {
                title = 'Confirmer la suppression';
                text = 'Cette action est irréversible.';
                confirmText = 'Oui, supprimer';
            } else if (this.classList.contains('btn-valider')) {
                title = 'Valider cette action ?';
                text = 'Confirmer la validation ?';
                confirmText = 'Oui, valider';
                icon = 'question';
            } else if (this.classList.contains('btn-annuler')) {
                title = 'Annuler cette action ?';
                text = 'Êtes-vous sûr d\'annuler ?';
                confirmText = 'Oui, annuler';
            } else if (this.classList.contains('btn-livrer')) {
                title = 'Confirmer la livraison ?';
                text = 'Le stock sera mis à jour.';
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

@stack('scripts')
</body>
</html>