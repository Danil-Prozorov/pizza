<?php

namespace App\Providers;

use App\Contracts\AdminUserShowContract;
use Illuminate\Support\ServiceProvider;
use App\Actions\AdminUserShowAction;
use Illuminate\Support\Facades\App;

class AdminUserShowProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminUserShowContract::class, AdminUserShowAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
