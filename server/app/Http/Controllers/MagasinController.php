<?php

namespace App\Http\Controllers;

use App\Models\Magasin;
use Illuminate\Http\Request;
use App\Services\MagasinService;

class MagasinController extends Controller
{
    protected $magasinService;

    public function __construct(MagasinService $magasinService)
    {
        $this->magasinService = $magasinService;
    }   

    public function index()
    {
        $magasins = $this->magasinService->getMagasins();
        return response()->json($magasins);
    }

    public function store(Request $request)
    {
        $magasin = $this->magasinService->saveMagasin($request->all());
        return response()->json($magasin);
    }

    public function show(Magasin $magasin)
    {
        return response()->json(['magasin' => $magasin]);
    }

    public function update(Request $request, Magasin $magasin)
    {
        $updateMagasin = $this->magasinService->updateMagasin($magasin, $request->all());
        return response()->json($updateMagasin);
    }    

    public function destroy(Magasin $magasin)
    {
        return $this->magasinService->deleteMagasin($magasin);
    }
}