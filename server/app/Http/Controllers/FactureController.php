<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\FactureService;

class FactureController extends Controller
{
    protected $factureService;
    public function __construct(FactureService $factureService)
    {
        $this->factureService = $factureService;
    }

    public function index()
    {
        $factures = $this->factureService->getFactures();
        return response()->json($factures);
    }

    public function store(Request $request)
    {
        $facture = $this->factureService->saveFacture($request->all());
        return response()->json(['message' => 'facture effectué avec succès', 'facture' => $facture]);
    }

    public function show($client_id)
    {
        $client = Client::with('facture')->find($client_id);
    
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }
    
        return response()->json([
            'client' => $client,
            'facture' => $client->facture,
        ]);
    }
    
    public function update(Request $request, Facture $facture)
    {
        $updateFacture = $this->factureService->updateFacture($facture, $request->all());
        return response()->json($updateFacture);
    }

    public function destroy(Facture $facture)
    {
        $this->factureService->deleteFacture($facture);
        return response()->json(['message' => 'facture deleted successfully']);
    }
}