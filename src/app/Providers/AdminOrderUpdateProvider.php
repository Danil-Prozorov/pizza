<?php

namespace App\Providers;

use App\Contracts\AdminOrderUpdateContract;
use Illuminate\Support\ServiceProvider;
use App\Actions\AdminOrderUpdateAction;
use Illuminate\Support\Facades\App;

class AdminOrderUpdateProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminOrderUpdateContract::class, AdminOrderUpdateAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
