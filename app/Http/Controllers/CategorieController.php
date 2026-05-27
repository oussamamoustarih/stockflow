<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::orderBy('id', 'asc')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => 'required|max:100|unique:categories,nom',
            'description' => 'nullable',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.unique'   => 'Cette catégorie existe déjà.',
            'nom.max'      => 'Le nom ne doit pas dépasser 100 caractères.',
        ]);

        Categorie::create($request->only('nom', 'description'));

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function edit(Categorie $categorie)
    {
        return view('categories.edit', compact('categorie'));
    }

    public function update(Request $request, Categorie $categorie)
    {
        $request->validate([
            'nom'         => 'required|max:100|unique:categories,nom,' . $categorie->id,
            'description' => 'nullable',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.unique'   => 'Cette catégorie existe déjà.',
            'nom.max'      => 'Le nom ne doit pas dépasser 100 caractères.',
        ]);

        $categorie->update($request->only('nom', 'description'));

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie modifiée avec succès.');
    }

    public function destroy(Categorie $categorie)
    {
        if ($categorie->produits()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer : des produits utilisent cette catégorie.');
        }

        $categorie->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}