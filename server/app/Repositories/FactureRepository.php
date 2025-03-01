<?php

namespace App\Repositories;

use App\Models\Facture;
use App\Models\Client;
use App\Interfaces\FactureInterface;
 
class FactureRepository implements FactureInterface
{
    public function getFactures()
    {
        return Facture::with(['client'])->get();
    }       

    public function saveFacture($facture)
    {
        return Facture::create($facture);
    }

    public function showFacture($client_id)
    {
        $client = Client::with('facture')->find($client_id);
    
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }
    
        return response()->json([
            'client' => $client,
            'facture' => $client->facture,
        ]);
    }

    public function updateFacture($facture, $data)
    {
        if ($facture->update($data)) {
            return $facture->fresh();
        }
        return response()->json(['error' => 'Failed to update facture'], 500);
    }

    public function deleteFacture($facture)
    {
        if ($facture->delete()) {
            return response()->json(['message' => 'facture deleted successfully']);
        }
        return response()->json(['error' => 'Failed to delete facture'], 500);
    }
}