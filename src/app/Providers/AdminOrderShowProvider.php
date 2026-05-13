<?php

namespace App\Providers;

use App\Contracts\AdminOrderShowContract;
use Illuminate\Support\ServiceProvider;
use App\Actions\AdminOrderShowAction;
use Illuminate\Support\Facades\App;

class AdminOrderShowProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminOrderShowContract::class, AdminOrderShowAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
