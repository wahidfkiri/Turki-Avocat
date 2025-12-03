<?php

namespace Vendor\Facture;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
// use Vendor\Storage\Components\GoogleDrive\MainApp;

class FactureServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/Views', 'facture');


        // Blade::component('drive-google-drive-main-app', MainApp::class);

        // Publish assets if needed
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/facture'),
        ], 'facture-assets');
    }

    public function register()
    {
        // Register package services
    }
}
