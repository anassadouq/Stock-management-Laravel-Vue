<?php

namespace App\Models;

use App\Models\Achat;
use App\Models\Magasin;
use App\Models\Fournisseur;
use App\Models\DetailProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'magasin_id', 'code', 'designation', 'stock_min', 'min_sortie'
    ];

    public function magasin() 
    {
        return $this->belongsTo(Magasin::class);
    }

    public function achat() 
    {
        return $this->hasMany(Achat::class);
    }

    public function detail_products() 
    {
        return $this->hasMany(DetailProduct::class);
    }
}