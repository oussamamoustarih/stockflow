<?php

namespace App\Http\Controllers;

use App\Models\BonLivraison;
use App\Models\Client;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Produit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CommandeController extends Controller
{
    public function index()
    {
        $commandes = Commande::with('client')
            ->orderBy('created_at', 'asc')
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
            'client_id' => 'required|exists:clients,id',
            'date_commande' => 'required|date',
            'date_livraison_prevue' => 'nullable|date|after_or_equal:date_commande',
            'produits' => 'required|array|min:1',
            'produits.*.produit_id' => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'produits.*.prix_unitaire' => 'required|numeric|min:0',
        ]);

        $quantitesParProduit = [];
        foreach ($request->produits as $ligne) {
            $produitId = (int) $ligne['produit_id'];
            $quantitesParProduit[$produitId] = ($quantitesParProduit[$produitId] ?? 0) + (int) $ligne['quantite'];
        }

        $produits = Produit::whereIn('id', array_keys($quantitesParProduit))->get()->keyBy('id');
        $erreurs = [];

        foreach ($quantitesParProduit as $produitId => $quantiteDemandee) {
            $produit = $produits->get($produitId);

            if (!$produit) {
                continue;
            }

            if (!$produit->verifierStockDisponible($quantiteDemandee)) {
                $erreurs["produits.$produitId.quantite"] =
                    "Stock insuffisant pour le produit {$produit->libelle} : disponible {$produit->quantite_stock}, demandé {$quantiteDemandee}.";
            }
        }

        if (!empty($erreurs)) {
            throw ValidationException::withMessages($erreurs);
        }

        DB::transaction(function () use ($request) {
            $montantTotal = 0;
            foreach ($request->produits as $produit) {
                $montantTotal += $produit['quantite'] * $produit['prix_unitaire'];
            }

            $commande = Commande::create([
                'client_id' => $request->client_id,
                'date_commande' => $request->date_commande,
                'date_livraison_prevue' => $request->date_livraison_prevue,
                'montant_total' => $montantTotal,
                'statut' => 'en_attente',
            ]);

            foreach ($request->produits as $produit) {
                LigneCommande::create([
                    'commande_id' => $commande->id,
                    'produit_id' => $produit['produit_id'],
                    'quantite' => $produit['quantite'],
                    'prix_unitaire' => $produit['prix_unitaire'],
                    'sous_total' => $produit['quantite'] * $produit['prix_unitaire'],
                ]);
            }
        });

        return redirect()->route('commandes.index')->with('success', 'Commande créée.');
    }

    public function show(Commande $commande)
    {
        $commande->load('client', 'lignes.produit', 'bonLivraison');

        return view('commandes.show', compact('commande'));
    }

    public function genererBL(Commande $commande)
    {
        $commande->load('client', 'lignes.produit', 'bonLivraison');

        if (!$commande->bonLivraison && $commande->statut === 'livree') {
            $this->creerBonLivraison($commande);
            $commande->load('client', 'lignes.produit', 'bonLivraison');
        }

        if (!$commande->bonLivraison) {
            return back()->with('error', 'Aucun bon de livraison généré.');
        }

        Carbon::setLocale('fr');
        date_default_timezone_set('Africa/Casablanca');

        $pdf = Pdf::loadView('commandes.bon_livraison', compact('commande'));

        return $pdf->download('BL-' . $commande->bonLivraison->numero_bl . '.pdf');
    }

    public function genererFacture(Commande $commande)
    {
        $commande->load('client', 'lignes.produit');

        date_default_timezone_set('Africa/Casablanca');

        $totalHT = $commande->montant_total;
        $tva = $totalHT * 0.20;
        $totalTTC = $totalHT + $tva;

        $totalEnLettres = $this->chiffreEnLettres($totalTTC);
        $numeroFacture = 'FAC-' . now()->format('Ymd') . '-' . str_pad($commande->id, 4, '0', STR_PAD_LEFT);

        $pdf = Pdf::loadView('ventes.facture', [
            'vente' => $commande,
            'numeroFacture' => $numeroFacture,
            'totalTTC' => $totalTTC,
            'totalEnLettres' => $totalEnLettres,
        ]);

        return $pdf->download($numeroFacture . '.pdf');
    }

    /**
     * Finalise le cycle de vie de la commande en la marquant comme livrée.
     *
     * Cette méthode met à jour le statut et enregistre la date réelle de
     * livraison afin de conserver une trace exploitable dans les rapports.
     */
    public function livrer(Commande $commande)
    {
        DB::transaction(function () use ($commande) {
            $commande->update([
                'statut' => 'livree',
                'date_livraison_reelle' => now(),
            ]);

            $this->creerBonLivraison($commande);
        });

        return redirect()->back()->with(
            'success',
            'La commande a été marquée comme livrée le ' . $commande->date_livraison_reelle
        );
    }

    public function valider(Commande $commande)
    {
        $commande->update([
            'statut' => 'validee',
        ]);

        return redirect()->back()->with('success', 'Commande validée avec succès.');
    }

    public function destroy(Commande $commande)
    {
        $commande->delete();

        return redirect()->route('commandes.index')->with('success', 'Commande supprimée avec succès.');
    }

    private function chiffreEnLettres($montant)
    {
        if (!class_exists('NumberFormatter')) {
            return number_format($montant, 2, ',', ' ') . ' Dirhams';
        }

        $formatter = new \NumberFormatter('fr', \NumberFormatter::SPELLOUT);
        $entier = floor($montant);
        $decimal = round(($montant - $entier) * 100);

        $resultat = $formatter->format($entier) . ' Dirhams';
        if ($decimal > 0) {
            $resultat .= ' et ' . $formatter->format($decimal) . ' Centimes';
        }

        return ucfirst($resultat);
    }

    private function creerBonLivraison(Commande $commande): void
    {
        BonLivraison::firstOrCreate(
            ['commande_id' => $commande->id],
            [
                'numero_bl' => 'BL-' . now()->format('Ymd') . '-' . str_pad($commande->id, 4, '0', STR_PAD_LEFT),
                'date_livraison' => optional($commande->date_livraison_reelle)->toDateString() ?? now()->toDateString(),
            ]
        );
    }
}
