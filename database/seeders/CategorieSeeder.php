<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        Categorie::create(['nom' => 'Électronique', 'description' => 'Appareils électroniques et accessoires']);
        Categorie::create(['nom' => 'Informatique', 'description' => 'Matériel et accessoires informatiques']);
        Categorie::create(['nom' => 'Téléphonie', 'description' => 'Téléphones et accessoires']);
    }
}