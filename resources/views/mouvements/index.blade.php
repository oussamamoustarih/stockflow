@extends('layouts.app')

@section('title', 'Mouvements de Stock')

@section('content')

{{-- Filtres --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form action="{{ route('mouvements.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm">
                    <option value="">Tous les types</option>
                    <option value="entree" {{ request('type') == 'entree' ? 'selected' : '' }}>
                        Entrée
                    </option>
                    <option value="sortie" {{ request('type') == 'sortie' ? 'selected' : '' }}>
                        Sortie
                    </option>
                    <option value="ajustement" {{ request('type') == 'ajustement' ? 'selected' : '' }}>
                        Ajustement
                    </option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="produit_id" class="form-select form-select-sm">
                    <option value="">Tous les produits</option>
                    @foreach($produits as $p)
                        <option value="{{ $p->id }}" {{ request('produit_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_debut" class="form-control form-control-sm"
                       value="{{ request('date_debut') }}" placeholder="Date début">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_fin" class="form-control form-control-sm"
                       value="{{ request('date_fin') }}" placeholder="Date fin">
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search"></i> Filtrer
                </button>
                <a href="{{ route('mouvements.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="bi bi-arrow-left-right"></i> Historique des mouvements de stock</span>
    <a href="{{ route('mouvements.export.excel') }}" class="btn btn-success btn-sm">
        <i class="bi bi-file-earmark-excel"></i> Export Excel
    </a>
</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Produit</th>
                    <th>Type</th>
                    <th>Quantité</th>
                    <th>Stock avant</th>
                    <th>Stock après</th>
                    <th>Référence</th>
                    <th>Utilisateur</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mouvements as $mouvement)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($mouvement->date_mouvement)->format('d/m/Y H:i') }}</td>
                    <td>{{ $mouvement->produit->libelle ?? '-' }}</td>
                    <td>
                        @if($mouvement->type_mouvement == 'entree')
                            <span class="badge bg-success">
                                <i class="bi bi-arrow-down"></i> Entrée
                            </span>
                        @elseif($mouvement->type_mouvement == 'sortie')
                            <span class="badge bg-danger">
                                <i class="bi bi-arrow-up"></i> Sortie
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-pencil"></i> Ajustement
                            </span>
                        @endif
                    </td>
                    <td><strong>{{ $mouvement->quantite }}</strong></td>
                    <td>{{ $mouvement->stock_avant }}</td>
                    <td>{{ $mouvement->stock_apres }}</td>
                    <td><small class="text-muted">{{ $mouvement->reference_operation ?? '-' }}</small></td>
                    <td>{{ $mouvement->utilisateur->name ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        Aucun mouvement trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($mouvements->hasPages())
    <div class="card-footer">
        {{ $mouvements->links() }}
    </div>
    @endif
</div>
@endsection