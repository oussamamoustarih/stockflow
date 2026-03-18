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

class VenteController extends Controller
{
    public function index()
    {
        $ventes = Vente::with('client', 'vendeur')
            ->orderBy('created_at')
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
        ], [
            'mode_paiement.required' => 'Le mode de paiement est obligatoire.',
            'produits.required'      => 'Ajoutez au moins un produit.',
        ]);

        // Vérifier stock AVANT validation
        foreach ($request->produits as $p) {
            $produit = Produit::find($p['produit_id']);
            if (!$produit->verifierStockDisponible($p['quantite'])) {
                return back()->with('error',
                    'Stock insuffisant pour le produit : ' . $produit->libelle .
                    ' (stock disponible : ' . $produit->quantite_stock . ')'
                );
            }
        }

        $vente = null;

        DB::transaction(function () use ($request, &$vente) {

            // Calculer montant total
            $montantTotal = 0;
            foreach ($request->produits as $p) {
                $montantTotal += $p['quantite'] * $p['prix'];
            }

            // Créer la vente
            $vente = Vente::create([
                'client_id'     => $request->client_id ?: null,
                'vendeur_id'    => auth()->id(),
                'date_vente'    => now(),
                'montant_total' => $montantTotal,
                'mode_paiement' => $request->mode_paiement,
                'statut'        => 'validee',
            ]);

            // Pour chaque produit du panier
            foreach ($request->produits as $p) {
                // 1. Créer ligne vente
                LigneVente::create([
                    'vente_id'      => $vente->id,
                    'produit_id'    => $p['produit_id'],
                    'quantite'      => $p['quantite'],
                    'prix_unitaire' => $p['prix'],
                    'sous_total'    => $p['quantite'] * $p['prix'],
                ]);

                // 2. Diminuer stock
                /** @var Produit $produit */
                $produit = Produit::find($p['produit_id']);
                $stockAvant = $produit->quantite_stock;
                $produit->decrement('quantite_stock', $p['quantite']);

                // 3. Mouvement stock automatique (R1)
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

        return redirect()->route('ventes.show', $vente->id)
            ->with('success', 'Vente enregistrée avec succès.');
    }

    public function show(Vente $vente)
    {
        $vente->load('client', 'vendeur', 'lignes.produit');
        return view('ventes.show', compact('vente'));
    }

    public function genererFacture(Vente $vente)
    {
        $vente->load('client', 'vendeur', 'lignes.produit');

        // Numéro facture : FAC-YYYYMMDD-XXXX
        $numeroFacture = 'FAC-' . $vente->created_at->format('Ymd') . '-' . str_pad($vente->id, 4, '0', STR_PAD_LEFT);

        $pdf = Pdf::loadView('ventes.facture', compact('vente', 'numeroFacture'));

        return $pdf->download('facture-' . $numeroFacture . '.pdf');
    }

    public function destroy(Vente $vente)
    {
        if ($vente->statut === 'validee') {
            return redirect()->route('ventes.index')
                ->with('error', 'Impossible de supprimer une vente validée.');
        }
        $vente->delete();
        return redirect()->route('ventes.index')
            ->with('success', 'Vente supprimée avec succès.');
    }
    public function exportExcel()
    {
        return Excel::download(new VentesExport, 'ventes-' . now()->format('Ymd') . '.xlsx');
    }
}