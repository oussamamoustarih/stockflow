<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'client_id', 'date_commande',
        'date_livraison_prevue', 'montant_total', 'statut'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    public function bonLivraison()
    {
        return $this->hasOne(BonLivraison::class);
    }
}