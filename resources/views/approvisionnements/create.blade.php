@extends('layouts.app')

@section('title', 'Nouvel Approvisionnement')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-plus-lg"></i> Nouvel approvisionnement
    </div>
    <div class="card-body">
        <form action="{{ route('approvisionnements.store') }}" method="POST" id="formAppro">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fournisseur <span class="text-danger">*</span></label>
                    <select name="fournisseur_id" class="form-select @error('fournisseur_id') is-invalid @enderror">
                        <option value="">-- Choisir --</option>
                        @foreach($fournisseurs as $f)
                            <option value="{{ $f->id }}" {{ old('fournisseur_id') == $f->id ? 'selected' : '' }}>
                                {{ $f->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('fournisseur_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date_approvisionnement"
                           class="form-control @error('date_approvisionnement') is-invalid @enderror"
                           value="{{ old('date_approvisionnement', date('Y-m-d')) }}">
                    @error('date_approvisionnement')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Statut <span class="text-danger">*</span></label>
                    <select name="statut" class="form-select">
                        <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="valide" {{ old('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                    </select>
                </div>
            </div>

            {{-- Lignes produits --}}
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-list-ul"></i> Produits</span>
                    <button type="button" class="btn btn-sm btn-primary" id="ajouterLigne">
                        <i class="bi bi-plus-lg"></i> Ajouter un produit
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0" id="tableProduits">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix unitaire (DH)</th>
                                <th>Sous-total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="lignesProduits">
                            <tr id="ligne_0">
                                <td>
                                    <select name="produits[0][produit_id]" class="form-select form-select-sm select-produit">
                                        <option value="">-- Choisir --</option>
                                        @foreach($produits as $p)
                                            <option value="{{ $p->id }}" data-prix="{{ $p->prix_achat }}">
                                                {{ $p->libelle }} ({{ $p->reference }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="produits[0][quantite]"
                                           class="form-control form-control-sm quantite"
                                           min="1" value="1">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="produits[0][prix_unitaire]"
                                           class="form-control form-control-sm prix"
                                           min="0" value="0">
                                </td>
                                <td>
                                    <span class="sous-total fw-bold">0.00 DH</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger supprimerLigne">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total :</td>
                                <td><span id="montantTotal" class="fw-bold text-primary">0.00 DH</span></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @error('produits')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="d-flex flex-column flex-sm-row gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Enregistrer
                </button>
                <a href="{{ route('approvisionnements.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let ligneIndex = 1;

const produits = @json($produits);

function getProduitOptions() {
    let options = '<option value="">-- Choisir --</option>';
    produits.forEach(p => {
        options += `<option value="${p.id}" data-prix="${p.prix_achat}">${p.libelle} (${p.reference})</option>`;
    });
    return options;
}

function updatePrixLigne(event) {
    const select = event.target;
    const selectedOption = select.options[select.selectedIndex];
    const prix = selectedOption.getAttribute('data-prix') || 0;
    const row = select.closest('tr');
    const inputPrix = row.querySelector('.prix');

    if (inputPrix) {
        inputPrix.value = prix;
        calculerTotaux();
    }
}

function calculerTotaux() {
    let total = 0;
    document.querySelectorAll('#lignesProduits tr').forEach(row => {
        const qte = parseFloat(row.querySelector('.quantite')?.value) || 0;
        const prix = parseFloat(row.querySelector('.prix')?.value) || 0;
        const sousTotal = qte * prix;
        const span = row.querySelector('.sous-total');
        if (span) span.textContent = sousTotal.toFixed(2) + ' DH';
        total += sousTotal;
    });
    document.getElementById('montantTotal').textContent = total.toFixed(2) + ' DH';
}

document.getElementById('ajouterLigne').addEventListener('click', function () {
    const tbody = document.getElementById('lignesProduits');
    const tr = document.createElement('tr');
    tr.id = 'ligne_' + ligneIndex;
    tr.innerHTML = `
        <td>
            <select name="produits[${ligneIndex}][produit_id]" class="form-select form-select-sm select-produit">
                ${getProduitOptions()}
            </select>
        </td>
        <td>
            <input type="number" name="produits[${ligneIndex}][quantite]"
                   class="form-control form-control-sm quantite" min="1" value="1">
        </td>
        <td>
            <input type="number" step="0.01" name="produits[${ligneIndex}][prix_unitaire]"
                   class="form-control form-control-sm prix" min="0" value="0">
        </td>
        <td><span class="sous-total fw-bold">0.00 DH</span></td>
        <td>
            <button type="button" class="btn btn-sm btn-danger supprimerLigne">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    ligneIndex++;
    attacherEvenements();
});

function attacherEvenements() {
    document.querySelectorAll('.quantite, .prix').forEach(input => {
        input.removeEventListener('input', calculerTotaux);
        input.addEventListener('input', calculerTotaux);
    });
    document.querySelectorAll('.select-produit').forEach(select => {
        select.removeEventListener('change', updatePrixLigne);
        select.addEventListener('change', updatePrixLigne);
    });
    document.querySelectorAll('.supprimerLigne').forEach(btn => {
        btn.removeEventListener('click', supprimerLigne);
        btn.addEventListener('click', supprimerLigne);
    });
}

function supprimerLigne() {
    const rows = document.querySelectorAll('#lignesProduits tr');
    if (rows.length > 1) {
        this.closest('tr').remove();
        calculerTotaux();
    }
}

attacherEvenements();
</script>
@endpush
