@extends('layouts.app')

@section('title', 'Détail Vente')

@section('content')
<div class="row g-4">

    {{-- Infos vente --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Informations
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">N°</td>
                        <td><strong>#{{ $vente->id }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date de vente</td>
                        <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Client</td>
                        <td>{{ $vente->client ? $vente->client->nom . ' ' . $vente->client->prenom : 'Anonyme' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Vendeur</td>
                        <td>{{ $vente->vendeur->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Montant</td>
                        <td><strong>{{ number_format($vente->montant_total, 2) }} DH</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Paiement</td>
                        <td>
                            @if($vente->mode_paiement == 'especes')
                                <span class="badge bg-success">Espèces</span>
                            @else
                                <span class="badge bg-info">Carte</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Statut</td>
                        <td>
                            @if($vente->statut == 'validee')
                                <span class="badge bg-success">Validée</span>
                            @else
                                <span class="badge bg-danger">Annulée</span>
                            @endif
                        </td>
                    </tr>
                </table>

                <a href="{{ route('ventes.facture', $vente) }}"
                   class="btn btn-dark btn-sm w-100 mt-2">
                    <i class="bi bi-file-pdf"></i> Télécharger la facture PDF
                </a>
            </div>
        </div>
    </div>

    {{-- Lignes vente --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-ul"></i> Produits vendus
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
                        @foreach($vente->lignes as $ligne)
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
                                {{ number_format($vente->montant_total, 2) }} DH
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="mt-3">
    <a href="{{ route('ventes.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>
@endsection