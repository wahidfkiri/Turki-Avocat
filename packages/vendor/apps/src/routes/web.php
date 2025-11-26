<?php 
use Vendor\Apps\Controllers\AppsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->prefix('apps')
    ->group(function () {
        Route::get('/', [AppsController::class, 'index'])
            ->name('apps.index');
    });