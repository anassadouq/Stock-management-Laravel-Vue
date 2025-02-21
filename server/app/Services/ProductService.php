<?php

namespace App\Services;

use App\Interfaces\ProductInterface;

class ProductService
{
    public $productInterface;

    public function __construct(ProductInterface $productInterface)
    {
        $this->productInterface = $productInterface;
    }

    public function getProducts()
    {
        return $this->productInterface->getProducts();
    }

    public function saveProduct($data)
    {
        return $this->productInterface->saveProduct($data);
    }

    public function updateProduct($product, $data)
    {
        return $this->productInterface->updateProduct($product, $data);
    }

    public function showProduct($magasin_id)
    {
        return $this->productInterface->showProduct($magasin_id);
    }

    public function deleteProduct($product)
    {
        return $this->productInterface->deleteProduct($product);
    }
}