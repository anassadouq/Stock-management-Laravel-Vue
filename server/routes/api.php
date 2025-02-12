<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AchatController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FournisseurController;

Route::resource('fournisseur', FournisseurController::class);

Route::resource('product', ProductController::class);

Route::resource('client', ClientController::class);

Route::resource('achat', AchatController::class);
Route::get('achat/show/{client_id}', [AchatController::class, 'show']);
