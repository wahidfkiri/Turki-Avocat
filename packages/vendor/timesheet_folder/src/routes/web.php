<?php

use Illuminate\Support\Facades\Route;
use Vendor\TimesheetFolder\Controllers\TimesheetFolderController;


Auth::routes();
// Routes pour les feuilles de temps dans le contexte d'un dossier
Route::middleware(['web', 'auth', 'active'])->group(function () {
    // Routes pour les timesheets d'un dossier spécifique
    Route::prefix('dossiers/{dossier}')->group(function () {
        // DataTable pour les timesheets du dossier
        Route::get('/timesheets/data', [TimesheetFolderController::class, 'getDossierTimesheetsData'])
            ->name('dossiers.timesheets.data');
        Route::get('/timesheets/{timesheet}/edit-data', [TimesheetFolderController::class, 'getTimesheetEditData'])
    ->name('dossiers.timesheets.edit');  // ✅ CORRECT
        // Création de timesheet pour un dossier spécifique
        Route::post('/timesheets/store', [TimesheetFolderController::class, 'storeForDossier']);
        
        // Mise à jour de timesheet dans un dossier
        Route::put('/timesheets/{timesheet}', [TimesheetFolderController::class, 'updateForDossier'])
            ->name('dossiers.timesheets.update');
        
        // Suppression de timesheet dans un dossier
        Route::delete('/timesheets/{timesheet}', [TimesheetFolderController::class, 'destroyForDossier'])
            ->name('dossiers.timesheets.destroy');
                // Route pour voir les détails d'une timesheet
    Route::get('/timesheets/{timesheet}/details', [TimesheetFolderController::class, 'getTimesheetDetails'])
        ->name('dossiers.timesheets.details');
    });
    
    // Routes globales pour les catégories/types
    Route::get('/categories/ajax', [TimesheetFolderController::class, 'getCategoriesAjax'])
        ->name('categories.ajax');
    
    Route::get('/categories/{categorie}/types', [TimesheetFolderController::class, 'getTypesByCategory'])
        ->name('categories.types');
    

});