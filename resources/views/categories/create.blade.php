@extends('layouts.app')

@section('title', 'Nouvelle Catégorie')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <i class="bi bi-plus-lg"></i> Nouvelle catégorie
    </div>
    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                <input type="text" name="nom"
                       class="form-control @error('nom') is-invalid @enderror"
                       value="{{ old('nom') }}" placeholder="Ex: Électronique">
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" rows="3"
                          class="form-control"
                          placeholder="Description optionnelle...">{{ old('description') }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Enregistrer
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection