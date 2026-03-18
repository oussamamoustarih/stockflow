<?php

namespace App\Http\Controllers;


use App\Exports\MouvementsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\MouvementStock;
use App\Models\Produit;

class MouvementStockController extends Controller
{
    public function index(Request $request)
    {
        $query = MouvementStock::with('produit', 'utilisateur')
            ->orderBy('date_mouvement', 'desc');

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type_mouvement', $request->type);
        }

        // Filtre par produit
        if ($request->filled('produit_id')) {
            $query->where('produit_id', $request->produit_id);
        }

        // Filtre par date
        if ($request->filled('date_debut')) {
            $query->whereDate('date_mouvement', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_mouvement', '<=', $request->date_fin);
        }

        $mouvements = $query->paginate(15)->withQueryString();
        $produits = Produit::orderBy('libelle')->get();

        return view('mouvements.index', compact('mouvements', 'produits'));
    }

    public function exportExcel()
    {
        return Excel::download(new MouvementsExport, 'mouvements-' . now()->format('Ymd') . '.xlsx');
    }
}