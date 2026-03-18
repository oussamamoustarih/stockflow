<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\BonLivraison;
use App\Models\Produit;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;

class CommandeController extends Controller
{
    public function index()
    {
        $commandes = Commande::with('client')
            ->orderBy('created_at')
            ->paginate(10);
        return view('commandes.index', compact('commandes'));
    }

    public function create()
    {
        $clients = Client::orderBy('nom')->get();
        $produits = Produit::orderBy('libelle')->get();
        return view('commandes.create', compact('clients', 'produits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'              => 'required|exists:clients,id',
            'date_commande'          => 'required|date',
            'date_livraison_prevue'  => 'nullable|date|after_or_equal:date_commande',
            'produits'               => 'required|array|min:1',
            'produits.*.produit_id'  => 'required|exists:produits,id',
            'produits.*.quantite'    => 'required|integer|min:1',
            'produits.*.prix_unitaire' => 'required|numeric|min:0',
        ], [
            'client_id.required'    => 'Le client est obligatoire.',
            'date_commande.required' => 'La date est obligatoire.',
            'produits.required'     => 'Ajoutez au moins un produit.',
        ]);

        DB::transaction(function () use ($request) {

            // Calculer montant total
            $montantTotal = 0;
            foreach ($request->produits as $p) {
                $montantTotal += $p['quantite'] * $p['prix_unitaire'];
            }

            // Créer la commande
            $commande = Commande::create([
                'client_id'             => $request->client_id,
                'date_commande'         => $request->date_commande,
                'date_livraison_prevue' => $request->date_livraison_prevue,
                'montant_total'         => $montantTotal,
                'statut'                => 'en_attente',
            ]);

            // Créer les lignes
            foreach ($request->produits as $p) {
                LigneCommande::create([
                    'commande_id'   => $commande->id,
                    'produit_id'    => $p['produit_id'],
                    'quantite'      => $p['quantite'],
                    'prix_unitaire' => $p['prix_unitaire'],
                    'sous_total'    => $p['quantite'] * $p['prix_unitaire'],
                ]);
            }
        });

        return redirect()->route('commandes.index')
            ->with('success', 'Commande créée avec succès.');
    }

    public function show(Commande $commande)
    {
        $commande->load('client', 'lignes.produit', 'bonLivraison');
        return view('commandes.show', compact('commande'));
    }

    public function valider(Commande $commande)
    {
        if ($commande->statut !== 'en_attente') {
            return redirect()->route('commandes.index')
                ->with('error', 'Cette commande ne peut pas être validée.');
        }

        $commande->update(['statut' => 'validee']);

        return redirect()->route('commandes.show', $commande)
            ->with('success', 'Commande validée avec succès.');
    }

    public function livrer(Commande $commande)
    {
        if ($commande->statut !== 'validee') {
            return redirect()->route('commandes.index')
                ->with('error', 'La commande doit être validée avant la livraison.');
        }

        DB::transaction(function () use ($commande) {

            // Générer numéro BL : BL-YYYYMMDD-XXXX (R2)
            $numeroBL = 'BL-' . now()->format('Ymd') . '-' . str_pad($commande->id, 4, '0', STR_PAD_LEFT);

            // Créer le bon de livraison
            BonLivraison::create([
                'commande_id'    => $commande->id,
                'numero_bl'      => $numeroBL,
                'date_livraison' => now()->toDateString(),
            ]);

            // Mettre à jour statut commande
            $commande->update(['statut' => 'livree']);
        });

        return redirect()->route('commandes.show', $commande)
            ->with('success', 'Commande livrée — bon de livraison généré.');
    }

    public function annuler(Commande $commande)
    {
        if ($commande->statut === 'livree') {
            return redirect()->route('commandes.index')
                ->with('error', 'Impossible d\'annuler une commande livrée.');
        }

        $commande->update(['statut' => 'annulee']);

        return redirect()->route('commandes.index')
            ->with('success', 'Commande annulée.');
    }

    public function genererBL(Commande $commande)
    {
        $commande->load('client', 'lignes.produit', 'bonLivraison');

        if (!$commande->bonLivraison) {
            return redirect()->route('commandes.show', $commande)
                ->with('error', 'Aucun bon de livraison disponible.');
        }

        $pdf = Pdf::loadView('commandes.bon_livraison', compact('commande'));

        return $pdf->download('BL-' . $commande->bonLivraison->numero_bl . '.pdf');
    }

    public function destroy(Commande $commande)
    {
        if (in_array($commande->statut, ['validee', 'livree'])) {
            return redirect()->route('commandes.index')
                ->with('error', 'Impossible de supprimer une commande validée ou livrée.');
        }

        $commande->delete();

        return redirect()->route('commandes.index')
            ->with('success', 'Commande supprimée avec succès.');
    }
}