<?php

namespace App\Services;

use App\Interfaces\MagasinInterface;

class MagasinService
{
    public $magasinInterface;

    public function __construct(MagasinInterface $magasinInterface)
    {
        $this->magasinInterface = $magasinInterface;
    }

    // Fetch magasins
    public function getMagasins()
    {
        return $this->magasinInterface->getMagasins();
    }

    // Save magasin
    public function saveMagasin($data)
    {
        return $this->magasinInterface->saveMagasin($data);
    }

    // Update magasin
    public function updateMagasin($magasin, $data)
    {
        return $this->magasinInterface->updateMagasin($magasin, $data);
    }

    // Delete magasin
    public function deleteMagasin($magasin)
    {
        return $this->magasinInterface->deleteMagasin($magasin);
    }
}