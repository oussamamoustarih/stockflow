<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'client_id',
        'date_commande',
        'date_livraison_prevue',
        'date_livraison_reelle',
        'montant_total',
        'statut',
    ];

    protected $casts = [
        'date_commande' => 'date',
        'date_livraison_prevue' => 'date',
        'date_livraison_reelle' => 'datetime',
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
