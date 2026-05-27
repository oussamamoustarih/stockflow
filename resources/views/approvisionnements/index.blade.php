@extends('layouts.app')

@section('title', 'Approvisionnements')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-cart-plus"></i> Liste des approvisionnements</span>
        <a href="{{ route('approvisionnements.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Nouvel approvisionnement
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fournisseur</th>
                    <th>Date</th>
                    <th>Montant Total</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($approvisionnements as $appro)
                <tr>
                    <td>{{ $appro->id }}</td>
                    <td>{{ $appro->fournisseur->nom ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($appro->date_approvisionnement)->format('d/m/Y') }}</td>
                    <td>{{ number_format($appro->montant_total, 2) }} DH</td>
                    <td>
                        @if($appro->statut == 'valide')
                            <span class="badge bg-success">Validé</span>
                        @elseif($appro->statut == 'en_cours')
                            <span class="badge bg-warning text-dark">En cours</span>
                        @else
                            <span class="badge bg-danger">Annulé</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('approvisionnements.show', $appro) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if($appro->statut == 'en_cours')
                            <form action="{{ route('approvisionnements.valider', $appro) }}"
                                  method="POST" class="d-inline confirm-form">
                                @csrf
                                <button type="button" class="btn btn-sm btn-success btn-confirm btn-valider">
                                    <i class="bi bi-check-lg"></i> Valider
                                </button>
                            </form>
                            <form action="{{ route('approvisionnements.destroy', $appro) }}"
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
                        Aucun approvisionnement trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($approvisionnements->hasPages())
    <div class="card-footer">
        {{ $approvisionnements->links() }}
    </div>
    @endif
</div>
@endsection