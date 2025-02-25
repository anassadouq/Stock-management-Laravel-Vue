<?php

namespace App\Http\Controllers;

use App\Models\DetailProduct;
use App\Models\Product;
use App\Services\DetailProductService;
use Illuminate\Http\Request;

class DetailProductController extends Controller
{
    protected $detailProductService;

    public function __construct(DetailProductService $detailProductService)
    {
        $this->detailProductService = $detailProductService;
    }

    public function index()
    {
        $detailProducts = $this->detailProductService->getDetails();
        return response()->json($detailProducts);
    }

    public function store(Request $request)
    {
        $product = $this->detailProductService->saveDetail($request->all());
        return response()->json($product);
    }

    public function show($product_id)
    {
        $product = Product::with('detail_products.fournisseur')->find($product_id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'product' => $product,
            'detail_products' => $product->detail_products,  // Corrected to plural
        ]);
    }

    public function update(Request $request, DetailProduct $detailProduct)
    {
        $updateDetail = $this->detailProductService->updateDetail($detailProduct, $request->all());
        return response()->json($updateDetail);
    }

    public function destroy(DetailProduct $detailProduct)
    {
        $this->detailProductService->deleteDetail($detailProduct);
        return response()->json(['message' => 'Detail deleted successfully']);
    }
}