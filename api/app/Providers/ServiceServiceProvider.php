<?php

namespace App\Providers;

use App\Services\Transaction\Transfer\CreateTransferService;
use App\Services\Transaction\Transfer\CreateTransferServiceInterface;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {        
        //Transfer
        $this->app->bind( 
            \App\Services\Transaction\Transfer\CreateTransferServiceInterface::class, 
            \App\Services\Transaction\Transfer\CreateTransferService::class
        );

        //Authorization
        $this->app->bind( 
            \App\Services\Transaction\Authorization\AuthorizationServiceInterface::class, 
            \App\Services\Transaction\Authorization\AuthorizationService::class
        );

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
