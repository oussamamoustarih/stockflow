<?php

namespace App\Http\Controllers;

use App\Exports\ProduitsExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Marque;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $query = Produit::with('categorie', 'marque');

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('libelle', 'like', "%$search%")
                    ->orWhere('reference', 'like', "%$search%");
            });
        }

        // Filtre catégorie
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        $produits = $query->orderBy('id')->paginate(10)->withQueryString();
        $categories = Categorie::orderBy('nom')->get();

        return view('produits.index', compact('produits', 'categories'));
    }

    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();
        $marques = Marque::orderBy('nom')->get();
        return view('produits.create', compact('categories', 'marques'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reference'      => 'required|max:50|unique:produits,reference',
            'libelle'        => 'required|max:200',
            'categorie_id'   => 'required|exists:categories,id',
            'marque_id'      => 'required|exists:marques,id',
            'prix_achat'     => 'required|numeric|min:0',
            'prix_vente'     => 'required|numeric|min:0',
            'quantite_stock' => 'required|integer|min:0',
            'seuil_alerte'   => 'required|integer|min:0',
            'image'          => 'nullable|image|max:2048',
        ], [
            'reference.required'    => 'La référence est obligatoire.',
            'reference.unique'      => 'Cette référence existe déjà.',
            'libelle.required'      => 'Le libellé est obligatoire.',
            'categorie_id.required' => 'La catégorie est obligatoire.',
            'marque_id.required'    => 'La marque est obligatoire.',
            'prix_achat.required'   => 'Le prix d\'achat est obligatoire.',
            'prix_vente.required'   => 'Le prix de vente est obligatoire.',
            'quantite_stock.required' => 'La quantité est obligatoire.',
            'seuil_alerte.required' => 'Le seuil d\'alerte est obligatoire.',
            'image.image'           => 'Le fichier doit être une image.',
            'image.max'             => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        $data = $request->only([
            'reference',
            'libelle',
            'categorie_id',
            'marque_id',
            'prix_achat',
            'prix_vente',
            'quantite_stock',
            'seuil_alerte'
        ]);

        // Upload image
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $uploadedFile = Cloudinary::upload($request->file('image')->getPathname());
            $data['image_url'] = $uploadedFile->getSecurePath();
        }

        Produit::create($data);

        return redirect()->route('produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function show(Produit $produit)
    {
        $mouvements = $produit->mouvements()
            ->orderBy('date_mouvement', 'desc')
            ->paginate(10);
        return view('produits.show', compact('produit', 'mouvements'));
    }

    public function edit(Produit $produit)
    {
        $categories = Categorie::orderBy('nom')->get();
        $marques = Marque::orderBy('nom')->get();
        return view('produits.edit', compact('produit', 'categories', 'marques'));
    }

    public function update(Request $request, Produit $produit)
    {
        $request->validate([
            'reference'      => 'required|max:50|unique:produits,reference,' . $produit->id,
            'libelle'        => 'required|max:200',
            'categorie_id'   => 'required|exists:categories,id',
            'marque_id'      => 'required|exists:marques,id',
            'prix_achat'     => 'required|numeric|min:0',
            'prix_vente'     => 'required|numeric|min:0',
            'quantite_stock' => 'required|integer|min:0',
            'seuil_alerte'   => 'required|integer|min:0',
            'image'          => 'nullable|image|max:2048',
        ], [
            'reference.required'    => 'La référence est obligatoire.',
            'reference.unique'      => 'Cette référence existe déjà.',
            'libelle.required'      => 'Le libellé est obligatoire.',
            'categorie_id.required' => 'La catégorie est obligatoire.',
            'marque_id.required'    => 'La marque est obligatoire.',
            'prix_achat.required'   => 'Le prix d\'achat est obligatoire.',
            'prix_vente.required'   => 'Le prix de vente est obligatoire.',
            'image.image'           => 'Le fichier doit être une image.',
            'image.max'             => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        $data = $request->only([
            'reference',
            'libelle',
            'categorie_id',
            'marque_id',
            'prix_achat',
            'prix_vente',
            'quantite_stock',
            'seuil_alerte'
        ]);

        // Upload nouvelle image
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $uploadedFile = Cloudinary::upload($request->file('image')->getPathname());
            $data['image_url'] = $uploadedFile->getSecurePath();
        }

        $produit->update($data);

        return redirect()->route('produits.index')
            ->with('success', 'Produit modifié avec succès.');
    }

    public function destroy(Produit $produit)
    {
        // Supprimer image si existe
        // Image gérée par Cloudinary - suppression optionnelle

        $produit->delete();

        return redirect()->route('produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }

    public function exportExcel()
    {
        return Excel::download(new ProduitsExport, 'produits-' . now()->format('Ymd') . '.xlsx');
    }
}
