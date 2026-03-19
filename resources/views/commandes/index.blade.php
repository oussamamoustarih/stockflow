@extends('layouts.app')

@section('title', 'Commandes')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clipboard-check"></i> Liste des commandes</span>
        <a href="{{ route('commandes.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Nouvelle commande
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Date commande</th>
                    <th>Livraison prévue</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commandes as $commande)
                <tr>
                    <td>{{ $commande->id }}</td>
                    <td>{{ $commande->client->nom ?? '-' }} {{ $commande->client->prenom ?? '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y') }}</td>
                    <td>{{ $commande->date_livraison_prevue ? \Carbon\Carbon::parse($commande->date_livraison_prevue)->format('d/m/Y') : '-' }}</td>
                    <td>{{ number_format($commande->montant_total, 2) }} DH</td>
                    <td>
                        @if($commande->statut == 'en_attente')
                            <span class="badge bg-warning text-dark">En attente</span>
                        @elseif($commande->statut == 'validee')
                            <span class="badge bg-primary">Validée</span>
                        @elseif($commande->statut == 'livree')
                            <span class="badge bg-success">Livrée</span>
                        @else
                            <span class="badge bg-danger">Annulée</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('commandes.show', $commande) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if($commande->statut == 'en_attente')
                            <form action="{{ route('commandes.valider', $commande) }}"
                                  method="POST" class="d-inline confirm-form">
                                @csrf
                                <button type="button" class="btn btn-sm btn-primary btn-confirm btn-valider">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            <form action="{{ route('commandes.destroy', $commande) }}"
                                  method="POST" class="d-inline confirm-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger btn-confirm btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endif
                        @if($commande->statut == 'validee')
                            <form action="{{ route('commandes.livrer', $commande) }}"
                                  method="POST" class="d-inline confirm-form">
                                @csrf
                                <button type="button" class="btn btn-sm btn-success btn-confirm btn-livrer">
                                    <i class="bi bi-truck"></i>
                                </button>
                            </form>
                        @endif
                        @if($commande->statut == 'livree')
                            <a href="{{ route('commandes.bl', $commande) }}"
                               class="btn btn-sm btn-dark">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        Aucune commande trouvée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($commandes->hasPages())
    <div class="card-footer">
        {{ $commandes->links() }}
    </div>
    @endif
</div>
@endsection