<?php

namespace App\Interfaces;

interface ProductInterface
{
    // Fetch Products
    public function getProducts();

    // Save Product
    public function saveProduct($data);

    // Show Product
    public function showProduct($magasin_id);

    // Update Product
    public function updateProduct($product, $data);

    // Delete Product
    public function deleteProduct($product);
}