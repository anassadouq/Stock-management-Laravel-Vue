<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fournisseur extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom', 'adresse', 'tel'
    ];

    public function produits() {
        return $this->hasMany(Product::class, 'fournisseur_id');
    }
}