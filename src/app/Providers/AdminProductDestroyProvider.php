<?php

namespace App\Providers;

use App\Contracts\AdminProductDestroyContract;
use App\Actions\AdminProductDestroyAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AdminProductDestroyProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminProductDestroyContract::class, AdminProductDestroyAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
