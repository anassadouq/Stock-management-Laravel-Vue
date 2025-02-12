<?php

namespace App\Repositories;

use App\Models\Client;
use App\Interfaces\ClientInterface;

class ClientRepository implements ClientInterface
{
    // Fetch Clients
    public function getClients()
    {
        return Client::all();
    }

    // Save Client
    public function saveClient($client)
    {
        return Client::create($client);
    }

    // Update Client
    public function updateClient($client, $data)
    {
        if ($client->update($data)) {
            return $client->fresh(); // Simply return updated client
        }
        return null; // Let the service/controller handle errors
    }
    

    // Delete Client
    public function deleteClient($client)
    {
        if ($client->delete()) {
            return response()->json(['message' => 'Client deleted successfully']);
        }
        return response()->json(['error' => 'Failed to delete client'], 500);
    }
}