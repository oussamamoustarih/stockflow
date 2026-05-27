@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')

{{-- Ligne 1 : KPIs principaux --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white h-100" style="background-color: #3b82f6;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">{{ $totalProduits }}</div>
                    <div class="small">Produits</div>
                </div>
                <i class="bi bi-box-seam fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white h-100" style="background-color: #10b981;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">{{ $totalClients }}</div>
                    <div class="small">Clients</div>
                </div>
                <i class="bi bi-people fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white h-100" style="background-color: #f59e0b;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">{{ $totalFournisseurs }}</div>
                    <div class="small">Fournisseurs</div>
                </div>
                <i class="bi bi-truck fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white h-100" style="background-color: #ef4444;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">{{ $alertesStock }}</div>
                    <div class="small">Alertes Stock</div>
                </div>
                <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'manager')
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-file-earmark-arrow-down me-2"></i>
                    <strong>Exporter les rapports</strong>
                </div>
                <span class="badge bg-info">PDF / Excel</span>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    Télécharger un rapport global du tableau de bord avec les principaux indicateurs, le Top 10 clients,
                    les produits en alerte et les clients inactifs.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('dashboard.export.pdf') }}" class="btn btn-outline-primary">
                        <i class="bi bi-file-earmark-pdf"></i> Exporter en PDF
                    </a>
                    <a href="{{ route('dashboard.export.excel') }}" class="btn btn-outline-success">
                        <i class="bi bi-file-earmark-excel"></i> Exporter en Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Ligne 2 : CA KPIs --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">CA Aujourd'hui</div>
                <div class="fs-4 fw-bold text-primary">
                    {{ number_format($caJour, 2) }} DH
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">CA Cette semaine</div>
                <div class="fs-4 fw-bold text-success">
                    {{ number_format($caSemaine, 2) }} DH
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">CA Ce mois</div>
                <div class="fs-4 fw-bold text-warning">
                    {{ number_format($caMois, 2) }} DH
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Ventes aujourd'hui</div>
                <div class="fs-4 fw-bold text-info">
                    {{ $ventesJour }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Ligne 3 : Graphique + Top produits --}}
<div class="row g-3 mb-4">

    {{-- Graphique évolution CA --}}
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-graph-up"></i> Évolution du CA
            </div>
            <div class="card-body">
                <canvas id="chartCA" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Top 5 produits --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-trophy"></i> Top 5 produits vendus
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Qté</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProduits as $ligne)
                        <tr>
                            <td>{{ $ligne->produit->libelle ?? '-' }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $ligne->total_vendu }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">
                                Aucune vente.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Ligne 4 : Top clients + Clients inactifs --}}
<div class="row g-3 mb-4">

    {{-- Top 10 clients (R4) --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-star"></i> Top 10 meilleurs clients
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>CA Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topClients as $index => $client)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('clients.show', $client) }}">
                                    {{ $client->nom }} {{ $client->prenom }}
                                </a>
                            </td>
                            <td>
                                <strong>
                                    {{ number_format($client->ventes_sum_montant_total ?? 0, 2) }} DH
                                </strong>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">
                                Aucun client.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Clients inactifs (R4) --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header text-warning">
                <i class="bi bi-person-x"></i> Clients inactifs (+ 60 jours)
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Téléphone</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientsInactifs as $client)
                        <tr>
                            <td>
                                <a href="{{ route('clients.show', $client) }}">
                                    {{ $client->nom }} {{ $client->prenom }}
                                </a>
                            </td>
                            <td>{{ $client->telephone ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">
                                Aucun client inactif.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Produits en alerte --}}
@if($produitsAlerte->count() > 0)
<div class="card">
    <div class="card-header text-danger">
        <i class="bi bi-exclamation-triangle"></i> Produits en alerte de stock
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Produit</th>
                    <th>Stock actuel</th>
                    <th>Seuil alerte</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produitsAlerte as $produit)
                <tr>
                    <td><code>{{ $produit->reference }}</code></td>
                    <td>{{ $produit->libelle }}</td>
                    <td>
                        <span class="badge bg-danger">
                            {{ $produit->quantite_stock }}
                        </span>
                    </td>
                    <td>{{ $produit->seuil_alerte }}</td>
                    <td>
                        <a href="{{ route('approvisionnements.create') }}"
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-cart-plus"></i> Approvisionner
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartCA').getContext('2d');

const rawData = {!! json_encode($dataCA) !!}.map((value) => {
    const numericValue = Number(value);
    return Number.isFinite(numericValue) ? numericValue : 0;
});
const labels = {!! json_encode($labels) !!};

// Ligne de tendance : moyenne glissante robuste sur 3 jours max
const tendance = rawData.map((_, index) => {
    const windowStart = Math.max(0, index - 2);
    const windowValues = rawData
        .slice(windowStart, index + 1)
        .map((value) => {
            const numericValue = Number(value);
            return Number.isFinite(numericValue) ? numericValue : 0;
        });

    if (windowValues.length === 0) {
        return 0;
    }

    const sum = windowValues.reduce((total, value) => total + value, 0);
    const average = sum / windowValues.length;

    return Number.isFinite(average) ? average : 0;
});

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                // Dataset 1 : Barres CA journalier
                type: 'bar',
                label: 'CA journalier (DH)',
                data: rawData,
                backgroundColor: function(context) {
                    const value = context.parsed.y;
                    if (value === 0) return 'rgba(203, 213, 225, 0.5)';
                    return 'rgba(59, 130, 246, 0.75)';
                },
                borderColor: function(context) {
                    const value = context.parsed.y;
                    if (value === 0) return 'rgba(203, 213, 225, 0.8)';
                    return '#3b82f6';
                },
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false,
                order: 2,
            },
            {
                // Dataset 2 : Ligne de tendance
                type: 'line',
                label: 'Tendance',
                data: tendance,
                borderColor: '#f97316',
                backgroundColor: 'transparent',
                borderWidth: 2.5,
                borderDash: [6, 3],
                pointRadius: 3,
                pointBackgroundColor: '#f97316',
                pointHoverRadius: 5,
                tension: 0.4,
                spanGaps: true,
                order: 1,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            legend: {
                display: true,
                position: 'top',
                align: 'end',
                labels: {
                    boxWidth: 12,
                    boxHeight: 12,
                    borderRadius: 3,
                    useBorderRadius: true,
                    padding: 15,
                    font: {
                        size: 12,
                        family: "'Inter', sans-serif"
                    },
                    color: '#64748b'
                }
            },
            tooltip: {
                backgroundColor: '#1e293b',
                titleColor: '#f8fafc',
                bodyColor: '#cbd5e1',
                borderColor: '#334155',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        const value = context.parsed.y;
                        if (context.dataset.label === 'Tendance') {
                            return ' Tendance : ' + value.toLocaleString('fr-FR', {
                                minimumFractionDigits: 2
                            }) + ' DH';
                        }
                        return ' CA : ' + value.toLocaleString('fr-FR', {
                            minimumFractionDigits: 2
                        }) + ' DH';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        if (value >= 1000) {
                            return (value / 1000).toLocaleString('fr-FR') + 'k DH';
                        }
                        return value.toLocaleString('fr-FR') + ' DH';
                    },
                    color: '#94a3b8',
                    font: { size: 11 }
                },
                grid: {
                    color: 'rgba(0,0,0,0.04)',
                    drawBorder: false
                },
                border: {
                    display: false
                }
            },
            x: {
                ticks: {
                    color: '#94a3b8',
                    font: { size: 11 },
                    maxTicksLimit: 10,
                    maxRotation: 0,
                    minRotation: 0,
                },
                grid: {
                    display: false
                },
                border: {
                    display: false
                }
            }
        }
    }
});
</script>
@endpush
