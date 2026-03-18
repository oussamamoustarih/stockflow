<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    protected $table = 'mouvements_stock';
    protected $fillable = [
        'produit_id', 'type_mouvement', 'quantite',
        'stock_avant', 'stock_apres', 'reference_operation',
        'utilisateur_id', 'date_mouvement'
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}