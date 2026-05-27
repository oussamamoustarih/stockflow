<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('id', 'asc')->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'       => 'required|max:100',
            'prenom'    => 'required|max:100',
            'email'     => 'nullable|email|max:150',
            'telephone' => 'nullable|max:20',
            'adresse'   => 'nullable',
        ], [
            'nom.required'    => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.email'     => 'L\'adresse email n\'est pas valide.',
        ]);

        Client::create($request->only('nom', 'prenom', 'email', 'telephone', 'adresse'));

        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès.');
    }

    public function show(Client $client)
    {
        // Stats individuelles (R4)
        $totalAchats = $client->ventes()->where('statut', 'validee')->count();
        $chiffreAffaires = $client->ventes()->where('statut', 'validee')->sum('montant_total');
        $panierMoyen = $totalAchats > 0 ? $chiffreAffaires / $totalAchats : 0;
        $premiereVente = $client->ventes()->where('statut', 'validee')->min('date_vente');
        $derniereVente = $client->ventes()->where('statut', 'validee')->max('date_vente');

        // Top 5 produits préférés
        $topProduits = \App\Models\LigneVente::whereHas('vente', function($q) use ($client) {
                $q->where('client_id', $client->id)->where('statut', 'validee');
            })
            ->selectRaw('produit_id, SUM(quantite) as total_quantite')
            ->groupBy('produit_id')
            ->orderByDesc('total_quantite')
            ->take(5)
            ->with('produit')
            ->get();

        // Historique transactions
        $ventes = $client->ventes()
            ->orderBy('date_vente', 'desc')
            ->paginate(10);

        return view('clients.show', compact(
            'client', 'totalAchats', 'chiffreAffaires',
            'panierMoyen', 'premiereVente', 'derniereVente',
            'topProduits', 'ventes'
        ));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nom'       => 'required|max:100',
            'prenom'    => 'required|max:100',
            'email'     => 'nullable|email|max:150',
            'telephone' => 'nullable|max:20',
            'adresse'   => 'nullable',
        ], [
            'nom.required'    => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.email'     => 'L\'adresse email n\'est pas valide.',
        ]);

        $client->update($request->only('nom', 'prenom', 'email', 'telephone', 'adresse'));

        return redirect()->route('clients.index')
            ->with('success', 'Client modifié avec succès.');
    }

    public function destroy(Client $client)
    {
        if ($client->ventes()->count() > 0) {
            return redirect()->route('clients.index')
                ->with('error', 'Impossible de supprimer : ce client a des ventes enregistrées.');
        }

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}
