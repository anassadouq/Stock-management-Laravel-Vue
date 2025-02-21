<?php

namespace App\Repositories;

use App\Models\Magasin;
use App\Interfaces\MagasinInterface;

class MagasinRepository implements MagasinInterface
{
    // Fetch magasins
    public function getMagasins()
    {
        return Magasin::all();
    }

    // Save magasin
    public function saveMagasin($magasin)
    {
        return Magasin::create($magasin);
    }

    // Update magasin
    public function updateMagasin($magasin, $data)
    {
        if ($magasin->update($data)) {
            return $magasin->fresh(); // Simply return updated magasin
        }
        return null; // Let the service/controller handle errors
    }
    
    // Delete magasin
    public function deleteMagasin($magasin)
    {
        if ($magasin->delete()) {
            return response()->json(['message' => 'magasin deleted successfully']);
        }
        return response()->json(['error' => 'Failed to delete magasin'], 500);
    }
}