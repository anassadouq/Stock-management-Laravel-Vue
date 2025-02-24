<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Fournisseur;


class DetailProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'fournisseur_id', 'product_id', 'qte', 'pu'
    ];

    public function product() 
    {
        return $this->belongsTo(Product::class);
    }

    public function fournisseur() 
    {
        return $this->belongsTo(Fournisseur::class);
    }
}