<?php

namespace App\Exports;

use App\Models\MouvementStock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MouvementsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return MouvementStock::with('produit', 'utilisateur')
            ->orderBy('date_mouvement', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Date',
            'Produit',
            'Type',
            'Quantité',
            'Stock Avant',
            'Stock Après',
            'Référence Opération',
            'Utilisateur',
        ];
    }

    public function map($mouvement): array
    {
        return [
            $mouvement->id,
            \Carbon\Carbon::parse($mouvement->date_mouvement)->format('d/m/Y H:i'),
            $mouvement->produit->libelle ?? '-',
            ucfirst($mouvement->type_mouvement),
            $mouvement->quantite,
            $mouvement->stock_avant,
            $mouvement->stock_apres,
            $mouvement->reference_operation ?? '-',
            $mouvement->utilisateur->name ?? '-',
        ];
    }
}