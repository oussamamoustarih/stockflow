@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="row g-4">

    {{-- Informations personnelles --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-circle"></i> Informations personnelles
            </div>
            <div class="card-body">
                <form action="{{ route('profil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom complet</label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Adresse email</label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rôle</label>
                        <input type="text" class="form-control"
                               value="{{ ucfirst($user->role) }}" disabled>
                        <small class="text-muted">
                            Le rôle ne peut pas être modifié ici.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Membre depuis</label>
                        <input type="text" class="form-control"
                               value="{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}"
                               disabled>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Mettre à jour
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Changer mot de passe --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-shield-lock"></i> Changer le mot de passe
            </div>
            <div class="card-body">
                <form action="{{ route('profil.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Mot de passe actuel
                        </label>
                        <input type="password" name="current_password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               placeholder="••••••••">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nouveau mot de passe
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
                            Confirmer le mot de passe
                        </label>
                        <input type="password" name="password_confirmation"
                               class="form-control"
                               placeholder="Répéter le mot de passe">
                    </div>

                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-lock"></i> Changer le mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<div class="mt-3">
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour au tableau de bord
    </a>
</div>
@endsection