<?php

namespace App\Interfaces;

interface AchatInterface
{
    // Fetch achats
    public function getAchats();

    // Save achat
    public function saveAchat($data);

    // Show achat
    public function showAchat($client_id);

    // Update achat
    public function updateAchat($achat, $data);

    // Delete achat
    public function deleteAchat($achat);
}