<?php

namespace App\Models;

use App\Models\Achat;
use App\Models\Magasin;
use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'magasin_id', 'fournisseur_id', 'code', 'designation', 'stock_min', 'min_sortie', 'qte', 'pu'
    ];

    public function magasin() 
    {
        return $this->belongsTo(Magasin::class);
    }

    public function fournisseur() 
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function achat() 
    {
        return $this->hasMany(Achat::class);
    }
}