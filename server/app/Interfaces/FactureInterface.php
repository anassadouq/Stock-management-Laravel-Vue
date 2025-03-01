<?php

namespace App\Interfaces;

interface FactureInterface
{
    // Fetch Factures
    public function getFactures();

    // Save Facture
    public function saveFacture($data);

    // Show Facture
    public function showFacture($client_id);

    // Update Facture
    public function updateFacture($facture, $data);

    // Delete Facture
    public function deleteFacture($facture);
}