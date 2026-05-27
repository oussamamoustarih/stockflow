<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->date('date_livraison_reelle')->nullable()->after('date_livraison_prevue');
            $table->date('date_annulation')->nullable()->after('date_livraison_reelle');
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['date_livraison_reelle', 'date_annulation']);
        });
    }
};