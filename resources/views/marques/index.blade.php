@extends('layouts.app')

@section('title', 'Marques')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-award"></i> Liste des marques</span>
        <a href="{{ route('marques.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Nouvelle marque
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Nb Produits</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($marques as $marque)
                <tr>
                    <td>{{ $marque->id }}</td>
                    <td>{{ $marque->nom }}</td>
                    <td>{{ $marque->description ?? '-' }}</td>
                    <td>
                        <span class="badge bg-primary">
                            {{ $marque->produits()->count() }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('marques.edit', $marque) }}"
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('marques.destroy', $marque) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Supprimer cette marque ?')">
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
                    <td colspan="5" class="text-center text-muted py-4">
                        Aucune marque trouvée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($marques->hasPages())
    <div class="card-footer">
        {{ $marques->links() }}
    </div>
    @endif
</div>
@endsection