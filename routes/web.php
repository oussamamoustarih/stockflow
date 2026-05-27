<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\MarqueController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ApprovisionnementController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\MouvementStockController;

// Groupe global pour empêcher le cache sur toutes les pages
Route::middleware(['prevent.back'])->group(function () {

    // Redirection intelligente sur la racine
    Route::get('/', function () {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('login');
    });

    // Public auth status check
    Route::get('/auth/status', function() {
        return response()->json([
            'authenticated' => auth()->check()
        ]);
    })->name('auth.status');

    // Routes protégées par authentification
    Route::middleware(['auth'])->group(function () {

        // Dashboard — tous les rôles
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Export des rapports du tableau de bord
        Route::get('/dashboard/export/excel', [DashboardController::class, 'exportExcel'])
            ->name('dashboard.export.excel')
            ->middleware('role:manager');

        Route::get('/dashboard/export/pdf', [DashboardController::class, 'exportPdf'])
            ->name('dashboard.export.pdf')
            ->middleware('role:manager');

        // Auth check endpoint
        Route::get('/auth/check', function() {
            if (auth()->check()) {
                return response()->json(['authenticated' => true]);
            }
            return response()->json(['authenticated' => false], 401);
        })->name('auth.check');

        // Profil utilisateur — tous les rôles
        Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
        Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');

        // Gestion utilisateurs — admin uniquement
        Route::resource('users', UserController::class)
            ->parameters(['users' => 'user'])
            ->middleware('role:admin')
            ->except(['show']);

        // Catalogue — admin + gestionnaire
        Route::resource('categories', CategorieController::class)
            ->parameters(['categories' => 'categorie'])
            ->middleware('role:admin,gestionnaire');

        Route::resource('marques', MarqueController::class)
            ->parameters(['marques' => 'marque'])
            ->middleware('role:admin,gestionnaire');

        // Export Produits
        Route::get('produits/export/excel', [ProduitController::class, 'exportExcel'])
            ->name('produits.export.excel')
            ->middleware('role:admin,gestionnaire');

        Route::resource('produits', ProduitController::class)
            ->parameters(['produits' => 'produit'])
            ->middleware('role:admin,gestionnaire');

        // Personnes — admin + gestionnaire + vendeur
        Route::resource('fournisseurs', FournisseurController::class)
            ->parameters(['fournisseurs' => 'fournisseur'])
            ->middleware('role:admin,gestionnaire');

        Route::resource('clients', ClientController::class)
            ->parameters(['clients' => 'client'])
            ->middleware('role:admin,gestionnaire,vendeur');

        // Approvisionnements
        Route::resource('approvisionnements', ApprovisionnementController::class)
            ->parameters(['approvisionnements' => 'approvisionnement'])
            ->middleware('role:admin,gestionnaire');

        Route::post('approvisionnements/{approvisionnement}/valider',
            [ApprovisionnementController::class, 'valider'])
            ->name('approvisionnements.valider')
            ->middleware('role:admin,gestionnaire');

        // Ventes
        Route::get('ventes/export/excel', [VenteController::class, 'exportExcel'])
            ->name('ventes.export.excel')
            ->middleware('role:admin,vendeur,gestionnaire');

        Route::resource('ventes', VenteController::class)
            ->parameters(['ventes' => 'vente'])
            ->middleware('role:admin,vendeur,gestionnaire');

        Route::get('ventes/{vente}/facture', [VenteController::class, 'genererFacture'])
            ->name('ventes.facture')
            ->middleware('role:admin,vendeur,gestionnaire');

        // Commandes
        Route::resource('commandes', CommandeController::class)
            ->parameters(['commandes' => 'commande']);

        Route::post('commandes/{commande}/valider',
            [CommandeController::class, 'valider'])->name('commandes.valider');
        Route::post('commandes/{commande}/livrer',
            [CommandeController::class, 'livrer'])->name('commandes.livrer');
        Route::post('commandes/{commande}/annuler',
            [CommandeController::class, 'annuler'])->name('commandes.annuler');
        Route::get('commandes/{commande}/bon-livraison',
            [CommandeController::class, 'genererBL'])->name('commandes.bl');

        // Mouvements stock
        Route::get('mouvements/export/excel',
            [MouvementStockController::class, 'exportExcel'])
            ->name('mouvements.export.excel')
            ->middleware('role:admin,gestionnaire');

        Route::get('/mouvements', [MouvementStockController::class, 'index'])
            ->name('mouvements.index')
            ->middleware('role:admin,gestionnaire');
    });

    // Routes Breeze (login, register, logout...)
    require __DIR__.'/auth.php';
});