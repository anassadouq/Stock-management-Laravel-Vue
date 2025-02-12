<?php

namespace App\Interfaces;

interface ClientInterface
{
    // Fetch all Clients
    public function getClients();

    // Save Client
    public function saveClient($data);

    // Update Client
    public function updateClient($client, $data);

    // Update Client
    public function deleteClient($client);
}