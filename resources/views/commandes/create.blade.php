@extends('layouts.app')

@section('title', 'Nouvelle Commande')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-plus-lg"></i> Nouvelle commande
    </div>
    <div class="card-body">
        <form action="{{ route('commandes.store') }}" method="POST" id="formCommande">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Client <span class="text-danger">*</span></label>
                    <select name="client_id"
                            class="form-select @error('client_id') is-invalid @enderror">
                        <option value="">-- Choisir --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->nom }} {{ $client->prenom }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Date commande <span class="text-danger">*</span></label>
                    <input type="date" name="date_commande"
                           class="form-control @error('date_commande') is-invalid @enderror"
                           value="{{ old('date_commande', date('Y-m-d')) }}">
                    @error('date_commande')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Date livraison prévue</label>
                    <input type="date" name="date_livraison_prevue"
                           class="form-control"
                           value="{{ old('date_livraison_prevue') }}">
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-list-ul"></i> Produits</span>
                    <button type="button" class="btn btn-sm btn-primary" id="ajouterLigne">
                        <i class="bi bi-plus-lg"></i> Ajouter un produit
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
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
                                    <select name="produits[0][produit_id]"
                                            class="form-select form-select-sm select-produit">
                                        <option value="">-- Choisir --</option>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit->id }}"
                                                    data-prix="{{ $produit->prix_vente }}"
                                                    data-stock="{{ $produit->quantite_stock }}"
                                                    {{ $produit->quantite_stock <= 0 ? 'disabled' : '' }}>
                                                {{ $produit->libelle }} ({{ $produit->reference }}) - Stock: {{ $produit->quantite_stock }}
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
                                    <input type="number" step="0.01"
                                           name="produits[0][prix_unitaire]"
                                           class="form-control form-control-sm prix"
                                           min="0" value="0">
                                </td>
                                <td>
                                    <span class="sous-total fw-bold">0.00 DH</span>
                                </td>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-danger supprimerLigne">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total :</td>
                                <td>
                                    <span id="montantTotal" class="fw-bold text-primary">0.00 DH</span>
                                </td>
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
                <a href="{{ route('commandes.index') }}" class="btn btn-secondary">
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
const produitsData = @json($produits);

function getProduitOptions() {
    let options = '<option value="">-- Choisir --</option>';

    produitsData.forEach(produit => {
        const disabled = Number(produit.quantite_stock) <= 0 ? 'disabled' : '';
        options += `<option value="${produit.id}" data-prix="${produit.prix_vente}" data-stock="${produit.quantite_stock}" ${disabled}>${produit.libelle} (${produit.reference}) - Stock: ${produit.quantite_stock}</option>`;
    });

    return options;
}

function updateLigneProduit(event) {
    const select = event.target;
    const selectedOption = select.options[select.selectedIndex];
    const prix = selectedOption.getAttribute('data-prix') || 0;
    const stock = parseInt(selectedOption.getAttribute('data-stock') || 0, 10);
    const row = select.closest('tr');
    const inputPrix = row.querySelector('.prix');
    const inputQuantite = row.querySelector('.quantite');

    if (inputPrix) {
        inputPrix.value = prix;
    }

    if (inputQuantite) {
        if (stock > 0) {
            inputQuantite.max = stock;
            inputQuantite.value = Math.min(parseInt(inputQuantite.value || 1, 10), stock);
            if (parseInt(inputQuantite.value || 0, 10) < 1) {
                inputQuantite.value = 1;
            }
        } else {
            inputQuantite.value = 1;
            inputQuantite.removeAttribute('max');
        }
    }

    calculerTotaux();
}

function calculerTotaux() {
    let total = 0;

    document.querySelectorAll('#lignesProduits tr').forEach(row => {
        const inputQuantite = row.querySelector('.quantite');
        let qte = parseFloat(inputQuantite?.value) || 0;
        const max = parseFloat(inputQuantite?.max);

        if (!Number.isNaN(max) && max > 0 && qte > max) {
            qte = max;
            inputQuantite.value = max;
        }

        const prix = parseFloat(row.querySelector('.prix')?.value) || 0;
        const sousTotal = qte * prix;
        const span = row.querySelector('.sous-total');

        if (span) {
            span.textContent = sousTotal.toFixed(2) + ' DH';
        }

        total += sousTotal;
    });

    document.getElementById('montantTotal').textContent = total.toFixed(2) + ' DH';
}

document.getElementById('ajouterLigne').addEventListener('click', function () {
    const tbody = document.getElementById('lignesProduits');
    const tr = document.createElement('tr');

    tr.innerHTML = `
        <td>
            <select name="produits[${ligneIndex}][produit_id]" class="form-select form-select-sm select-produit">
                ${getProduitOptions()}
            </select>
        </td>
        <td>
            <input type="number" name="produits[${ligneIndex}][quantite]" class="form-control form-control-sm quantite" min="1" value="1">
        </td>
        <td>
            <input type="number" step="0.01" name="produits[${ligneIndex}][prix_unitaire]" class="form-control form-control-sm prix" min="0" value="0">
        </td>
        <td>
            <span class="sous-total fw-bold">0.00 DH</span>
        </td>
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
        select.removeEventListener('change', updateLigneProduit);
        select.addEventListener('change', updateLigneProduit);
    });

    document.querySelectorAll('.supprimerLigne').forEach(btn => {
        btn.removeEventListener('click', supprimerLigne);
        btn.addEventListener('click', supprimerLigne);
    });

    updateDeleteButtons();
}

function supprimerLigne() {
    const rows = document.querySelectorAll('#lignesProduits tr');

    if (rows.length > 1) {
        this.closest('tr').remove();
        calculerTotaux();
        updateDeleteButtons();
    }
}

function updateDeleteButtons() {
    const rows = document.querySelectorAll('#lignesProduits tr');
    const disableDelete = rows.length === 1;

    document.querySelectorAll('.supprimerLigne').forEach(btn => {
        btn.disabled = disableDelete;
        btn.title = disableDelete
            ? 'Ajoutez une autre ligne pour pouvoir en supprimer une.'
            : 'Supprimer cette ligne';
    });
}

attacherEvenements();
</script>
@endpush
