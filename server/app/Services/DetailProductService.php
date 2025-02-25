<?php

namespace App\Services;

use App\Interfaces\DetailProductInterface;

class DetailProductService
{
    protected $detailProductInterface;

    public function __construct(DetailProductInterface $detailProductInterface)
    {
        $this->detailProductInterface = $detailProductInterface;
    }

    public function getDetails()
    {
        return $this->detailProductInterface->getDetails();
    }

    public function saveDetail($data)
    {
        return $this->detailProductInterface->saveDetail($data);
    }

    public function updateDetail($detail_product, $data)
    {
        return $this->detailProductInterface->updateDetail($detail_product, $data);
    }

    public function showDetail($product_id)
    {
        return $this->detailProductInterface->showDetail($product_id);
    }

    public function deleteDetail($detail_product)
    {
        return $this->detailProductInterface->deleteDetail($detail_product);
    }
}