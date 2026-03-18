<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonLivraison extends Model
{
    protected $table = 'bons_livraison';
    protected $fillable = [
        'commande_id', 'numero_bl',
        'date_livraison', 'signature_client'
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}