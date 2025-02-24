<?php

namespace App\Interfaces;

interface DetailProductInterface
{
    // Fetch detail_product
    public function getDetails();

    // Save detail_product
    public function saveDetail($data);

    // Show detail_product
    public function showDetail($product_id);

    // Update detail_product
    public function updateDetail($detail_product, $data);

    // Delete detail_product
    public function deleteDetail($detail_product);
}