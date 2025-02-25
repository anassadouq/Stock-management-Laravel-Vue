<?php

namespace App\Models;

use App\Models\Achat;
use App\Models\Product;
use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


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