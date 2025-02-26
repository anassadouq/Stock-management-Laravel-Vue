<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Product;
use App\Models\DetailProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achat extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'product_id', 'qte'
    ];
    
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}