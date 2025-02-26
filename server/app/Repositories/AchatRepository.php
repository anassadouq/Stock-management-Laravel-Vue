<?php

namespace App\Repositories;

use App\Models\Achat;
use App\Models\Client;
use App\Interfaces\AchatInterface;
 
class AchatRepository implements AchatInterface
{
    public function getAchats()
    {
        return Achat::with(['product', 'client'])->get();
    }       

    public function saveAchat($achat)
    {
        return Achat::create($achat);
    }

    public function showAchat($client_id)
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