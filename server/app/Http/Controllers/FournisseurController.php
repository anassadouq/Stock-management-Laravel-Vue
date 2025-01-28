<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FournisseurController extends Controller {

    public function index()
    {
        return response(Fournisseur::all());
    }

    public function store(Request $request)
    {
        return response(Fournisseur::create($request->all()));
    }

    public function show(Fournisseur $fournisseur){
        return response()->json([
            'fournisseur' => $fournisseur
        ]);
    }
 
    public function update(Request $request, Fournisseur $fournisseur){
        $fournisseur->update($request->all());
        return response()->json([
            'message' => 'Item updated successfully'
        ]);
    }

    public function destroy(Fournisseur $fournisseur)
    {
        return response($fournisseur->delete());
    }
}