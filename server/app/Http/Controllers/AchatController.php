<?php

namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\AchatService;

class AchatController extends Controller
{
    protected $achatService;
    public function __construct(AchatService $achatService)
    {
        $this->achatService = $achatService;
    }

    public function index()
    {
        $achats = $this->achatService->getAchats();
        return response()->json($achats);
    }

    public function store(Request $request)
    {
        $achat = $this->achatService->saveAchat($request->all());
        return response()->json(['message' => 'Achat effectué avec succès', 'achat' => $achat]);
    }    

    public function show($client_id)
    {
        $client = Client::with('achats.product')->find($client_id);
    
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }
    
        return response()->json([
            'client' => $client,
            'achats' => $client->achats,
        ]);
    }

    public function update(Request $request, Achat $achat)
    {
        $updateAchat = $this->achatService->updateAchat($achat, $request->all());
        return response()->json($updateAchat);
    }

    public function destroy(Achat $achat)
    {
        $this->achatService->deleteAchat($achat);
        return response()->json(['message' => 'Achat deleted successfully']);
    }
}