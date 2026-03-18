<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 50)->unique();
            $table->string('libelle', 200);
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('restrict');
            $table->foreignId('marque_id')->constrained('marques')->onDelete('restrict');
            $table->decimal('prix_achat', 10, 2);
            $table->decimal('prix_vente', 10, 2);
            $table->integer('quantite_stock')->default(0);
            $table->integer('seuil_alerte')->default(5);
            $table->string('image_url', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};