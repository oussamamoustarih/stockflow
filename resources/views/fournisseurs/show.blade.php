@extends('layouts.app')

@section('title', 'Détail Fournisseur')

@section('content')
<div class="row g-4">

    {{-- Fiche fournisseur --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-truck"></i> {{ $fournisseur->nom }}
            </div>
            <div class="card-body">
                <p><i class="bi bi-envelope"></i> {{ $fournisseur->email ?? '-' }}</p>
                <p><i class="bi bi-telephone"></i> {{ $fournisseur->telephone ?? '-' }}</p>
                <p><i class="bi bi-geo-alt"></i> {{ $fournisseur->adresse ?? '-' }}</p>
                <a href="{{ route('fournisseurs.edit', $fournisseur) }}"
                   class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
            </div>
        </div>
    </div>

    {{-- Historique approvisionnements --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i> Historique des approvisionnements
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Montant Total</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($approvisionnements as $appro)
                        <tr>
                            <td>{{ $appro->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($appro->date_approvisionnement)->format('d/m/Y') }}</td>
                            <td>{{ number_format($appro->montant_total, 2) }} DH</td>
                            <td>
                                @if($appro->statut == 'valide')
                                    <span class="badge bg-success">Validé</span>
                                @elseif($appro->statut == 'en_cours')
                                    <span class="badge bg-warning">En cours</span>
                                @else
                                    <span class="badge bg-danger">Annulé</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
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
    </div>

</div>

<div class="mt-3">
    <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>
@endsection