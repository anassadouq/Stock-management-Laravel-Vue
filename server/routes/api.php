<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FournisseurController;

Route::resource('fournisseur', FournisseurController::class);

Route::resource('product', ProductController::class);
Route::get('product/show/{fournisseur_id}', [ProductController::class, 'show']);

Route::resource('client', ClientController::class);
Route::get('client/show/{product_id}', [ClientController::class, 'show']);