<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Add database connection event listeners for debugging
        DB::listen(function ($query) {
            if (app()->environment('local') && is_desktop()) {
                Log::channel('desktop')->debug('DB Query', [
                    'query' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time
                ]);
            }
        });

        // Handle database connection errors for desktop app
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            if (is_desktop()) {
                Log::channel('desktop')->error('Database connection failed', [
                    'error' => $e->getMessage()
                ]);
                
                // You could show a dialog to user in desktop mode
                if (app()->runningInConsole()) {
                    echo "Database connection failed! Please check your MySQL server.\n";
                }
            }
        }
    }
}