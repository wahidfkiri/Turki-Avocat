<?php

use Illuminate\Support\Facades\Route;
use Vendor\Intervenant\Controllers\IntervenantController;


Auth::routes();
Route::middleware(['web','auth','active'])
    ->group(function () {

Route::resource('intervenants', IntervenantController::class);
Route::get('intervenants/search', [IntervenantController::class, 'search']);
Route::post('intervenants/{intervenant}/attach-dossier', [IntervenantController::class, 'attachDossier']);
Route::post('intervenants/detach-intervenant', [IntervenantController::class, 'detachIntervenant'])->name('intervenants.detach-intervenant');
Route::delete('intervenant-files/{file}', [IntervenantController::class, 'destroyFile'])->name('intervenants.files.destroy');
Route::get('intervenant/download/{file}', [IntervenantController::class,'downloadFile']);
Route::get('intervenant/display/{file}', [IntervenantController::class,'displayFile']);
Route::get('/intervenants/{intervenant}/files', [IntervenantController::class, 'getFiles'])->name('intervenants.files');
Route::post('/intervenant/download', [IntervenantController::class, 'downloadFile'])->name('intervenant.download');
// Route::post('/dossier/view', [DossierController::class, 'viewFile'])->name('dossier.view');

// POST: select file
Route::post('/intervenant/view/chrome', [IntervenantController::class, 'viewFileChrome'])->name('intervenant.view.chrome');



// Gardez l'ancienne route GET pour la compatibilité si nécessaire
Route::get('/intervenants/download/{intervenantId}/{fileName}', [IntervenantController::class, 'downloadFile'])->name('intervenant.download.get');
Route::post('/intervenants/{intervenant}/delete', [IntervenantController::class, 'deleteFile'])->name('intervenant.delete');
Route::post('/intervenants/{intervenant}/rename', [IntervenantController::class, 'renameFile'])->name('intervenant.rename');
Route::post('/intervenants/{intervenant}/move', [IntervenantController::class, 'moveFile'])->name('intervenant.move');
Route::get('/intervenants/{intervenant}/folders', [IntervenantController::class, 'getFoldersTree'])->name('intervenant.folders');
Route::post('/intervenants/{intervenant}/create-folder', [IntervenantController::class, 'createFolder'])->name('intervenant.create-folder');
Route::post('/intervenants/{intervenant}/file-url', [IntervenantController::class, 'getFileUrl'])->name('intervenant.file-url');
Route::post('/intervenants/{intervenant}/upload-folder', [IntervenantController::class, 'uploadFolder'])->name('intervenant.upload-folder');
Route::get('/intervenants/{intervenant}/files', [IntervenantController::class, 'getFiles'])->name('intervenant.files');
Route::post('/intervenants/{intervenant}/upload', [IntervenantController::class, 'uploadFiles'])->name('intervenant.upload');
    });