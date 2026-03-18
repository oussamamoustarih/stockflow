@extends('layouts.app')

@section('title', 'Fiche Client')

@section('content')
<div class="row g-4">

    {{-- Fiche client --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-circle"></i> {{ $client->prenom }} {{ $client->nom }}
            </div>
            <div class="card-body">
                <p><i class="bi bi-envelope"></i> {{ $client->email ?? '-' }}</p>
                <p><i class="bi bi-telephone"></i> {{ $client->telephone ?? '-' }}</p>
                <p><i class="bi bi-geo-alt"></i> {{ $client->adresse ?? '-' }}</p>
                <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
            </div>
        </div>

        {{-- Statistiques individuelles (R4) --}}
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-bar-chart"></i> Statistiques
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total achats</span>
                    <strong>{{ $totalAchats }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Chiffre d'affaires</span>
                    <strong>{{ number_format($chiffreAffaires, 2) }} DH</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Panier moyen</span>
                    <strong>{{ number_format($panierMoyen, 2) }} DH</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Premier achat</span>
                    <strong>{{ $premiereVente ? \Carbon\Carbon::parse($premiereVente)->format('d/m/Y') : '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Dernier achat</span>
                    <strong>{{ $derniereVente ? \Carbon\Carbon::parse($derniereVente)->format('d/m/Y') : '-' }}</strong>
                </div>
            </div>
        </div>

        {{-- Top 5 produits (R4) --}}
        @if($topProduits->count() > 0)
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-trophy"></i> Top 5 produits préférés
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
                        @foreach($topProduits as $ligne)
                        <tr>
                            <td>{{ $ligne->produit->libelle ?? '-' }}</td>
                            <td><span class="badge bg-primary">{{ $ligne->total_quantite }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Historique transactions --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i> Historique des transactions
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Paiement</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventes as $vente)
                        <tr>
                            <td>{{ $vente->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($vente->montant_total, 2) }} DH</td>
                            <td>
                                @if($vente->mode_paiement == 'especes')
                                    <span class="badge bg-success">Espèces</span>
                                @else
                                    <span class="badge bg-info">Carte</span>
                                @endif
                            </td>
                            <td>
                                @if($vente->statut == 'validee')
                                    <span class="badge bg-success">Validée</span>
                                @elseif($vente->statut == 'en_cours')
                                    <span class="badge bg-warning">En cours</span>
                                @else
                                    <span class="badge bg-danger">Annulée</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Aucune transaction trouvée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($ventes->hasPages())
            <div class="card-footer">
                {{ $ventes->links() }}
            </div>
            @endif
        </div>
    </div>

</div>

<div class="mt-3">
    <a href="{{ route('clients.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>
@endsection