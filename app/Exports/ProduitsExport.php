<?php

namespace App\Exports;

use App\Models\Produit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProduitsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Produit::with('categorie', 'marque')
            ->orderBy('libelle')
            ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Référence',
            'Libellé',
            'Catégorie',
            'Marque',
            'Prix Achat (DH)',
            'Prix Vente (DH)',
            'Marge (DH)',
            'Stock Actuel',
            'Seuil Alerte',
            'Statut Stock',
        ];
    }

    public function map($produit): array
    {
        return [
            $produit->id,
            $produit->reference,
            $produit->libelle,
            $produit->categorie->nom ?? '-',
            $produit->marque->nom ?? '-',
            number_format($produit->prix_achat, 2),
            number_format($produit->prix_vente, 2),
            number_format($produit->calculerMarge(), 2),
            $produit->quantite_stock,
            $produit->seuil_alerte,
            $produit->quantite_stock <= $produit->seuil_alerte ? 'ALERTE' : 'Normal',
        ];
    }
}