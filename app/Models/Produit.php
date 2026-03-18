<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'reference', 'libelle', 'categorie_id', 'marque_id',
        'prix_achat', 'prix_vente', 'quantite_stock',
        'seuil_alerte', 'image_url'
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function marque()
    {
        return $this->belongsTo(Marque::class);
    }

    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class);
    }

    public function lignesVente()
    {
        return $this->hasMany(LigneVente::class);
    }

    public function lignesApprovisionnement()
    {
        return $this->hasMany(LigneApprovisionnement::class);
    }

    public function lignesCommande()
    {
        return $this->hasMany(LigneCommande::class);
    }

    // Calcule la marge bénéficiaire
    public function calculerMarge()
    {
        return $this->prix_vente - $this->prix_achat;
    }

    // Vérifie si le stock est disponible
    public function verifierStockDisponible($quantite)
    {
        return $this->quantite_stock >= $quantite;
    }
}