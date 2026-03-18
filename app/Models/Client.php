<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['nom', 'prenom', 'email', 'telephone', 'adresse'];

    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
}