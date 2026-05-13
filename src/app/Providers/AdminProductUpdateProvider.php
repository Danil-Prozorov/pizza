<?php

namespace App\Providers;

use App\Contracts\AdminProductUpdateContract;
use App\Actions\AdminProductUpdateAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AdminProductUpdateProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminProductUpdateContract::class, AdminProductUpdateAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
