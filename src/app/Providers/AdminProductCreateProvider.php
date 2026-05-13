<?php

namespace App\Providers;

use App\Contracts\AdminProductCreateContract;
use App\Actions\AdminProductCreationAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AdminProductCreateProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::singleton(AdminProductCreateContract::class, AdminProductCreationAction::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
