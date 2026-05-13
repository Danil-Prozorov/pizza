<?php

namespace App\Providers;

use App\Contracts\AdminProductIndexContract;
use App\Actions\AdminProductIndexAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AdminProductIndexProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminProductIndexContract::class, AdminProductIndexAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
