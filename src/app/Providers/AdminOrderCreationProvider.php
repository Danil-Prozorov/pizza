<?php

namespace App\Providers;

use App\Contracts\AdminOrderCreationContract;
use Illuminate\Support\ServiceProvider;
use App\Actions\AdminOrderCreationAction;
use Illuminate\Support\Facades\App;

class AdminOrderCreationProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        /* $this->app->singleton(AdminOrderContract::class, function ($app) {
            return new AdminOrderCreation();
        }); */

        App::singleton(AdminOrderCreationContract::class, AdminOrderCreationAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
