<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\View\Composers\NotificationComposer;
use Illuminate\Support\Facades;

class NotificationServiceProvider extends ServiceProvider
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
        Facades\View::composer('web.*',NotificationComposer::class);
    }
}
