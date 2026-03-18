<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fournisseur;

class FournisseurSeeder extends Seeder
{
    public function run(): void
    {
        Fournisseur::create(['nom' => 'TechDistrib Maroc', 'email' => 'contact@techdistrib.ma', 'telephone' => '0522001122', 'adresse' => 'Casablanca, Maroc']);
        Fournisseur::create(['nom' => 'ElectroSupply', 'email' => 'info@electrosupply.ma', 'telephone' => '0537112233', 'adresse' => 'Rabat, Maroc']);
        Fournisseur::create(['nom' => 'InfoPro', 'email' => 'ventes@infopro.ma', 'telephone' => '0528223344', 'adresse' => 'Agadir, Maroc']);
        Fournisseur::create(['nom' => 'MobileStock', 'email' => 'stock@mobile.ma', 'telephone' => '0535334455', 'adresse' => 'Fès, Maroc']);
        Fournisseur::create(['nom' => 'DigiMaroc', 'email' => 'digi@maroc.ma', 'telephone' => '0539445566', 'adresse' => 'Tanger, Maroc']);
    }
}