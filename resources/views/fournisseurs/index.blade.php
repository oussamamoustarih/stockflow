@extends('layouts.app')

@section('title', 'Fournisseurs')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-truck"></i> Liste des fournisseurs</span>
        <a href="{{ route('fournisseurs.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Nouveau fournisseur
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fournisseurs as $fournisseur)
                <tr>
                    <td>{{ $fournisseur->id }}</td>
                    <td>{{ $fournisseur->nom }}</td>
                    <td>{{ $fournisseur->email ?? '-' }}</td>
                    <td>{{ $fournisseur->telephone ?? '-' }}</td>
                    <td>{{ $fournisseur->adresse ?? '-' }}</td>
                    <td>
                        <a href="{{ route('fournisseurs.show', $fournisseur) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('fournisseurs.edit', $fournisseur) }}"
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('fournisseurs.destroy', $fournisseur) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Supprimer ce fournisseur ?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        Aucun fournisseur trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($fournisseurs->hasPages())
    <div class="card-footer">
        {{ $fournisseurs->links() }}
    </div>
    @endif
</div>
@endsection