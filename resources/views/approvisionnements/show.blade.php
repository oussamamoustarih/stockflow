@extends('layouts.app')

@section('title', 'Détail Approvisionnement')

@section('content')
<div class="row g-4">

    {{-- Infos générales --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Informations
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">N°</td>
                        <td><strong>#{{ $approvisionnement->id }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Fournisseur</td>
                        <td>{{ $approvisionnement->fournisseur->nom ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date</td>
                        <td>{{ \Carbon\Carbon::parse($approvisionnement->date_approvisionnement)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Montant total</td>
                        <td><strong>{{ number_format($approvisionnement->montant_total, 2) }} DH</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Statut</td>
                        <td>
                            @if($approvisionnement->statut == 'valide')
                                <span class="badge bg-success">Validé</span>
                            @elseif($approvisionnement->statut == 'en_cours')
                                <span class="badge bg-warning text-dark">En cours</span>
                            @else
                                <span class="badge bg-danger">Annulé</span>
                            @endif
                        </td>
                    </tr>
                </table>

                @if($approvisionnement->statut == 'en_cours')
                <form action="{{ route('approvisionnements.valider', $approvisionnement) }}"
                      method="POST"
                      onsubmit="return confirm('Valider cet approvisionnement ?')">
                    @csrf
                    <button class="btn btn-success btn-sm w-100">
                        <i class="bi bi-check-lg"></i> Valider l'approvisionnement
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Lignes produits --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-ul"></i> Produits approvisionnés
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
                        @foreach($approvisionnement->lignes as $ligne)
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
                                {{ number_format($approvisionnement->montant_total, 2) }} DH
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="mt-3">
    <a href="{{ route('approvisionnements.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>
@endsection