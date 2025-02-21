<?php

namespace App\Interfaces;

interface MagasinInterface
{
    // Fetch all magasins
    public function getMagasins();

    // Save magasin
    public function saveMagasin($data);

    // Update magasin
    public function updateMagasin($magasin, $data);

    // Update magasin
    public function deleteMagasin($magasin);
}