<?php

namespace App\Models;

use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\DetailProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achat extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'product_id', 'detail_product_id', 'qte'
    ];
    
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function detail_product()
    {
        return $this->belongsTo(DetailProduct::class, 'detail_product_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($achat) {
            $product = Product::find($achat->product_id);
            if ($product && $product->qte >= $achat->qte) {
                $product->qte -= $achat->qte;
                $product->save();
            } else {
                throw new \Exception("Quantité insuffisante pour cet achat.");
            }
        });
    }
}