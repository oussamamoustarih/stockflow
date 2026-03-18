<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Vente;
use App\Models\MouvementStock;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // KPIs généraux
        $totalProduits    = Produit::count();
        $totalClients     = Client::count();
        $totalFournisseurs = Fournisseur::count();
        $alertesStock     = Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')->count();
        $produitsAlerte   = Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')->get();

        // CA jour / semaine / mois
        $caJour    = Vente::where('statut', 'validee')
                        ->whereDate('date_vente', today())
                        ->sum('montant_total');

        $caSemaine = Vente::where('statut', 'validee')
                        ->whereBetween('date_vente', [
                            Carbon::now()->startOfWeek(),
                            Carbon::now()->endOfWeek()
                        ])
                        ->sum('montant_total');

        $caMois    = Vente::where('statut', 'validee')
                        ->whereMonth('date_vente', now()->month)
                        ->whereYear('date_vente', now()->year)
                        ->sum('montant_total');

        // Nombre de ventes aujourd'hui
        $ventesJour = Vente::where('statut', 'validee')
                        ->whereDate('date_vente', today())
                        ->count();

        // Top 5 produits les plus vendus
        $topProduits = \App\Models\LigneVente::selectRaw('produit_id, SUM(quantite) as total_vendu')
                        ->groupBy('produit_id')
                        ->orderByDesc('total_vendu')
                        ->take(5)
                        ->with('produit')
                        ->get();

        // Top 10 clients par CA (R4)
        $topClients = Client::withSum(['ventes' => function($q) {
                            $q->where('statut', 'validee');
                        }], 'montant_total')
                        ->orderByDesc('ventes_sum_montant_total')
                        ->take(10)
                        ->get();

        // Clients inactifs > 60 jours (R4)
        $clientsInactifs = Client::whereHas('ventes', function($q) {
                                $q->where('statut', 'validee');
                            })
                            ->whereDoesntHave('ventes', function($q) {
                                $q->where('statut', 'validee')
                                  ->where('date_vente', '>=', Carbon::now()->subDays(60));
                            })
                            ->take(10)
                            ->get();

        // Données graphique Chart.js — 30 derniers jours
        $labels = [];
        $dataCA = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d/m');
            $dataCA[] = Vente::where('statut', 'validee')
                            ->whereDate('date_vente', $date->toDateString())
                            ->sum('montant_total');
        }

        return view('dashboard', compact(
            'totalProduits', 'totalClients', 'totalFournisseurs',
            'alertesStock', 'produitsAlerte',
            'caJour', 'caSemaine', 'caMois', 'ventesJour',
            'topProduits', 'topClients', 'clientsInactifs',
            'labels', 'dataCA'
        ));
    }
}