<?php

namespace App\Providers;

use App\Contracts\AdminProductShowContract;
use App\Actions\AdminProductShowAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AdminProductShowProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminProductShowContract::class, AdminProductShowAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
