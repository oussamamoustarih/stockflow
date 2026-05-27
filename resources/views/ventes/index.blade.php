@extends('layouts.app')

@section('title', 'Ventes')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="bi bi-cash-register"></i> Historique des ventes</span>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('ventes.export.excel') }}" class="btn btn-success btn-sm">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
    <a href="{{ route('ventes.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Nouvelle vente
    </a>
    </div>
</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Vendeur</th>
                    <th>Montant</th>
                    <th>Paiement</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventes as $vente)
                <tr>
                    <td>{{ $vente->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y H:i') }}</td>
                    <td>{{ $vente->client ? $vente->client->nom . ' ' . $vente->client->prenom : 'Client anonyme' }}</td>
                    <td>{{ $vente->vendeur->name ?? '-' }}</td>
                    <td>{{ number_format($vente->montant_total, 2) }} DH</td>
                    <td>
                        @if($vente->mode_paiement == 'especes')
                            <span class="badge bg-success">Espèces</span>
                        @else
                            <span class="badge bg-info">Carte</span>
                        @endif
                    </td>
                    <td>
                        @if($vente->statut == 'validee')
                            <span class="badge bg-success">Validée</span>
                        @elseif($vente->statut == 'en_cours')
                            <span class="badge bg-warning text-dark">En cours</span>
                        @else
                            <span class="badge bg-danger">Annulée</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('ventes.show', $vente) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('ventes.facture', $vente) }}"
                           class="btn btn-sm btn-dark">
                            <i class="bi bi-file-pdf"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        Aucune vente trouvée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ventes->hasPages())
    <div class="card-footer">
        {{ $ventes->links() }}
    </div>
    @endif
</div>
@endsection
