@extends('layouts.app')

@section('title', 'Nouvelle Vente')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-cash-register"></i> Interface Caisse
    </div>
    <div class="card-body">
        <form action="{{ route('ventes.store') }}" method="POST" id="formVente">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Client</label>
                    <select name="client_id" class="form-select">
                        <option value="">-- Client anonyme --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->nom }} {{ $client->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        Mode de paiement <span class="text-danger">*</span>
                    </label>
                    <select name="mode_paiement"
                            class="form-select @error('mode_paiement') is-invalid @enderror">
                        <option value="">-- Choisir --</option>
                        <option value="especes">Espèces</option>
                        <option value="carte">Carte bancaire</option>
                    </select>
                    @error('mode_paiement')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Sélection produits par catégorie --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">
                        Sélectionner une catégorie
                    </label>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($categories as $cat)
                            <button type="button"
                                    class="btn btn-outline-primary btn-sm btnCategorie"
                                    data-categorie="{{ $cat->id }}">
                                {{ $cat->nom }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Produits de la catégorie --}}
                    <div id="listeProduits" class="row g-2">
                        @foreach($categories as $cat)
                            <div class="produits-categorie d-none row g-2"
                                 data-categorie="{{ $cat->id }}">
                                @foreach($cat->produits as $produit)
                                    <div class="col-6 col-lg-3">
                                        <div class="card h-100 produit-card"
                                             style="cursor:pointer;"
                                             data-id="{{ $produit->id }}"
                                             data-nom="{{ $produit->libelle }}"
                                             data-prix="{{ $produit->prix_vente }}"
                                             data-stock="{{ $produit->quantite_stock }}">
                                            <div class="card-body p-2 text-center">
                                                @if($produit->image_url)
                                                    <img src="{{ asset('storage/' . $produit->image_url) }}"
                                                         height="50"
                                                         style="object-fit:cover;border-radius:5px;"
                                                         class="mb-1">
                                                @else
                                                    <i class="bi bi-box-seam fs-3 text-muted"></i>
                                                @endif
                                                <div class="small fw-semibold">{{ $produit->libelle }}</div>
                                                <div class="text-primary small">
                                                    {{ number_format($produit->prix_vente, 2) }} DH
                                                </div>
                                                <div class="text-muted small">
                                                    Stock : {{ $produit->quantite_stock }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Panier --}}
            <div class="card mb-3">
                <div class="card-header">
                    <i class="bi bi-cart"></i> Panier
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0" id="tablePanier">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Prix unitaire</th>
                                <th>Quantité</th>
                                <th>Sous-total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="lignesPanier">
                            <tr id="panierVide">
                                <td colspan="5" class="text-center text-muted py-3">
                                    Cliquez sur un produit pour l'ajouter
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total :</td>
                                <td colspan="2">
                                    <span id="totalVente" class="fw-bold text-primary fs-5">
                                        0.00 DH
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @error('produits')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div id="champsHidden"></div>

            <div class="d-flex flex-column flex-sm-row gap-2">
                <button type="submit" class="btn btn-success btn-lg" id="btnValider">
                    <i class="bi bi-check-lg"></i> Valider la vente
                </button>
                <a href="{{ route('ventes.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let panier = [];

// Afficher produits par catégorie
document.querySelectorAll('.btnCategorie').forEach(btn => {
    btn.addEventListener('click', function() {
        const catId = this.dataset.categorie;

        // Activer bouton
        document.querySelectorAll('.btnCategorie').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        // Afficher produits
        document.querySelectorAll('.produits-categorie').forEach(div => {
            div.classList.add('d-none');
        });
        document.querySelector(`.produits-categorie[data-categorie="${catId}"]`)
                .classList.remove('d-none');
    });
});

// Ajouter produit au panier
document.querySelectorAll('.produit-card').forEach(card => {
    card.addEventListener('click', function() {
        const id = parseInt(this.dataset.id);
        const nom = this.dataset.nom;
        const prix = parseFloat(this.dataset.prix);
        const stock = parseInt(this.dataset.stock);

        const existant = panier.find(p => p.id === id);
        if (existant) {
            if (existant.quantite < stock) {
                existant.quantite++;
            } else {
                alert('Stock insuffisant pour ce produit !');
                return;
            }
        } else {
            if (stock <= 0) {
                alert('Ce produit est en rupture de stock !');
                return;
            }
            panier.push({ id, nom, prix, quantite: 1, stock });
        }
        afficherPanier();
    });
});

function afficherPanier() {
    const tbody = document.getElementById('lignesPanier');
    const hidden = document.getElementById('champsHidden');

    tbody.innerHTML = '';
    hidden.innerHTML = '';

    if (panier.length === 0) {
        tbody.innerHTML = `
            <tr id="panierVide">
                <td colspan="5" class="text-center text-muted py-3">
                    Cliquez sur un produit pour l'ajouter
                </td>
            </tr>`;
        document.getElementById('totalVente').textContent = '0.00 DH';
        return;
    }

    let total = 0;
    panier.forEach((item, index) => {
        const sousTotal = item.quantite * item.prix;
        total += sousTotal;

        tbody.innerHTML += `
            <tr>
                <td>${item.nom}</td>
                <td>${item.prix.toFixed(2)} DH</td>
                <td>
                    <input type="number" class="form-control form-control-sm"
                           style="width:80px; min-width:80px;"
                           value="${item.quantite}" min="1" max="${item.stock}"
                           onchange="modifierQuantite(${index}, this.value)">
                </td>
                <td>${sousTotal.toFixed(2)} DH</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger"
                            onclick="supprimerProduit(${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>`;

        // Champs hidden pour le formulaire
        hidden.innerHTML += `
            <input type="hidden" name="produits[${index}][produit_id]" value="${item.id}">
            <input type="hidden" name="produits[${index}][quantite]" value="${item.quantite}">
            <input type="hidden" name="produits[${index}][prix]" value="${item.prix}">`;
    });

    document.getElementById('totalVente').textContent = total.toFixed(2) + ' DH';
}

function modifierQuantite(index, valeur) {
    const qte = parseInt(valeur);
    if (qte >= 1 && qte <= panier[index].stock) {
        panier[index].quantite = qte;
    }
    afficherPanier();
}

function supprimerProduit(index) {
    panier.splice(index, 1);
    afficherPanier();
}
</script>
@endpush
