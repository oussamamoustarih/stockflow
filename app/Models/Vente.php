<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    protected $fillable = [
        'client_id', 'vendeur_id', 'date_vente',
        'montant_total', 'mode_paiement', 'statut'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function lignes()
    {
        return $this->hasMany(LigneVente::class);
    }
}