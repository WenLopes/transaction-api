<?php

namespace App\Providers;

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

        $this->app->bind( 
            \App\Services\Transaction\Transfer\CompleteTransferServiceInterface::class, 
            \App\Services\Transaction\Transfer\CompleteTransferService::class
        );

        $this->app->bind( 
            \App\Services\Transaction\Transfer\RollbackTransferServiceInterface::class, 
            \App\Services\Transaction\Transfer\RollbackTransferService::class
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
