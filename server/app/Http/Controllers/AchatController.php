<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Achat;
use Illuminate\Http\Request;

class AchatController extends Controller
{
    public function index()
    {
        $achats = Achat::all();
        return response()->json($achats);
    }

    public function store(Request $request)
    {
        $achat = Achat::create($request->all());
        return response()->json($achat, 201);
    }

    public function show($client_id)
    {
        $client = Client::with('achats.product')->find($client_id);
    
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }
    
        return response()->json([
            'client' => $client,
            'achats' => $client->achats,
        ]);
    }       

    public function update(Request $request, $id)
    {
        $achat = Achat::find($id);

        if (!$achat) {
            return response()->json(['error' => 'achat not found'], 404);
        }

        $achat->update($request->all());
        return response()->json($achat);
    }

    public function destroy($id)
    {
        $achat = Achat::findOrFail($id);
        $client_id = $achat->client_id; // Get the client ID before deleting the achat
        if (!$achat) {
            return response()->json(['error' => 'achat not found'], 404);
        }

        $achat->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }
}