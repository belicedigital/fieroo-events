<?php

namespace Fieroo\Events\Providers;

use Illuminate\Support\ServiceProvider;

class EventsProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../views', 'events');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        
    }
}