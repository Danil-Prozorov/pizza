<?php

namespace App\Providers;

use App\Contracts\AdminUserDestroyContract;
use App\Actions\AdminUserDestroyAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AdminUserDestroyProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminUserDestroyContract::class, AdminUserDestroyAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
