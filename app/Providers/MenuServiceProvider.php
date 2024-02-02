<?php

namespace App\Providers;

use App\View\Composers\MenuComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Facades\View::composer('web.*',MenuComposer::class);
    }
}
