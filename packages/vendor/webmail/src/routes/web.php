<?php

use Illuminate\Support\Facades\Route;
use Vendor\Webmail\Controllers\RoundcubeController;


Auth::routes();
Route::middleware(['web','auth','active'])
    ->group(function () {


           Route::get('/webmail', [RoundcubeController::class, 'showIframe'])
         ->name('webmail.index');

   Route::get('/webmails', [RoundcubeController::class, 'embedWebmail'])
         ->name('webmail.embedded');
    
    // Proxy for AJAX requests (NO PARAMETER!)
    Route::match(['GET', 'POST'], '/webmails/proxy', [RoundcubeController::class, 'proxyRequest'])
         ->name('webmail.proxy');
    
    // Simple page
    Route::get('/webmail/simple', [RoundcubeController::class, 'simple'])
         ->name('webmail.simple');
    
});

