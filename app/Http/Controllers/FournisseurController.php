<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fournisseur;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::orderBy('id')->paginate(10);
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function create()
    {
        return view('fournisseurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'       => 'required|max:150',
            'email'     => 'nullable|email|max:150',
            'telephone' => 'nullable|max:20',
            'adresse'   => 'nullable',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'email.email'  => 'L\'adresse email n\'est pas valide.',
        ]);

        Fournisseur::create($request->only('nom', 'email', 'telephone', 'adresse'));

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur créé avec succès.');
    }

    public function show(Fournisseur $fournisseur)
    {
        $approvisionnements = $fournisseur->approvisionnements()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('fournisseurs.show', compact('fournisseur', 'approvisionnements'));
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $request->validate([
            'nom'       => 'required|max:150',
            'email'     => 'nullable|email|max:150',
            'telephone' => 'nullable|max:20',
            'adresse'   => 'nullable',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'email.email'  => 'L\'adresse email n\'est pas valide.',
        ]);

        $fournisseur->update($request->only('nom', 'email', 'telephone', 'adresse'));

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur modifié avec succès.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        if ($fournisseur->approvisionnements()->count() > 0) {
            return redirect()->route('fournisseurs.index')
                ->with('error', 'Impossible de supprimer : ce fournisseur a des approvisionnements.');
        }

        $fournisseur->delete();

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur supprimé avec succès.');
    }
}