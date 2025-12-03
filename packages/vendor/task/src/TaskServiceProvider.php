<?php

namespace Vendor\Task;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
// use Vendor\Storage\Components\GoogleDrive\MainApp;

class TaskServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/Views', 'task');


        // Blade::component('drive-google-drive-main-app', MainApp::class);

        // Publish assets if needed
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/task'),
        ], 'task-assets');
    }

    public function register()
    {
        // Register package services
    }
}
