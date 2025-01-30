<?php

namespace App\Http\Controllers;

use App\Models\Product;
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

    public function show($fournisseur_id)
    {
        $data = $this->productService->showProduct($fournisseur_id);
        return response()->json($data);
    }

    public function update(Request $request, Product $product)
    {
        $updateProduct = $this->productService->updateProduct($product, $request->all());
        return response()->json($updateProduct);
    }

    public function destroy(Product $product)
    {
        return $this->productService->deleteProduct($product);
    }
}