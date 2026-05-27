<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasDateReelle = Schema::hasColumn('commandes', 'date_reelle');
        $hasDateLivraisonReelle = Schema::hasColumn('commandes', 'date_livraison_reelle');

        if ($hasDateReelle && !$hasDateLivraisonReelle) {
            DB::statement(
                'ALTER TABLE commandes CHANGE date_reelle date_livraison_reelle DATETIME NULL'
            );
            return;
        }

        if ($hasDateLivraisonReelle) {
            DB::statement(
                'ALTER TABLE commandes MODIFY date_livraison_reelle DATETIME NULL'
            );
        }
    }

    public function down(): void
    {
        $hasDateLivraisonReelle = Schema::hasColumn('commandes', 'date_livraison_reelle');
        $hasDateReelle = Schema::hasColumn('commandes', 'date_reelle');

        if ($hasDateLivraisonReelle && !$hasDateReelle) {
            DB::statement(
                'ALTER TABLE commandes CHANGE date_livraison_reelle date_reelle DATE NULL'
            );
            return;
        }

        if ($hasDateReelle) {
            DB::statement(
                'ALTER TABLE commandes MODIFY date_reelle DATE NULL'
            );
        }
    }
};
