<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DashboardReportExport implements FromArray, WithHeadings
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [
            ['Section', 'Champ', 'Valeur'],
        ];

        $metrics = $this->data['metrics'];
        foreach ($metrics as $label => $value) {
            $rows[] = ['Résumé', $label, $value];
        }

        $rows[] = ['Top 5 produits vendus', 'Produit', 'Qté vendue'];
        foreach ($this->data['topProduits'] as $ligne) {
            $rows[] = [
                'Top 5 produits vendus',
                $ligne->produit->libelle ?? '-',
                $ligne->total_vendu,
            ];
        }

        $rows[] = ['Top 10 meilleurs clients', 'Client', 'CA total (DH)'];
        foreach ($this->data['topClients'] as $client) {
            $rows[] = [
                'Top 10 meilleurs clients',
                trim(($client->nom ?? '-') . ' ' . ($client->prenom ?? '-')),
                number_format($client->ventes_sum_montant_total ?? 0, 2),
            ];
        }

        $rows[] = ['Clients inactifs (+ 60 jours)', 'Client', 'Téléphone'];
        foreach ($this->data['clientsInactifs'] as $client) {
            $rows[] = [
                'Clients inactifs (+ 60 jours)',
                trim(($client->nom ?? '-') . ' ' . ($client->prenom ?? '-')),
                $client->telephone ?? '-',
            ];
        }

        $rows[] = ['Produits en alerte', 'Produit', 'Stock actuel / Seuil'];
        foreach ($this->data['produitsAlerte'] as $produit) {
            $rows[] = [
                'Produits en alerte',
                $produit->libelle,
                $produit->quantite_stock . ' / ' . $produit->seuil_alerte,
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Section', 'Champ', 'Valeur'];
    }
}
