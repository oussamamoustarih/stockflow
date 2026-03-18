@extends('layouts.app')

@section('title', 'Nouvel Utilisateur')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <i class="bi bi-plus-lg"></i> Nouvel utilisateur
    </div>
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Nom complet <span class="text-danger">*</span>
                </label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="Ex: Ahmed Benali">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Email <span class="text-danger">*</span>
                </label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       placeholder="Ex: ahmed@stockflow.ma">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Rôle <span class="text-danger">*</span>
                </label>
                <select name="role"
                        class="form-select @error('role') is-invalid @enderror">
                    <option value="">-- Choisir un rôle --</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                        Administrateur
                    </option>
                    <option value="gestionnaire" {{ old('role') == 'gestionnaire' ? 'selected' : '' }}>
                        Gestionnaire de stock
                    </option>
                    <option value="vendeur" {{ old('role') == 'vendeur' ? 'selected' : '' }}>
                        Vendeur / Caissier
                    </option>
                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>
                        Manager / Gérant
                    </option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Mot de passe <span class="text-danger">*</span>
                </label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Minimum 8 caractères">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Confirmer le mot de passe <span class="text-danger">*</span>
                </label>
                <input type="password" name="password_confirmation"
                       class="form-control"
                       placeholder="Répéter le mot de passe">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Créer l'utilisateur
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection