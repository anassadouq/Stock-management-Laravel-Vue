<?php

namespace App\Models;

use App\Models\DetailProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fournisseur extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom', 'adresse', 'tel'
    ];

    public function detail_products() 
    {
        return $this->hasMany(DetailProduct::class);
    }
}