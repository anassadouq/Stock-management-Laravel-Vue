<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use App\Services\FournisseurService;

class FournisseurController extends Controller
{
    protected $fournisseurService;

    public function __construct(FournisseurService $fournisseurService)
    {
        $this->fournisseurService = $fournisseurService;
    }

    public function index()
    {
        $fournisseurs = $this->fournisseurService->getFournisseurs();
        return response()->json($fournisseurs);
    }

    public function store(Request $request)
    {
        $fournisseur = $this->fournisseurService->saveFournisseur($request->all());
        return response()->json($fournisseur);
    }

    public function show(Fournisseur $fournisseur)
    {
        return response()->json(['fournisseur' => $fournisseur]);
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $updateFournisseur = $this->fournisseurService->updateFournisseur($fournisseur, $request->all());
        return response()->json($updateFournisseur);
    }

    public function destroy(Fournisseur $fournisseur)
    {
        return $this->fournisseurService->deleteFournisseur($fournisseur);
    }
}