<?php

use Illuminate\Support\Facades\Route;
use Vendor\Facture\Controllers\FactureController;


Auth::routes();
Route::middleware(['web','auth','active'])
    ->group(function () {
Route::resource('factures', FactureController::class);
 Route::get('/get/factures/data', [FactureController::class, 'getFacturesData'])->name('factures.data');
 Route::get('/dossier/{dossier}/factures/data', [FactureController::class, 'getDataTable'])->name('dossier.factures.data');
Route::get('/factures/{facture}/datas', [FactureController::class, 'getData'])->name('factures.datas');
 Route::get('/get/factures/data/paid', [FactureController::class, 'getPaidFacturesData'])->name('factures.paid.data');
 Route::get('/factures/data/paid', [FactureController::class, 'indexpaid'])->name('factures.paid.index');
 Route::get('/get/factures/data/unpaid', [FactureController::class, 'getUnpaidFacturesData'])->name('factures.unpaid.data');
 Route::get('/factures/data/unpaid', [FactureController::class, 'indexUnpaid'])->name('factures.unpaid.index');
 Route::get('/factures/{facture}/pdf', [FactureController::class, 'downloadPDF'])->name('factures.pdf');
Route::get('dossiers/{dossierId}/factures', [FactureController::class, 'byDossier']);
Route::get('factures/status/{statut}', [FactureController::class, 'byStatus']);
Route::patch('factures/{facture}/status', [FactureController::class, 'updateStatus']);
Route::get('factures/generate-number', [FactureController::class, 'generateNumber']);
Route::get('factures/download/{id}', [FactureController::class,'downloadFile']);
Route::get('factures/display/{id}', [FactureController::class,'displayFile'])->name('factures.display');
Route::get('/factures/{facture}/data', [FactureController::class, 'getFactureData'])->name('factures.data.get');

});