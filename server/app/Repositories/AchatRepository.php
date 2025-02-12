<?php

namespace App\Repositories;

use App\Models\Achat;
use App\Models\Client;
use App\Interfaces\AchatInterface;
 
class AchatRepository implements AchatInterface
{
    public function getAchats()
    {
        return Achat::with('client','product')->get();
    }

    public function saveAchat($achat)
    {
        return Achat::create($achat);
    }

    public function showAchat($client_id)
    {
        $client = Client::find($client_id);
        $product = Product::where('client_id', $client_id)->get();

        return [
            'client' => $client,
            'product' => $product
        ];
    }

    public function updateAchat($achat, $data)
    {
        if ($achat->update($data)) {
            return $achat->fresh();
        }
        return response()->json(['error' => 'Failed to update achat'], 500);
    }

    public function deleteAchat($achat)
    {
        if ($achat->delete()) {
            return response()->json(['message' => 'achat deleted successfully']);
        }
        return response()->json(['error' => 'Failed to delete achat'], 500);
    }    
}