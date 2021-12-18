<?php

namespace App\Providers;

use App\Services\Payment\Payswitch;
use Illuminate\Support\ServiceProvider;

class ThetellerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('payswitch', function ($app) {
            return new PaySwitch();
        });
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
