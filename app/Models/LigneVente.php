<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneVente extends Model
{
    protected $table = 'lignes_vente';

    protected $fillable = [
        'vente_id', 'produit_id',
        'quantite', 'prix_unitaire', 'sous_total'
    ];

    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}