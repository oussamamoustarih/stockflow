@extends('layouts.app')

@section('title', 'Nouveau Produit')

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-header">
        <i class="bi bi-plus-lg"></i> Nouveau produit
    </div>
    <div class="card-body">
        <form action="{{ route('produits.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Référence <span class="text-danger">*</span></label>
                    <input type="text" name="reference"
                           class="form-control @error('reference') is-invalid @enderror"
                           value="{{ old('reference') }}" placeholder="Ex: ELEC001">
                    @error('reference')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Libellé <span class="text-danger">*</span></label>
                    <input type="text" name="libelle"
                           class="form-control @error('libelle') is-invalid @enderror"
                           value="{{ old('libelle') }}" placeholder="Ex: Télévision Samsung 55">
                    @error('libelle')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                    <select name="categorie_id" class="form-select @error('categorie_id') is-invalid @enderror">
                        <option value="">-- Choisir --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('categorie_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Marque <span class="text-danger">*</span></label>
                    <select name="marque_id" class="form-select @error('marque_id') is-invalid @enderror">
                        <option value="">-- Choisir --</option>
                        @foreach($marques as $marque)
                            <option value="{{ $marque->id }}" {{ old('marque_id') == $marque->id ? 'selected' : '' }}>
                                {{ $marque->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('marque_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Prix d'achat (DH) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="prix_achat"
                           class="form-control @error('prix_achat') is-invalid @enderror"
                           value="{{ old('prix_achat') }}">
                    @error('prix_achat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Prix de vente (DH) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="prix_vente"
                           class="form-control @error('prix_vente') is-invalid @enderror"
                           value="{{ old('prix_vente') }}">
                    @error('prix_vente')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Quantité en stock <span class="text-danger">*</span></label>
                    <input type="number" name="quantite_stock"
                           class="form-control @error('quantite_stock') is-invalid @enderror"
                           value="{{ old('quantite_stock', 0) }}">
                    @error('quantite_stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Seuil d'alerte <span class="text-danger">*</span></label>
                    <input type="number" name="seuil_alerte"
                           class="form-control @error('seuil_alerte') is-invalid @enderror"
                           value="{{ old('seuil_alerte', 5) }}">
                    @error('seuil_alerte')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Image du produit</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                       accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Formats acceptés : JPG, PNG, GIF. Taille max : 2 Mo.</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Enregistrer
                </button>
                <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection