@extends('layouts.app')

@section('title', 'Détail Commande')

@section('content')
<div class="row g-4">

    {{-- Infos commande --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Informations
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">N°</td>
                        <td><strong>#{{ $commande->id }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Client</td>
                        <td>{{ $commande->client->nom ?? '-' }} {{ $commande->client->prenom ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date commande</td>
                        <td>{{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Livraison prévue</td>
                        <td>{{ $commande->date_livraison_prevue ? \Carbon\Carbon::parse($commande->date_livraison_prevue)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Montant</td>
                        <td><strong>{{ number_format($commande->montant_total, 2) }} DH</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Statut</td>
                        <td>
                            @if($commande->statut == 'en_attente')
                                <span class="badge bg-warning text-dark">En attente</span>
                            @elseif($commande->statut == 'validee')
                                <span class="badge bg-primary">Validée</span>
                            @elseif($commande->statut == 'livree')
                                <span class="badge bg-success">Livrée</span>
                            @else
                                <span class="badge bg-danger">Annulée</span>
                            @endif
                        </td>
                    </tr>
                </table>

                {{-- Actions selon statut --}}
                <div class="d-grid gap-2 mt-2">
                    @if($commande->statut == 'en_attente')
                        <form action="{{ route('commandes.valider', $commande) }}"
                              method="POST"
                              onsubmit="return confirm('Valider cette commande ?')">
                            @csrf
                            <button class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-check-lg"></i> Valider la commande
                            </button>
                        </form>
                        <form action="{{ route('commandes.annuler', $commande) }}"
                              method="POST"
                              onsubmit="return confirm('Annuler cette commande ?')">
                            @csrf
                            <button class="btn btn-danger btn-sm w-100">
                                <i class="bi bi-x-lg"></i> Annuler la commande
                            </button>
                        </form>
                    @endif

                    @if($commande->statut == 'validee')
                        <form action="{{ route('commandes.livrer', $commande) }}"
                              method="POST"
                              onsubmit="return confirm('Confirmer la livraison ?')">
                            @csrf
                            <button class="btn btn-success btn-sm w-100">
                                <i class="bi bi-truck"></i> Confirmer la livraison
                            </button>
                        </form>
                        <form action="{{ route('commandes.annuler', $commande) }}"
                              method="POST"
                              onsubmit="return confirm('Annuler cette commande ?')">
                            @csrf
                            <button class="btn btn-danger btn-sm w-100">
                                <i class="bi bi-x-lg"></i> Annuler la commande
                            </button>
                        </form>
                    @endif

                    @if($commande->statut == 'livree')
                        <a href="{{ route('commandes.bl', $commande) }}"
                           class="btn btn-dark btn-sm w-100">
                            <i class="bi bi-file-pdf"></i> Télécharger Bon de Livraison
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Infos BL --}}
        @if($commande->bonLivraison)
        <div class="card mt-3">
            <div class="card-header text-success">
                <i class="bi bi-truck"></i> Bon de livraison
            </div>
            <div class="card-body">
                <p><strong>N° :</strong> {{ $commande->bonLivraison->numero_bl }}</p>
                <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($commande->bonLivraison->date_livraison)->format('d/m/Y') }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- Lignes commande --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-ul"></i> Produits commandés
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Référence</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commande->lignes as $ligne)
                        <tr>
                            <td>{{ $ligne->produit->libelle ?? '-' }}</td>
                            <td><code>{{ $ligne->produit->reference ?? '-' }}</code></td>
                            <td>{{ $ligne->quantite }}</td>
                            <td>{{ number_format($ligne->prix_unitaire, 2) }} DH</td>
                            <td>{{ number_format($ligne->sous_total, 2) }} DH</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total :</td>
                            <td class="fw-bold text-primary">
                                {{ number_format($commande->montant_total, 2) }} DH
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="mt-3">
    <a href="{{ route('commandes.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>
@endsection