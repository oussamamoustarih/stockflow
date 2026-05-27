<?php

namespace App\Http\Controllers;

use App\Exports\VentesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Vente;
use App\Models\LigneVente;
use App\Models\Produit;
use App\Models\Client;
use App\Models\Categorie;
use App\Models\MouvementStock;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class VenteController extends Controller
{
    // Affiche la liste des ventes (C'est cette méthode qui manquait !)
    public function index()
    {
        $ventes = Vente::with('client', 'vendeur')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('ventes.index', compact('ventes'));
    }

    public function create()
    {
        $categories = Categorie::with('produits')->orderBy('nom')->get();
        $clients = Client::orderBy('nom')->get();
        return view('ventes.create', compact('categories', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mode_paiement'         => 'required|in:especes,carte',
            'produits'              => 'required|array|min:1',
            'produits.*.produit_id' => 'required|exists:produits,id',
            'produits.*.quantite'   => 'required|integer|min:1',
            'produits.*.prix'       => 'required|numeric|min:0',
        ]);

        foreach ($request->produits as $p) {
            $produit = Produit::find($p['produit_id']);
            if (!$produit->verifierStockDisponible($p['quantite'])) {
                return back()->with('error', 'Stock insuffisant pour : ' . $produit->libelle);
            }
        }

        $vente = null;

        DB::transaction(function () use ($request, &$vente) {
            $montantTotal = 0;
            foreach ($request->produits as $p) {
                $montantTotal += $p['quantite'] * $p['prix'];
            }

            $vente = Vente::create([
                'client_id'     => $request->client_id ?: null,
                'vendeur_id'    => auth()->id(),
                'date_vente'    => now(),
                'montant_total' => $montantTotal,
                'mode_paiement' => $request->mode_paiement,
                'statut'        => 'validee',
            ]);

            foreach ($request->produits as $p) {
                LigneVente::create([
                    'vente_id'      => $vente->id,
                    'produit_id'    => $p['produit_id'],
                    'quantite'      => $p['quantite'],
                    'prix_unitaire' => $p['prix'],
                    'sous_total'    => $p['quantite'] * $p['prix'],
                ]);

                $produit = Produit::find($p['produit_id']);
                $stockAvant = $produit->quantite_stock;
                $produit->decrement('quantite_stock', $p['quantite']);

                MouvementStock::create([
                    'produit_id'          => $p['produit_id'],
                    'type_mouvement'      => 'sortie',
                    'quantite'            => $p['quantite'],
                    'stock_avant'         => $stockAvant,
                    'stock_apres'         => $produit->fresh()->quantite_stock,
                    'reference_operation' => 'vente_' . $vente->id,
                    'utilisateur_id'      => auth()->id(),
                    'date_mouvement'      => now(),
                ]);
            }
        });

        return redirect()->route('ventes.show', $vente->id)->with('success', 'Vente enregistrée.');
    }

    public function show(Vente $vente)
    {
        $vente->load('client', 'vendeur', 'lignes.produit');
        return view('ventes.show', compact('vente'));
    }

    public function genererFacture(Vente $vente)
    {
        $vente->load('client', 'vendeur', 'lignes.produit');
        date_default_timezone_set('Africa/Casablanca');

        $totalHT = $vente->montant_total;
        $tva = $totalHT * 0.20;
        $totalTTC = $totalHT + $tva;
        $totalEnLettres = $this->chiffreEnLettres($totalTTC);

        $numeroFacture = 'FAC-' . $vente->created_at->format('Ymd') . '-' . str_pad($vente->id, 4, '0', STR_PAD_LEFT);

        $pdf = Pdf::loadView('ventes.facture', [
            'vente' => $vente,
            'numeroFacture' => $numeroFacture,
            'totalTTC' => $totalTTC,
            'totalEnLettres' => $totalEnLettres
        ]);

        return $pdf->download('facture-' . $numeroFacture . '.pdf');
    }

    private function chiffreEnLettres($montant)
{
    // On force l'utilisation d'un format simple si l'extension Intl est absente
    if (!class_exists('NumberFormatter')) {
        return number_format($montant, 2, ',', ' ') . " Dirhams";
    }

    $f = new \NumberFormatter("fr", \NumberFormatter::SPELLOUT);
    
    // Séparation partie entière et décimale
    $entier = floor($montant);
    $decimal = round(($montant - $entier) * 100);

    $resultat = $f->format($entier) . " Dirhams";
    
    if ($decimal > 0) {
        $resultat .= " et " . $f->format($decimal) . " Centimes";
    }

    return ucfirst($resultat);
}

    public function destroy(Vente $vente)
    {
        if ($vente->statut === 'validee') {
            return redirect()->route('ventes.index')->with('error', 'Impossible de supprimer.');
        }
        $vente->delete();
        return redirect()->route('ventes.index')->with('success', 'Supprimée.');
    }

    public function exportExcel()
    {
        return Excel::download(new VentesExport, 'ventes-' . now()->format('Ymd') . '.xlsx');
    }
}