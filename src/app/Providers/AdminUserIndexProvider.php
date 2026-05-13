<?php

namespace App\Providers;

use App\Contracts\AdminUserIndexContract;
use Illuminate\Support\ServiceProvider;
use App\Actions\AdminUserIndexAction;
use Illuminate\Support\Facades\App;

class AdminUserIndexProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminUserIndexContract::class, AdminUserIndexAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
