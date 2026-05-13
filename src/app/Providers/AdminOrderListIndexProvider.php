<?php

namespace App\Providers;

use App\Contracts\AdminOrderIndexContract;
use Illuminate\Support\ServiceProvider;
use App\Actions\AdminOrderIndexAction;
use Illuminate\Support\Facades\App;

class AdminOrderListIndexProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminOrderIndexContract::class, AdminOrderIndexAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
