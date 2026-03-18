<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorieSeeder::class,
            MarqueSeeder::class,
            FournisseurSeeder::class,
            ClientSeeder::class,
            ProduitSeeder::class,
        ]);
    }
}