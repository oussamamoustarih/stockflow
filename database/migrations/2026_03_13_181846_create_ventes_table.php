<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('restrict');
            $table->foreignId('vendeur_id')->constrained('users')->onDelete('restrict');
            $table->dateTime('date_vente');
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->enum('mode_paiement', ['especes', 'carte']);
            $table->enum('statut', ['en_cours', 'validee', 'annulee'])->default('en_cours');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};