<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        Produit::create(['reference' => 'ELEC001', 'libelle' => 'Télévision Samsung 55"', 'categorie_id' => 1, 'marque_id' => 1, 'prix_achat' => 3500, 'prix_vente' => 4500, 'quantite_stock' => 10, 'seuil_alerte' => 3]);
        Produit::create(['reference' => 'ELEC002', 'libelle' => 'Télévision Samsung 43"', 'categorie_id' => 1, 'marque_id' => 1, 'prix_achat' => 2500, 'prix_vente' => 3200, 'quantite_stock' => 15, 'seuil_alerte' => 3]);
        Produit::create(['reference' => 'INFO001', 'libelle' => 'Laptop HP 15"', 'categorie_id' => 2, 'marque_id' => 3, 'prix_achat' => 5000, 'prix_vente' => 6500, 'quantite_stock' => 8, 'seuil_alerte' => 2]);
        Produit::create(['reference' => 'INFO002', 'libelle' => 'Laptop HP 17"', 'categorie_id' => 2, 'marque_id' => 3, 'prix_achat' => 6500, 'prix_vente' => 8000, 'quantite_stock' => 5, 'seuil_alerte' => 2]);
        Produit::create(['reference' => 'INFO003', 'libelle' => 'Clavier HP Sans Fil', 'categorie_id' => 2, 'marque_id' => 3, 'prix_achat' => 150, 'prix_vente' => 250, 'quantite_stock' => 30, 'seuil_alerte' => 5]);
        Produit::create(['reference' => 'TEL001', 'libelle' => 'iPhone 14', 'categorie_id' => 3, 'marque_id' => 2, 'prix_achat' => 8000, 'prix_vente' => 10000, 'quantite_stock' => 6, 'seuil_alerte' => 2]);
        Produit::create(['reference' => 'TEL002', 'libelle' => 'iPhone 13', 'categorie_id' => 3, 'marque_id' => 2, 'prix_achat' => 6000, 'prix_vente' => 7500, 'quantite_stock' => 4, 'seuil_alerte' => 2]);
        Produit::create(['reference' => 'TEL003', 'libelle' => 'Samsung Galaxy S23', 'categorie_id' => 3, 'marque_id' => 1, 'prix_achat' => 7000, 'prix_vente' => 8500, 'quantite_stock' => 7, 'seuil_alerte' => 2]);
        Produit::create(['reference' => 'TEL004', 'libelle' => 'Samsung Galaxy A54', 'categorie_id' => 3, 'marque_id' => 1, 'prix_achat' => 3000, 'prix_vente' => 3800, 'quantite_stock' => 12, 'seuil_alerte' => 3]);
        Produit::create(['reference' => 'INFO004', 'libelle' => 'Souris HP Sans Fil', 'categorie_id' => 2, 'marque_id' => 3, 'prix_achat' => 100, 'prix_vente' => 180, 'quantite_stock' => 25, 'seuil_alerte' => 5]);
    }
}