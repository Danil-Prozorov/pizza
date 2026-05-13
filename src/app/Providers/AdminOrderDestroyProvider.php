<?php

namespace App\Providers;

use App\Contracts\AdminOrderDestroyContract;
use App\Actions\AdminOrderDestroyAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AdminOrderDestroyProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminOrderDestroyContract::class, AdminOrderDestroyAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
