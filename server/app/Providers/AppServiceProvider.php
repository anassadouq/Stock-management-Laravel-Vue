<?php

namespace App\Providers;
//Interfaces
use App\Interfaces\AchatInterface;
use App\Interfaces\ClientInterface;
use App\Interfaces\ProductInterface;
use App\Interfaces\FournisseurInterface;
use App\Interfaces\MagasinInterface;

//Repositories
use App\Repositories\AchatRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ProductRepository;
use App\Repositories\FournisseurRepository;
use App\Repositories\MagasinRepository;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(FournisseurInterface::class, FournisseurRepository::class);
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(ClientInterface::class, ClientRepository::class);
        $this->app->bind(AchatInterface::class, AchatRepository::class);
        $this->app->bind(MagasinInterface::class, MagasinRepository::class);
    }        

    public function boot()
    {
        //
    }
}