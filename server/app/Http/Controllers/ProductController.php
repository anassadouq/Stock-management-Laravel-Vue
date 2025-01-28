<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    public function show($fournisseur_id)
    {
        $fournisseur = Fournisseur::find($fournisseur_id);
        $product = Product::where('fournisseur_id', $fournisseur_id)->get();
    
        return response()->json(['fournisseur' => $fournisseur, 'product' => $product]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }

        $product->update($request->all());
        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $fournisseur_id = $product->fournisseur_id;
        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }
}