<?php

namespace App\Interfaces;

interface FournisseurInterface
{
    // Fetch all Fournisseurs
    public function getFournisseurs();

    // Save Fournisseur
    public function saveFournisseur($data);

    // Update Fournisseur
    public function updateFournisseur($fournisseur, $request);

    // Update Fournisseur
    public function deleteFournisseur($fournisseur);
}