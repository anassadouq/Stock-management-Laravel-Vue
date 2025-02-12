<?php

namespace App\Services;

use App\Interfaces\ClientInterface;

class ClientService
{
    public $clientInterface;

    public function __construct(ClientInterface $clientInterface)
    {
        $this->clientInterface = $clientInterface;
    }

    // Fetch Clients
    public function getClients()
    {
        return $this->clientInterface->getClients();
    }

    // Save Client
    public function saveClient($data)
    {
        return $this->clientInterface->saveClient($data);
    }

    // Update Client
    public function updateClient($client, $data)
    {
        return $this->clientInterface->updateClient($client, $data);
    }

    // Delete Client
    public function deleteClient($client)
    {
        return $this->clientInterface->deleteClient($client);
    }
}