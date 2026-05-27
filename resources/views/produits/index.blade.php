@extends('layouts.app')

@section('title', 'Produits')

@section('content')

{{-- Filtres --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form action="{{ route('produits.index') }}" method="GET"
              class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control"
                       placeholder="Rechercher par nom ou référence..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="categorie_id" class="form-select">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                                {{ request('categorie_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search"></i> Filtrer
                </button>
                {{-- Bouton Reset : visible uniquement si un filtre est actif --}}
                @if(request('search') || request('categorie_id'))
                    <a href="{{ route('produits.index') }}"
                       class="btn btn-secondary btn-sm">
                        <i class="bi bi-x-lg"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box-seam"></i> Liste des produits</span>
        <div class="d-flex gap-2">
            <a href="{{ route('produits.export.excel') }}"
               class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>
            <a href="{{ route('produits.create') }}"
               class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Nouveau produit
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Marque</th>
                    <th>Prix Vente</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produits as $produit)
                <tr>
                    {{-- Image + Libellé + Référence dans une seule colonne --}}
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            {{-- Image --}}
                            @if($produit->image_url)
                                <img src="{{ asset('storage/' . $produit->image_url) }}"
                                     width="45" height="45"
                                     style="object-fit:cover; border-radius:8px;
                                            flex-shrink:0;">
                            @else
                                <div class="bg-light d-flex align-items-center
                                            justify-content-center"
                                     style="width:45px; height:45px;
                                            border-radius:8px; flex-shrink:0;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                            {{-- Libellé + Référence --}}
                            <div>
                                <div class="fw-semibold text-dark small">
                                    {{ $produit->libelle }}
                                </div>
                                <code class="text-muted"
                                      style="font-size:0.75rem;">
                                    {{ $produit->reference }}
                                </code>
                            </div>
                        </div>
                    </td>
                    <td>{{ $produit->categorie->nom ?? '-' }}</td>
                    <td>{{ $produit->marque->nom ?? '-' }}</td>
                    <td>{{ number_format($produit->prix_vente, 2) }} DH</td>
                    <td>
                        @if($produit->quantite_stock <= $produit->seuil_alerte)
                            <span class="badge bg-danger">
                                {{ $produit->quantite_stock }}
                            </span>
                        @else
                            <span class="badge bg-success">
                                {{ $produit->quantite_stock }}
                            </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('produits.show', $produit) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('produits.edit', $produit) }}"
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('produits.destroy', $produit) }}"
                              method="POST" class="d-inline confirm-form">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="btn btn-sm btn-danger btn-confirm btn-delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        Aucun produit trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($produits->hasPages())
    <div class="card-footer">
        {{ $produits->links() }}
    </div>
    @endif
</div>
@endsection