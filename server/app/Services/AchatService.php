<?php

namespace App\Services;

use App\Interfaces\AchatInterface;
use App\Models\Achat;
use App\Models\DetailProduct;
use Illuminate\Support\Facades\DB;
use Exception;

class AchatService
{
    protected $achatInterface;

    public function __construct(AchatInterface $achatInterface)
    {
        $this->achatInterface = $achatInterface;
    }

    public function getAchats()
    {
        return $this->achatInterface->getAchats();
    }

    public function saveAchat(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Vérifier si le produit a assez de stock dans DetailProduct
            $totalStock = DetailProduct::where('product_id', $data['product_id'])->sum('qte');

            if ($totalStock < $data['qte']) {
                throw new Exception("Stock insuffisant pour cet achat.");
            }

            // Enregistrer l'achat
            $achat = Achat::create([
                'client_id' => $data['client_id'],
                'product_id' => $data['product_id'],
                'qte' => $data['qte'],
            ]);

            // Déduire la quantité de chaque DetailProduct
            $remainingQte = $data['qte'];
            $detailProducts = DetailProduct::where('product_id', $data['product_id'])
                ->orderBy('id')
                ->lockForUpdate() // Lock rows to prevent race conditions
                ->get();

            foreach ($detailProducts as $detail) {
                if ($remainingQte <= 0) {
                    break;
                }

                if ($detail->qte >= $remainingQte) {
                    $detail->qte -= $remainingQte;
                    $detail->save();
                    $remainingQte = 0;
                } else {
                    $remainingQte -= $detail->qte;
                    $detail->qte = 0;
                    $detail->save();
                }
            }

            return $achat;
        });
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