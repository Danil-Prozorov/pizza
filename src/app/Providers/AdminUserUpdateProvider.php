<?php

namespace App\Providers;

use App\Contracts\AdminUserUpdateContract;
use Illuminate\Support\ServiceProvider;
use App\Actions\AdminUserUpdateAction;
use Illuminate\Support\Facades\App;

class AdminUserUpdateProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminUserUpdateContract::class, AdminUserUpdateAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
