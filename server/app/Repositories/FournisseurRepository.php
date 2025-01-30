<?php

namespace App\Repositories;

use App\Interfaces\FournisseurInterface;
use App\Models\Fournisseur;

class FournisseurRepository implements FournisseurInterface
{
    // Fetch all Fournisseurs
    public function getFournisseurs()
    {
        return Fournisseur::all();
    }

    // Save Fournisseur
    public function saveFournisseur($fournisseur)
    {
        return Fournisseur::create($fournisseur);
    }

    // Update Fournisseur
    public function updateFournisseur($fournisseur, $data)
    {
        if ($fournisseur->update($data)) {
            return $fournisseur->fresh(); // Return the updated model
        }
        return response()->json(['error' => 'Failed to update fournisseur'], 500);
    }

    // Delete Fournisseur
    public function deleteFournisseur($fournisseur)
    {
        if ($fournisseur->delete()) {
            return response()->json(['message' => 'Fournisseur deleted successfully']);
        }
        return response()->json(['error' => 'Failed to delete fournisseur'], 500);
    }    
}