<?php

namespace App\Providers;

use App\Contracts\AdminUserCreateContract;
use App\Actions\AdminUserCreateAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AdminUserCreateProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminUserCreateContract::class, AdminUserCreateAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
