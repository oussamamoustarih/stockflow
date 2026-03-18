@extends('layouts.app')

@section('title', 'Nouveau Client')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <i class="bi bi-plus-lg"></i> Nouveau client
    </div>
    <div class="card-body">
        <form action="{{ route('clients.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="nom"
                           class="form-control @error('nom') is-invalid @enderror"
                           value="{{ old('nom') }}" placeholder="Ex: Alami">
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                    <input type="text" name="prenom"
                           class="form-control @error('prenom') is-invalid @enderror"
                           value="{{ old('prenom') }}" placeholder="Ex: Youssef">
                    @error('prenom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="Ex: client@gmail.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Téléphone</label>
                <input type="text" name="telephone"
                       class="form-control"
                       value="{{ old('telephone') }}" placeholder="Ex: 0661112233">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Adresse</label>
                <textarea name="adresse" rows="3"
                          class="form-control"
                          placeholder="Adresse complète...">{{ old('adresse') }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Enregistrer
                </button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection