<?php

namespace App\Providers;

use App\Contracts\AdminOrderUpdateProductContract;
use App\Actions\AdminOrderUpdateProductAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AdminOrderUpdateProductProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminOrderUpdateProductContract::class, AdminOrderUpdateProductAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
