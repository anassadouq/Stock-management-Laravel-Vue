<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AchatController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FournisseurController;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');;

// Fournisseur
Route::resource('fournisseur', FournisseurController::class);

// Product
Route::resource('product', ProductController::class);

// Client
Route::resource('client', ClientController::class);

// Achat
Route::resource('achat', AchatController::class);
Route::get('achat/show/{client_id}', [AchatController::class, 'show']);