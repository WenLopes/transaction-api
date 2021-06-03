<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /** User must be active */
        Validator::extend('user_active', 'App\Http\Validators\UserActive@passes');

        /** User cannot be a seller */
        Validator::extend('user_not_seller', 'App\Http\Validators\UserNotSeller@passes');

        /** ser must have a balance greater than the value of the transaction */
        Validator::extend('user_has_balance', 'App\Http\Validators\UserHasBalance@passes');
    }
}
