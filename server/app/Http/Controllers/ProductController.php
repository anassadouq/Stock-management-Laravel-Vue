<?php

namespace App\Http\Controllers;

use App\Models\Magasin;
use App\Models\Product;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getProducts();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $product = $this->productService->saveProduct($request->all());
        return response()->json($product);
    }

    // Show a single product with its fournisseur details
    public function show($magasin_id)
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

    // Update a product
    public function update(Request $request, Product $product)
    {
        $updateProduct = $this->productService->updateProduct($product, $request->all());
        return response()->json($updateProduct);
    }

    // Delete a product
    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);
        return response()->json(['message' => 'Achat deleted successfully']);
    }
}