<?php

namespace App\Services;

use App\Interfaces\FournisseurInterface;

class FournisseurService
{
    public $fournisseurInterface;

    public function __construct(FournisseurInterface $fournisseurInterface)
    {
        $this->fournisseurInterface = $fournisseurInterface;
    }

    // Fetch all Fournisseurs
    public function getFournisseurs()
    {
        return $this->fournisseurInterface->getFournisseurs();
    }

    // Save Fournisseur
    public function saveFournisseur($data)
    {
        return $this->fournisseurInterface->saveFournisseur($data);
    }

    // Update Fournisseur
    public function updateFournisseur($fournisseur, $data)
    {
        return $this->fournisseurInterface->updateFournisseur($fournisseur, $data);
    }

    // Delete Fournisseur
    public function deleteFournisseur($fournisseur)
    {
        return $this->fournisseurInterface->deleteFournisseur($fournisseur);
    }
}