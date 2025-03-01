<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AchatController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\MagasinController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\DetailProductController;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');;

// Magasin
Route::resource('magasin', MagasinController::class);

// Fournisseur
Route::resource('fournisseur', FournisseurController::class);

// Product
Route::resource('product', ProductController::class);
Route::get('product/show/{magasin_id}', [ProductController::class, 'show']);

// Detail_product
Route::resource('detail_product', DetailProductController::class);
Route::get('detail_product/show/{product_id}', [DetailProductController::class, 'show']);

// Client
Route::resource('client', ClientController::class);

// Facture
Route::resource('facture', FactureController::class);
Route::get('facture/show/{client_id}', [FactureController::class, 'show']);
// Achat
Route::resource('achat', AchatController::class);
Route::get('achat/show/{facture_id}', [AchatController::class, 'show']);