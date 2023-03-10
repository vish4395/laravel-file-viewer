<?php

namespace Vish4395\LaravelFileViewer;

use Illuminate\Support\ServiceProvider;

class LaravelFileViewerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-file-viewer');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-file-viewer');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-file-viewer.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([  
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-file-viewer'),
            ], 'views');

            // Publishing assets.
            $this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-file-viewer'),
            ], 'assets');

            // Publishing the translation files.
            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-file-viewer'),
            ], 'lang');

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-file-viewer');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-file-viewer', function () {
            return new LaravelFileViewer;
        });

        $this->app->bind('LaravelFileViewer', function($app) {
            return new LaravelFileViewer();
        });
    }
}
