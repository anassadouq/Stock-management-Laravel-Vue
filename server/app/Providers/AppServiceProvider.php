<?php

namespace App\Providers;
//Interfaces
use App\Interfaces\FournisseurInterface;
use App\Interfaces\ProductInterface;

//Repositories
use App\Repositories\ProductRepository;
use App\Repositories\FournisseurRepository;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(FournisseurInterface::class, FournisseurRepository::class);
        $this->app->bind(ProductInterface::class, ProductRepository::class);
    }

    public function boot()
    {
        //
    }
}