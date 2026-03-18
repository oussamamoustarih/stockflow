@extends('layouts.app')

@section('title', 'Modifier Catégorie')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <i class="bi bi-pencil"></i> Modifier la catégorie
    </div>
    <div class="card-body">
        <form action="{{ route('categories.update', $categorie) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                <input type="text" name="nom"
                       class="form-control @error('nom') is-invalid @enderror"
                       value="{{ old('nom', $categorie->nom) }}">
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" rows="3"
                          class="form-control">{{ old('description', $categorie->description) }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-check-lg"></i> Modifier
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection