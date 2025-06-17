<?php

namespace Modules\Inventory\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as Provider;

class Main extends Provider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //$this->loadConfig();
        $this->loadRoutes();
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViews();
        $this->loadViewComponents();
        $this->loadMigrations();
    }

    /**
     * Load views.
     *
     * @return void
     */
    public function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'inventory');
    }

    /**
     * Load view components.
     *
     * @return void
     */
    public function loadViewComponents()
    {
        Blade::componentNamespace('Modules\Inventory\View\Components', 'inventory');
    }

    /**
     * Load migrations.
     *
     * @return void
     */
    public function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Load config.
     *
     * @return void
     */
    public function loadConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'inventory');
    }

    /**
     * Load translations.
     *
     * @return void
     */
    public function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'inventory');
    }

    /**
     * Load routes.
     *
     * @return void
     */
    public function loadRoutes()
    {
        if (app()->routesAreCached()) {
            return;
        }

        $routes = [
            'admin.php',
            'portal.php',
        ];

        foreach ($routes as $route) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/' . $route);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
