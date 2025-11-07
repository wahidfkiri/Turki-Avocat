<?php 

use Illuminate\Support\Facades\Route;
use Vendor\Drive\Controllers\DriveController;

Route::middleware(['web','auth'])->group(function () {
    // Define your package routes here
    Route::prefix('drive')->group(function () {
        Route::get('/', [DriveController::class, 'index'])->name('drive.index');
        Route::get('/dossiers/{dossier}/files', [DriveController::class, 'getFiles'])->name('drive.dossiers.files');
        Route::post('/dossiers/{dossier}/upload', [DriveController::class, 'uploadFiles'])->name('drive.dossiers.upload');
        // Route pour le téléchargement via POST
        Route::post('/dossier/download', [DriveController::class, 'downloadFile'])->name('drive.dossier.download');
        // Gardez l'ancienne route GET pour la compatibilité si nécessaire
        Route::get('/dossier/download/{dossierId}/{fileName}', [DriveController::class, 'downloadFile'])->name('drive.dossier.download.get');
        Route::post('/dossiers/{dossier}/delete', [DriveController::class, 'deleteFile'])->name('drive.dossiers.delete');
        Route::post('/dossiers/{dossier}/rename', [DriveController::class, 'renameFile'])->name('drive.dossiers.rename');
        Route::post('/dossiers/{dossier}/move', [DriveController::class, 'moveFile'])->name('drive.dossiers.move');
        Route::get('/dossiers/{dossier}/folders', [DriveController::class, 'getFoldersTree'])->name('drive.dossiers.folders');
    });
});