<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Facture;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achat extends Model
{
    use HasFactory;

    protected $fillable = [
        'facture_id', 'product_id', 'qte', 'pu'
    ];
    
    public function facture()
    {
        return $this->belongsTo(Facture::class, 'facture_id');
    }

    public function client()
    {
        return $this->hasOneThrough(Client::class, Facture::class, 'id', 'id', 'facture_id', 'client_id');
    }    

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}