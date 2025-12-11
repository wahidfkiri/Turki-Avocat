<?php

use Illuminate\Support\Facades\Route;
use Vendor\Timesheet\Controllers\TimesheetController;


Auth::routes();
Route::middleware(['web','auth','active'])
    ->group(function () {


Route::resource('time-sheets', TimeSheetController::class);
Route::put('dossiers/time-sheets/{timeSheetId}', [TimeSheetController::class, 'updateForDossier'])->name('time-sheets.updateForDossier');
Route::get('dossiers/{dossierId}/time-sheets', [TimeSheetController::class, 'byDossier']);
Route::get('users/{userId}/time-sheets', [TimeSheetController::class, 'byUser']);
Route::get('time-sheets/report', [TimeSheetController::class, 'report']);
Route::get('/timesheets/data', [TimesheetController::class, 'getTimesheetsData'])->name('timesheets.data');
// Routes pour les feuilles de temps
Route::get('/time-sheets/{time_sheet}/data', [TimeSheetController::class, 'getTimeSheetData'])->name('time-sheets.data.get');
Route::get('/get/categories', [TimesheetController::class, 'getCategories']);
Route::get('/get/types', [TimesheetController::class, 'getTypes']);
Route::get('/time-sheets/ajax/{timesheet}', [TimesheetController::class, 'getTimesheetAjax'])->name('time-sheets.ajax');
Route::get('/time-sheets/details/{id}', [TimesheetController::class, 'getTimesheetDetailsAjax'])->name('time-sheets.details.ajax');
Route::prefix('categories')->group(function () {
    Route::get('/ajax', [TimesheetController::class, 'ajax'])->name('categories.ajax');
    Route::post('/', [TimesheetController::class, 'storeCategorie'])->name('categories.store');
    Route::get('/{categorie}/types', [TimesheetController::class, 'getTypes'])->name('categories.types');
});
Route::get('/dossier/{dossier}/timesheets/data', [TimesheetController::class, 'getDossierTimesheetsData'])
    ->name('dossier.timesheets.data');


// Routes pour les types
Route::post('/types', [TimesheetController::class, 'storeType'])->name('types.store');
});