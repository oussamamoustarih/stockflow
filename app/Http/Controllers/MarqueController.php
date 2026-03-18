<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marque;

class MarqueController extends Controller
{
    public function index()
    {
        $marques = Marque::orderBy('id')->paginate(10);
        return view('marques.index', compact('marques'));
    }

    public function create()
    {
        return view('marques.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => 'required|max:100|unique:marques,nom',
            'description' => 'nullable',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.unique'   => 'Cette marque existe déjà.',
            'nom.max'      => 'Le nom ne doit pas dépasser 100 caractères.',
        ]);

        Marque::create($request->only('nom', 'description'));

        return redirect()->route('marques.index')
            ->with('success', 'Marque créée avec succès.');
    }

    public function edit(Marque $marque)
    {
        return view('marques.edit', compact('marque'));
    }

    public function update(Request $request, Marque $marque)
    {
        $request->validate([
            'nom'         => 'required|max:100|unique:marques,nom,' . $marque->id,
            'description' => 'nullable',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.unique'   => 'Cette marque existe déjà.',
            'nom.max'      => 'Le nom ne doit pas dépasser 100 caractères.',
        ]);

        $marque->update($request->only('nom', 'description'));

        return redirect()->route('marques.index')
            ->with('success', 'Marque modifiée avec succès.');
    }

    public function destroy(Marque $marque)
    {
        if ($marque->produits()->count() > 0) {
            return redirect()->route('marques.index')
                ->with('error', 'Impossible de supprimer : des produits utilisent cette marque.');
        }

        $marque->delete();

        return redirect()->route('marques.index')
            ->with('success', 'Marque supprimée avec succès.');
    }
}