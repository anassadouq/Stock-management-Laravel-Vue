<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\FournisseurInterface;
use App\Repositories\FournisseurRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(FournisseurInterface::class, FournisseurRepository::class);
    }

    public function boot()
    {
        //
    }
}