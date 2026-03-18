<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneApprovisionnement extends Model
{
    protected $table = 'lignes_approvisionnement';
    protected $fillable = [
        'approvisionnement_id', 'produit_id',
        'quantite', 'prix_unitaire', 'sous_total'
    ];

    public function approvisionnement()
    {
        return $this->belongsTo(Approvisionnement::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}