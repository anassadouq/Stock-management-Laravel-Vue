<?php

namespace App\Providers;
//Interfaces
use App\Interfaces\UserInterface;
use App\Interfaces\AchatInterface;
use App\Interfaces\ProductInterface;
use App\Interfaces\ClientInterface;
use App\Interfaces\FactureInterface;
use App\Interfaces\MagasinInterface;

//Repositories
use App\Repositories\UserRepository;
use App\Repositories\AchatRepository;
use App\Repositories\ClientRepository;
use App\Repositories\FactureRepository;
use App\Repositories\MagasinRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;

use App\Interfaces\FournisseurInterface;
use App\Interfaces\DetailProductInterface;
use App\Repositories\FournisseurRepository;
use App\Repositories\DetailProductRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(FournisseurInterface::class, FournisseurRepository::class);
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(DetailProductInterface::class, DetailProductRepository::class);
        $this->app->bind(ClientInterface::class, ClientRepository::class);
        $this->app->bind(FactureInterface::class, FactureRepository::class);
        $this->app->bind(AchatInterface::class, AchatRepository::class);
        $this->app->bind(MagasinInterface::class, MagasinRepository::class);
    }

    public function boot()
    {
        //
    }
}