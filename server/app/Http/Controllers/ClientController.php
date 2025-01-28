<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return response()->json($clients);
    }

    public function store(Request $request)
    {
        $client = Client::create($request->all());
        return response()->json($client, 201);
    }

    public function show($product_id)
    {
        $product = Product::find($product_id);
        $client = Client::where('product_id', $product_id)->get();
    
        return response()->json(['product' => $product, 'client' => $client]);
    }

    public function update(Request $request, $id)
    {
        $product = Client::find($id);

        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }

        $product->update($request->all());
        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Client::findOrFail($id);
        $product_id = $product->product_id;
        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }
}