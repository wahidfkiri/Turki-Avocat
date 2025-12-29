<?php

use Illuminate\Support\Facades\Route;
use Vendor\Webmail\Controllers\RoundcubeController;


Auth::routes();
Route::middleware(['web','auth','active'])
    ->group(function () {
    // Accès au webmail
    Route::get('/webmail', function() {
    return $user_email = auth()->user()->email; // ex: contact@turkiavocats.com
    return redirect()->to('http://roundcube.localhost:8082/?user=' . urlencode($user_email));
});


    
    // Tests
    Route::get('/webmail/test', [RoundcubeController::class, 'testConnection'])
        ->name('webmail.test');
    
    // Logs
    Route::get('/webmail/logs', [RoundcubeController::class, 'showLogs'])
        ->name('webmail.logs');
    
    // Callbacks
    Route::get('/webmail/callback', [RoundcubeController::class, 'callback'])
        ->name('webmail.callback');
    
    // Actions AJAX
    Route::post('/webmail/test/imap', [RoundcubeController::class, 'testImapManual'])
        ->name('webmail.test.imap');
    
    Route::post('/webmail/generate/url', [RoundcubeController::class, 'generateTestUrl'])
        ->name('webmail.generate.url');
    
    Route::post('/webmail/clear/logs', [RoundcubeController::class, 'clearLogs'])
        ->name('webmail.clear.logs');
});

