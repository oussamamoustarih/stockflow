@extends('layouts.app')

@section('title', 'Modifier Client')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <i class="bi bi-pencil"></i> Modifier le client
    </div>
    <div class="card-body">
        <form action="{{ route('clients.update', $client) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="nom"
                           class="form-control @error('nom') is-invalid @enderror"
                           value="{{ old('nom', $client->nom) }}">
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                    <input type="text" name="prenom"
                           class="form-control @error('prenom') is-invalid @enderror"
                           value="{{ old('prenom', $client->prenom) }}">
                    @error('prenom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $client->email) }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Téléphone</label>
                <input type="text" name="telephone"
                       class="form-control"
                       value="{{ old('telephone', $client->telephone) }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Adresse</label>
                <textarea name="adresse" rows="3"
                          class="form-control">{{ old('adresse', $client->adresse) }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-check-lg"></i> Modifier
                </button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection