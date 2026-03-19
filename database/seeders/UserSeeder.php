<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@stock.ma',
            'password' => Hash::make('StockFlow@2026'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Gestionnaire Stock',
            'email' => 'gestionnaire@stock.ma',
            'password' => Hash::make('StockFlow@2026'),
            'role' => 'gestionnaire',
        ]);

        User::create([
            'name' => 'Vendeur Caisse',
            'email' => 'vendeur@stock.ma',
            'password' => Hash::make('StockFlow@2026'),
            'role' => 'vendeur',
        ]);

        User::create([
            'name' => 'Manager Gérant',
            'email' => 'manager@stock.ma',
            'password' => Hash::make('StockFlow@2026'),
            'role' => 'manager',
        ]);
    }
}