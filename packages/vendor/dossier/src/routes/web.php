<?php

use Illuminate\Support\Facades\Route;
use Vendor\Dossier\Controllers\DossierController;


Auth::routes();
Route::middleware(['web','auth','active'])
    ->group(function () {
Route::resource('dossiers', DossierController::class);
Route::get('get/dossiers/data', [DossierController::class, 'getDossiersData'])->name('dossiers.data');
Route::post('dossiers/{dossier}/attach-user', [DossierController::class, 'attachUser']);
Route::post('dossiers/{dossier}/attach-intervenant', [DossierController::class, 'attachIntervenant']);
Route::post('dossiers/{dossier}/detach-intervenant', [DossierController::class, 'detachIntervenant']);
Route::post('dossiers/{dossier}/link-dossier', [DossierController::class, 'linkDossier']);
Route::get('/sous-domaines/by-domaine', [DossierController::class, 'getSousDomainesByDomaine'])->name('sous-domaines.by-domaine');
Route::get('/get-sous-domaines', [DossierController::class, 'getSousDomaines'])->name('get.sous-domaines');
Route::get('dossier/task/create/{dossier}', [DossierController::class, 'createForDossier'])->name('dossiers.tasks.create');
Route::post('dossier/task/create/{dossier}', [DossierController::class, 'storeForDossier'])->name('dossiers.tasks.store');
Route::get('dossier/timeSheets/create/{dossier}', [DossierController::class, 'createTimeSheetForDossier'])->name('dossiers.timesheets.create');
Route::post('dossier/timeSheets/create/{dossier}', [DossierController::class, 'storeTimeSheetForDossier'])->name('dossiers.timesheets.store');
Route::get('dossier/facturation/create/{dossier}', [DossierController::class, 'createFactureForDossier'])->name('dossiers.facturation.create');
Route::post('dossier/facturation/create/{dossier}', [DossierController::class, 'storeFactureForDossier'])->name('dossiers.facturation.store');
Route::get('/dossiers/{dossier}/files', [DossierController::class, 'getFiles'])->name('dossiers.files');
Route::post('/dossiers/{dossier}/upload', [DossierController::class, 'uploadFiles'])->name('dossiers.upload');
// Route pour le téléchargement via POST
Route::post('/dossier/download', [DossierController::class, 'downloadFile'])->name('dossier.download');

// POST: select file
Route::post('/dossier/view', [DossierController::class, 'viewFilePost'])->name('dossier.view.post');

Route::post('/dossier/view/chrome', [DossierController::class, 'viewFileChrome'])->name('dossier.view.chrome');

// GET: open OnlyOffice editor
Route::get('/dossier/view/{dossier}/{file}', [DossierController::class, 'viewFile'])
    ->where('file', '.*')
    ->name('dossier.view');

    
// Gardez l'ancienne route GET pour la compatibilité si nécessaire
Route::get('/dossier/download/{dossierId}/{fileName}', [DossierController::class, 'downloadFile'])->name('dossier.download.get');
Route::post('/dossiers/{dossier}/delete', [DossierController::class, 'deleteFile'])->name('dossiers.delete');
Route::post('/dossiers/{dossier}/rename', [DossierController::class, 'renameFile'])->name('dossiers.rename');
Route::post('/dossiers/{dossier}/move', [DossierController::class, 'moveFile'])->name('dossiers.move');
Route::get('/dossiers/{dossier}/folders', [DossierController::class, 'getFoldersTree'])->name('dossiers.folders');
Route::post('/dossiers/{dossier}/create-folder', [DossierController::class, 'createFolder'])->name('dossiers.create-folder');
Route::post('/dossiers/{dossier}/file-url', [DossierController::class, 'getFileUrl'])->name('dossiers.file-url');
Route::post('/dossiers/{dossier}/upload-folder', [DossierController::class, 'uploadFolder'])->name('dossiers.upload-folder');

Route::get('/dossier/{dossier}/tasks/data', [DossierController::class, 'getTasksData'])->name('dossier.tasks.data');
});