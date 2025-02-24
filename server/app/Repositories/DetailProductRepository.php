<?php

namespace App\Repositories;

use App\Models\DetailProduct;
use App\Models\Product;
use App\Interfaces\DetailProductInterface;

class DetailProductRepository implements DetailProductInterface
{
    public function getDetails()
    {
        return DetailProduct::all();
    }

    public function saveDetail($detail_product)
    {
        return DetailProduct::create($detail_product);
    }

    public function showDetail($product_id)
    {
        $product = Product::with('detail_products.fournisseur')->find($product_id);
    
        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }
    
        return response()->json([
            'product' => $product,
            'detail_products' => $product->detail_products,
        ]);
    }

    public function updateDetail($product, $data)
    {
        if ($detail_product->update($data)) {
            return $detail_product->fresh();
        }
        return response()->json(['error' => 'Failed to update product'], 500);
    }

    public function deleteDetail($detail_product)
    {
        if ($detail_product->delete()) {
            return response()->json(['message' => 'detail_product deleted successfully']);
        }
        return response()->json(['error' => 'Failed to delete detail_product'], 500);
    }    
}