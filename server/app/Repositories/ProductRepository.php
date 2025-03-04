<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Fournisseur;
use App\Interfaces\ProductInterface;

class ProductRepository implements ProductInterface
{
    public function getProducts()
    {
        return Product::with(['magasin', 'detail_products'])
            ->withSum('detail_products', 'qte') // Ajoute la somme des quantités
            ->get();
    }

    public function saveProduct($product)
    {
        return Product::create($product);
    }

    public function showProduct($magasin_id)
    {
        $magasin = Magasin::with('products.fournisseur')->find($magasin_id);
    
        if (!$magasin) {
            return response()->json(['error' => 'magasin not found'], 404);
        }
    
        return response()->json([
            'magasin' => $magasin,
            'products' => $magasin->products,
        ]);
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