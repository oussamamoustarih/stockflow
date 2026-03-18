@extends('layouts.app')

@section('title', 'Détail Produit')

@section('content')
<div class="row g-4">

    {{-- Fiche produit --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($produit->image_url)
                    <img src="{{ asset('storage/' . $produit->image_url) }}"
                         class="img-fluid mb-3" style="max-height:200px; border-radius:10px;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center mb-3"
                         style="height:150px; border-radius:10px;">
                        <i class="bi bi-image fs-1 text-muted"></i>
                    </div>
                @endif
                <h5>{{ $produit->libelle }}</h5>
                <code>{{ $produit->reference }}</code>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><td class="text-muted">Catégorie</td><td>{{ $produit->categorie->nom ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Marque</td><td>{{ $produit->marque->nom ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Prix achat</td><td>{{ number_format($produit->prix_achat, 2) }} DH</td></tr>
                    <tr><td class="text-muted">Prix vente</td><td>{{ number_format($produit->prix_vente, 2) }} DH</td></tr>
                    <tr><td class="text-muted">Marge</td><td class="text-success">{{ number_format($produit->calculerMarge(), 2) }} DH</td></tr>
                    <tr>
                        <td class="text-muted">Stock</td>
                        <td>
                            @if($produit->quantite_stock <= $produit->seuil_alerte)
                                <span class="badge bg-danger">{{ $produit->quantite_stock }}</span>
                            @else
                                <span class="badge bg-success">{{ $produit->quantite_stock }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr><td class="text-muted">Seuil alerte</td><td>{{ $produit->seuil_alerte }}</td></tr>
                </table>
                <a href="{{ route('produits.edit', $produit) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
            </div>
        </div>
    </div>

    {{-- Historique mouvements --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-arrow-left-right"></i> Historique des mouvements de stock
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Quantité</th>
                            <th>Avant</th>
                            <th>Après</th>
                            <th>Référence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mouvements as $mouvement)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($mouvement->date_mouvement)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($mouvement->type_mouvement == 'entree')
                                    <span class="badge bg-success">Entrée</span>
                                @elseif($mouvement->type_mouvement == 'sortie')
                                    <span class="badge bg-danger">Sortie</span>
                                @else
                                    <span class="badge bg-warning">Ajustement</span>
                                @endif
                            </td>
                            <td>{{ $mouvement->quantite }}</td>
                            <td>{{ $mouvement->stock_avant }}</td>
                            <td>{{ $mouvement->stock_apres }}</td>
                            <td><small>{{ $mouvement->reference_operation ?? '-' }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Aucun mouvement enregistré.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($mouvements->hasPages())
            <div class="card-footer">
                {{ $mouvements->links() }}
            </div>
            @endif
        </div>
    </div>

</div>

<div class="mt-3">
    <a href="{{ route('produits.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>
@endsection