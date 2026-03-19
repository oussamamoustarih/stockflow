@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people-fill"></i> Liste des utilisateurs</span>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Nouvel utilisateur
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <i class="bi bi-person-circle"></i>
                        {{ $user->name }}
                        @if($user->id === auth()->id())
                            <span class="badge bg-info ms-1">Vous</span>
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge bg-danger">Administrateur</span>
                        @elseif($user->role === 'gestionnaire')
                            <span class="badge bg-primary">Gestionnaire</span>
                        @elseif($user->role === 'vendeur')
                            <span class="badge bg-success">Vendeur</span>
                        @else
                            <span class="badge bg-warning text-dark">Manager</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}"
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user) }}"
                              method="POST" class="d-inline confirm-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger btn-confirm btn-delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        Aucun utilisateur trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection