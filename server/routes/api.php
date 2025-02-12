<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FournisseurController;

Route::resource('fournisseur', FournisseurController::class);

Route::resource('product', ProductController::class);

Route::resource('client', ClientController::class);
