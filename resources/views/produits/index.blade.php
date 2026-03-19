@extends('layouts.app')

@section('title', 'Produits')

@section('content')

{{-- Filtres --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form action="{{ route('produits.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control"
                       placeholder="Rechercher par nom ou référence..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="categorie_id" class="form-select">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('categorie_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrer
                </button>
                <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box-seam"></i> Liste des produits</span>
<div class="d-flex gap-2">
    <a href="{{ route('produits.export.excel') }}" class="btn btn-success btn-sm">
        <i class="bi bi-file-earmark-excel"></i> Export Excel
    </a>
    <a href="{{ route('produits.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Nouveau produit
    </a>
</div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Référence</th>
                    <th>Libellé</th>
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
                    <td>
                        @if($produit->image_url)
                            <img src="{{ asset('storage/' . $produit->image_url) }}"
                                 width="40" height="40"
                                 style="object-fit:cover; border-radius:5px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                 style="width:40px;height:40px;border-radius:5px;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td><code>{{ $produit->reference }}</code></td>
                    <td>{{ $produit->libelle }}</td>
                    <td>{{ $produit->categorie->nom ?? '-' }}</td>
                    <td>{{ $produit->marque->nom ?? '-' }}</td>
                    <td>{{ number_format($produit->prix_vente, 2) }} DH</td>
                    <td>
                        @if($produit->quantite_stock <= $produit->seuil_alerte)
                            <span class="badge bg-danger">{{ $produit->quantite_stock }}</span>
                        @else
                            <span class="badge bg-success">{{ $produit->quantite_stock }}</span>
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
                            <button type="button" class="btn btn-sm btn-danger btn-confirm btn-delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
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