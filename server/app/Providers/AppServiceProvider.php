<?php

namespace App\Providers;
//Interfaces
use App\Interfaces\ClientInterface;
use App\Interfaces\ProductInterface;
use App\Interfaces\FournisseurInterface;

//Repositories
use App\Repositories\ClientRepository;
use App\Repositories\ProductRepository;
use App\Repositories\FournisseurRepository;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(FournisseurInterface::class, FournisseurRepository::class);
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(ClientInterface::class, ClientRepository::class);
    }

    public function boot()
    {
        //
    }
}