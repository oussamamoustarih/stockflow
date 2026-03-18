<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::create(['nom' => 'Alami', 'prenom' => 'Youssef', 'email' => 'youssef.alami@gmail.com', 'telephone' => '0661112233', 'adresse' => 'Casablanca']);
        Client::create(['nom' => 'Benali', 'prenom' => 'Fatima', 'email' => 'fatima.benali@gmail.com', 'telephone' => '0662223344', 'adresse' => 'Rabat']);
        Client::create(['nom' => 'Cherkaoui', 'prenom' => 'Omar', 'email' => 'omar.cherkaoui@gmail.com', 'telephone' => '0663334455', 'adresse' => 'Fès']);
        Client::create(['nom' => 'Daoudi', 'prenom' => 'Sanae', 'email' => 'sanae.daoudi@gmail.com', 'telephone' => '0664445566', 'adresse' => 'Marrakech']);
        Client::create(['nom' => 'El Idrissi', 'prenom' => 'Khalid', 'email' => 'khalid.idrissi@gmail.com', 'telephone' => '0665556677', 'adresse' => 'Tanger']);
    }
}