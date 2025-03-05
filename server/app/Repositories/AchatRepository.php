<?php

namespace App\Repositories;

use App\Models\Achat;
use App\Models\Facture;
use App\Interfaces\AchatInterface;
 
class AchatRepository implements AchatInterface
{
    public function getAchats()
    {
        return Achat::with(['product', 'facture.client'])->get();
    }         

    public function saveAchat($achat)
    {
        return Achat::create($achat);
    }

    public function showAchat($facture_id)
    {
        $facture = Facture::with('achat.product')->find($facture_id);
    
        if (!$facture) {
            return response()->json(['error' => 'facture not found'], 404);
        }
    
        return response()->json([
            'facture' => $facture,
            'achat' => $facture->achat,
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