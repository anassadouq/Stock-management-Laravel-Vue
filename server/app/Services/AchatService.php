<?php

namespace App\Services;

use App\Interfaces\AchatInterface;

class AchatService
{
    public $achatInterface;

    public function __construct(AchatInterface $achatInterface)
    {
        $this->achatInterface = $achatInterface;
    }

    public function getAchats()
    {
        return $this->achatInterface->getAchats();
    }

    public function saveAchat($data)
    {    
        return $this->achatInterface->saveAchat($data);
    }
    
    public function updateAchat($achat, $data)
    {
        return $this->achatInterface->updateAchat($achat, $data);
    }

    public function showAchat($client_id)
    {
        return $this->achatInterface->showAchat($client_id);
    }

    public function deleteAchat($achat)
    {
        return $this->achatInterface->deleteAchat($achat);
    }
}