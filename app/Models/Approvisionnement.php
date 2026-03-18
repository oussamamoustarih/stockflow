<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approvisionnement extends Model
{
    protected $fillable = [
        'fournisseur_id', 'date_approvisionnement',
        'montant_total', 'statut'
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneApprovisionnement::class);
    }
}