<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Vente;
use App\Models\MouvementStock;
use App\Exports\DashboardReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $data = $this->buildDashboardData();

        return view('dashboard', $data);
    }

    public function exportExcel()
    {
        $data = $this->buildDashboardData();

        return Excel::download(
            new DashboardReportExport($data),
            'rapport-dashboard-' . now()->format('Ymd') . '.xlsx'
        );
    }

    public function exportPdf()
    {
        $data = $this->buildDashboardData();

        $pdf = Pdf::loadView('dashboard.export_pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('rapport-dashboard-' . now()->format('Ymd') . '.pdf');
    }

    private function buildDashboardData(): array
    {
        $totalProduits     = Produit::count();
        $totalClients      = Client::count();
        $totalFournisseurs = Fournisseur::count();
        $alertesStock      = Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')->count();
        $produitsAlerte    = Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')->get();

        $caJour = Vente::where('statut', 'validee')
            ->whereDate('date_vente', today())
            ->sum('montant_total');

        $caSemaine = Vente::where('statut', 'validee')
            ->whereBetween('date_vente', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ])
            ->sum('montant_total');

        $caMois = Vente::where('statut', 'validee')
            ->whereMonth('date_vente', now()->month)
            ->whereYear('date_vente', now()->year)
            ->sum('montant_total');

        $ventesJour = Vente::where('statut', 'validee')
            ->whereDate('date_vente', today())
            ->count();

        $topProduits = \App\Models\LigneVente::selectRaw('produit_id, SUM(quantite) as total_vendu')
            ->groupBy('produit_id')
            ->orderByDesc('total_vendu')
            ->take(5)
            ->with('produit')
            ->get();

        $topClients = Client::withSum(['ventes' => function ($q) {
            $q->where('statut', 'validee');
        }], 'montant_total')
            ->orderByDesc('ventes_sum_montant_total')
            ->take(10)
            ->get();

        $clientsInactifs = Client::whereHas('ventes', function ($q) {
            $q->where('statut', 'validee');
        })
            ->whereDoesntHave('ventes', function ($q) {
                $q->where('statut', 'validee')
                    ->where('date_vente', '>=', Carbon::now()->subDays(60));
            })
            ->take(10)
            ->get();

        $premiereVente = Vente::where('statut', 'validee')
            ->orderBy('date_vente', 'asc')
            ->first();

        if ($premiereVente) {
            $dateDebut = Carbon::parse($premiereVente->date_vente)->subDay();
        } else {
            $dateDebut = Carbon::now()->subDays(6);
        }

        $minStart = Carbon::parse('2026-04-18');
        if ($dateDebut->lt($minStart)) {
            $dateDebut = $minStart->copy();
        }

        $dateFin = Carbon::now();
        $labels = [];
        $dataCA = [];
        $currentDate = $dateDebut->copy();

        while ($currentDate <= $dateFin) {
            $labels[] = $currentDate->format('d/m');
            $dataCA[] = Vente::where('statut', 'validee')
                ->whereDate('date_vente', $currentDate->toDateString())
                ->sum('montant_total');
            $currentDate->addDay();
        }

        return [
            'totalProduits' => $totalProduits,
            'totalClients' => $totalClients,
            'totalFournisseurs' => $totalFournisseurs,
            'alertesStock' => $alertesStock,
            'produitsAlerte' => $produitsAlerte,
            'caJour' => $caJour,
            'caSemaine' => $caSemaine,
            'caMois' => $caMois,
            'ventesJour' => $ventesJour,
            'topProduits' => $topProduits,
            'topClients' => $topClients,
            'clientsInactifs' => $clientsInactifs,
            'labels' => $labels,
            'dataCA' => $dataCA,
            'metrics' => [
                'Total produits' => $totalProduits,
                'Total clients' => $totalClients,
                'Total fournisseurs' => $totalFournisseurs,
                'Alertes de stock' => $alertesStock,
                'CA aujourd\'hui (DH)' => number_format($caJour, 2),
                'CA cette semaine (DH)' => number_format($caSemaine, 2),
                'CA ce mois (DH)' => number_format($caMois, 2),
                'Ventes aujourd\'hui' => $ventesJour,
            ],
        ];
    }
}
