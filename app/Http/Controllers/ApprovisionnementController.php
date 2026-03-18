<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Approvisionnement;
use App\Models\LigneApprovisionnement;
use App\Models\Produit;
use App\Models\Fournisseur;
use App\Models\MouvementStock;

class ApprovisionnementController extends Controller
{
    public function index()
    {
        $approvisionnements = Approvisionnement::with('fournisseur')
            ->orderBy('created_at')
            ->paginate(10);
        return view('approvisionnements.index', compact('approvisionnements'));
    }

    public function create()
    {
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        $produits = Produit::orderBy('libelle')->get();
        return view('approvisionnements.create', compact('fournisseurs', 'produits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fournisseur_id'         => 'required|exists:fournisseurs,id',
            'date_approvisionnement' => 'required|date',
            'statut'                 => 'required|in:en_cours,valide,annule',
            'produits'               => 'required|array|min:1',
            'produits.*.produit_id'  => 'required|exists:produits,id',
            'produits.*.quantite'    => 'required|integer|min:1',
            'produits.*.prix_unitaire' => 'required|numeric|min:0',
        ], [
            'fournisseur_id.required' => 'Le fournisseur est obligatoire.',
            'date_approvisionnement.required' => 'La date est obligatoire.',
            'produits.required' => 'Ajoutez au moins un produit.',
            'produits.*.quantite.min' => 'La quantité doit être au moins 1.',
        ]);

        DB::transaction(function () use ($request) {

            // Calculer montant total
            $montantTotal = 0;
            foreach ($request->produits as $p) {
                $montantTotal += $p['quantite'] * $p['prix_unitaire'];
            }

            // Créer l'approvisionnement
            $appro = Approvisionnement::create([
                'fournisseur_id'         => $request->fournisseur_id,
                'date_approvisionnement' => $request->date_approvisionnement,
                'montant_total'          => $montantTotal,
                'statut'                 => $request->statut,
            ]);

            // Créer les lignes
            foreach ($request->produits as $p) {
                LigneApprovisionnement::create([
                    'approvisionnement_id' => $appro->id,
                    'produit_id'           => $p['produit_id'],
                    'quantite'             => $p['quantite'],
                    'prix_unitaire'        => $p['prix_unitaire'],
                    'sous_total'           => $p['quantite'] * $p['prix_unitaire'],
                ]);

                // Si statut = valide : augmenter stock + mouvement (R1)
                if ($request->statut === 'valide') {
                    /** @var Produit $produit */
                    $produit = Produit::find($p['produit_id']);
                    $stockAvant = $produit->quantite_stock;
                    $produit->increment('quantite_stock', $p['quantite']);

                    MouvementStock::create([
                        'produit_id'          => $p['produit_id'],
                        'type_mouvement'      => 'entree',
                        'quantite'            => $p['quantite'],
                        'stock_avant'         => $stockAvant,
                        'stock_apres'         => $produit->fresh()->quantite_stock,
                        'reference_operation' => 'appro_' . $appro->id,
                        'utilisateur_id'      => auth()->id(),
                        'date_mouvement'      => now(),
                    ]);
                }
            }
        });

        return redirect()->route('approvisionnements.index')
            ->with('success', 'Approvisionnement enregistré avec succès.');
    }

    public function show(Approvisionnement $approvisionnement)
    {
        $approvisionnement->load('fournisseur', 'lignes.produit');
        return view('approvisionnements.show', compact('approvisionnement'));
    }

    public function valider(Approvisionnement $approvisionnement)
    {
        if ($approvisionnement->statut !== 'en_cours') {
            return redirect()->route('approvisionnements.index')
                ->with('error', 'Cet approvisionnement ne peut pas être validé.');
        }

        DB::transaction(function () use ($approvisionnement) {
            foreach ($approvisionnement->lignes as $ligne) {
                /** @var Produit $produit */
                $produit = Produit::find($ligne->produit_id);
                $stockAvant = $produit->quantite_stock;
                $produit->increment('quantite_stock', $ligne->quantite);

                // Mouvement stock automatique (R1)
                MouvementStock::create([
                    'produit_id'          => $ligne->produit_id,
                    'type_mouvement'      => 'entree',
                    'quantite'            => $ligne->quantite,
                    'stock_avant'         => $stockAvant,
                    'stock_apres'         => $produit->fresh()->quantite_stock,
                    'reference_operation' => 'appro_' . $approvisionnement->id,
                    'utilisateur_id'      => auth()->id(),
                    'date_mouvement'      => now(),
                ]);
            }

            $approvisionnement->update(['statut' => 'valide']);
        });

        return redirect()->route('approvisionnements.index')
            ->with('success', 'Approvisionnement validé — stock mis à jour.');
    }

    public function destroy(Approvisionnement $approvisionnement)
    {
        if ($approvisionnement->statut === 'valide') {
            return redirect()->route('approvisionnements.index')
                ->with('error', 'Impossible de supprimer un approvisionnement validé.');
        }

        $approvisionnement->delete();

        return redirect()->route('approvisionnements.index')
            ->with('success', 'Approvisionnement supprimé avec succès.');
    }
}