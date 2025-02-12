<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Services\ClientService;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index()
    {
        $clients = $this->clientService->getClients();
        return response()->json($clients);
    }

    public function store(Request $request)
    {
        $client = $this->clientService->saveClient($request->all());
        return response()->json($client);
    }

    public function show(Client $client)
    {
        return response()->json(['client' => $client]);
    }

    public function update(Request $request, Client $client)
    {
        $updateClient = $this->clientService->updateClient($client, $request->all());
        return response()->json($updateClient);
    }    

    public function destroy(Client $client)
    {
        return $this->clientService->deleteClient($client);
    }
}