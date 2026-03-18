<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $fillable = ['nom', 'email', 'telephone', 'adresse'];

    public function approvisionnements()
    {
        return $this->hasMany(Approvisionnement::class);
    }
}