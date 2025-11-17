<?php

use Vendor\Email\Controllers\EmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('emails')->group(function () {
    // Liste des emails (version simple - recommandée)
    Route::get('/list', [EmailController::class, 'getMailList']);
    
    // Liste des emails avec tous les détails
    Route::get('/list-detailed', [EmailController::class, 'getMailListDetailed']);
    
    // Tester la connexion IMAP
    Route::get('/test-connection', [EmailController::class, 'testConnection']);
    
    // Lister tous les dossiers
    Route::get('/folders', [EmailController::class, 'getFolders']);
    
    // Récupérer un email spécifique par UID
    Route::get('/email', [EmailController::class, 'getEmailByUid']);
});