<?php

namespace App\Services;

use App\Interfaces\FactureInterface;
use App\Models\Facture;
use Illuminate\Support\Facades\DB;
use Exception;

class FactureService
{
    protected $factureInterface;

    public function __construct(FactureInterface $factureInterface)
    {
        $this->factureInterface = $factureInterface;
    }

    public function getFactures()
    {
        return $this->factureInterface->getFactures();
    }

    public function saveFacture(array $data)
    {
        return $this->factureInterface->saveFacture($data);
    }

    public function updateFacture($facture, $data)
    {
        return $this->factureInterface->updateFacture($facture, $data);
    }

    public function showFacture($client_id)
    {
        return $this->factureInterface->showFacture($client_id);
    }

    public function deleteFacture($facture)
    {
        return $this->factureInterface->deleteFacture($facture);
    }
}