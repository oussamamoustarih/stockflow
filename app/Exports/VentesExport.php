<?php

namespace App\Exports;

use App\Models\Vente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VentesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Vente::with('client', 'vendeur')
    ->where('statut', 'validee')
    ->orderBy('id', 'asc')
    ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Date',
            'Client',
            'Vendeur',
            'Montant Total (DH)',
            'Mode Paiement',
            'Statut',
        ];
    }

    public function map($vente): array
    {
        return [
            $vente->id,
            \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y H:i'),
            $vente->client ? $vente->client->nom . ' ' . $vente->client->prenom : 'Anonyme',
            $vente->vendeur->name ?? '-',
            number_format($vente->montant_total, 2),
            $vente->mode_paiement == 'especes' ? 'Espèces' : 'Carte bancaire',
            'Validée',
        ];
    }
}