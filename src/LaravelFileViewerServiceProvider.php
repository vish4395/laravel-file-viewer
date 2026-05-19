<?php

namespace Vish4395\LaravelFileViewer;

use Illuminate\Support\ServiceProvider;

class LaravelFileViewerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-file-viewer');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-file-viewer.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-file-viewer'),
            ], 'views');

            $this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-file-viewer'),
            ], 'assets');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-file-viewer');

        $this->app->singleton('laravel-file-viewer', function () {
            return new LaravelFileViewer;
        });
    }
}
