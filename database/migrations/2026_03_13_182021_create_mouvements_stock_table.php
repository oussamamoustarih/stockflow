<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('restrict');
            $table->enum('type_mouvement', ['entree', 'sortie', 'ajustement']);
            $table->integer('quantite');
            $table->integer('stock_avant');
            $table->integer('stock_apres');
            $table->string('reference_operation', 100)->nullable();
            $table->foreignId('utilisateur_id')->constrained('users')->onDelete('restrict');
            $table->dateTime('date_mouvement');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
    }
};