<?php

use Illuminate\Support\Facades\Route;
use Vendor\Agenda\Controllers\AgendaController;


Auth::routes();
Route::middleware(['web','auth','active'])
    ->group(function () {
Route::resource('agendas', AgendaController::class);
Route::get('agendas/by-date-range', [AgendaController::class, 'byDateRange']);
Route::get('dossiers/{dossierId}/agendas', [AgendaController::class, 'byDossier']);
Route::get('/get/agendas/data', [AgendaController::class, 'getAgendasData'])->name('agendas.data');
Route::get('/get/agendas/data/{dossierId}', [AgendaController::class, 'getAgendasDataByDossierId'])->name('agendas.data.by.dossier');
Route::post('agenda-categories', [AgendaController::class, 'storeCategorieAgenda'])->name('agenda-categories.store');
Route::put('agendas/categories/{id}', [AgendaController::class, 'updateCategorieAgenda'])->name('agenda-categories.update');
Route::delete('agendas/categories/{id}', [AgendaController::class, 'deleteCategorieAgenda'])->name('agenda-categories.delete');
Route::get('/api/agenda-categories', [AgendaController::class, 'apiIndex'])->name('agenda-categories.api');
Route::get('agendas/download/{id}', [AgendaController::class, 'downloadFile']);

});