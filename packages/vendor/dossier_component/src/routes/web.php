<?php

use Illuminate\Support\Facades\Route;
use Vendor\DossierComponent\Controllers\DossierComponentController;


Auth::routes();
Route::middleware(['web','auth','active'])
    ->group(function () {
// routes/web.php

// Routes pour la gestion des utilisateurs du dossier
Route::get('/dossiers/{id}/utilisateurs-data', [DossierComponentController::class, 'getUtilisateursData'])->name('dossiers.utilisateurs.data');
Route::post('/dossiers/{id}/attach-utilisateur', [DossierComponentController::class, 'attachUtilisateur'])->name('dossiers.attach.utilisateur');
Route::post('/dossiers/{id}/detach-utilisateur', [DossierComponentController::class, 'detachUtilisateur'])->name('dossiers.detach.utilisateur');

// Notes 
Route::post('/dossiers/{id}/update-notes', [DossierComponentController::class, 'updateNotes'])->name('dossiers.update.notes');
Route::get('/dossiers/{id}/get-notes', [DossierComponentController::class, 'getNotes'])->name('dossiers.get.notes');
});