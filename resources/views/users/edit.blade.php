@extends('layouts.app')

@section('title', 'Modifier Utilisateur')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <i class="bi bi-pencil"></i> Modifier l'utilisateur
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Nom complet <span class="text-danger">*</span>
                </label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}">
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
                       value="{{ old('email', $user->email) }}">
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
                    
                    {{-- On affiche Administrateur SEULEMENT si l'utilisateur actuel est déjà admin --}}
                    @if($user->role === 'admin')
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                            Administrateur
                        </option>
                    @endif

                    <option value="gestionnaire" {{ old('role', $user->role) == 'gestionnaire' ? 'selected' : '' }}>
                        Gestionnaire de stock
                    </option>
                    <option value="vendeur" {{ old('role', $user->role) == 'vendeur' ? 'selected' : '' }}>
                        Vendeur / Caissier
                    </option>
                    <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>
                        Manager / Gérant
                    </option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Mot de passe optionnel --}}
            <div class="card mb-3 border-warning">
                <div class="card-header bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-lock"></i> Changer le mot de passe
                    <small class="text-muted">(laisser vide pour ne pas changer)</small>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nouveau mot de passe</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Minimum 8 caractères">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation"
                               class="form-control"
                               placeholder="Répéter le mot de passe">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-check-lg"></i> Modifier
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection