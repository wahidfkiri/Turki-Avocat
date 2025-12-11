<?php

namespace Vendor\Webmail;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
// use Vendor\Storage\Components\GoogleDrive\MainApp;

class WebmailServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/Views', 'webmail');


        // Blade::component('drive-google-drive-main-app', MainApp::class);

        // Publish assets if needed
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/webmail'),
        ], 'webmail-assets');
    }

    public function register()
    {
        // Register package services
    }
}
