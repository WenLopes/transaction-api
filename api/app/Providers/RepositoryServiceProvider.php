<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /** UserRepo */
        $this->app->bind( 
            \App\Repositories\User\UserRepositoryInterface::class, 
            \App\Repositories\User\UserRepository::class
        );

        /** TransactionRepo */
        $this->app->bind( 
            \App\Repositories\Transaction\TransactionRepositoryInterface::class, 
            \App\Repositories\Transaction\TransactionRepository::class
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
