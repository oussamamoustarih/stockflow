<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marque;

class MarqueSeeder extends Seeder
{
    public function run(): void
    {
        Marque::create(['nom' => 'Samsung', 'description' => 'Marque coréenne électronique']);
        Marque::create(['nom' => 'Apple', 'description' => 'Marque américaine technologie']);
        Marque::create(['nom' => 'Hp', 'description' => 'Marque américaine informatique']);
    }
}