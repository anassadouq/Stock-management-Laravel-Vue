<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Fournisseur;
use App\Interfaces\ProductInterface;

class ProductRepository implements ProductInterface
{
    public function getProducts()
    {
        return Product::all();
    }

    public function saveProduct($product)
    {
        return Product::create($product);
    }

    public function showProduct($fournisseur_id)
    {
        $fournisseur = Fournisseur::find($fournisseur_id);
        $product = Product::where('fournisseur_id', $fournisseur_id)->get();

        return [
            'fournisseur' => $fournisseur,
            'product' => $product
        ];
    }

    public function updateProduct($product, $data)
    {
        if ($product->update($data)) {
            return $product->fresh();
        }
        return response()->json(['error' => 'Failed to update product'], 500);
    }

    public function deleteProduct($product)
    {
        if ($product->delete()) {
            return response()->json(['message' => 'product deleted successfully']);
        }
        return response()->json(['error' => 'Failed to delete product'], 500);
    }    
}