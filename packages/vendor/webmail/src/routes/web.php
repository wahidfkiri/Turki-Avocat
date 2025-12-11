<?php

use Illuminate\Support\Facades\Route;


Auth::routes();
Route::middleware(['web','auth','active'])
    ->group(function () {

Route::get('webmail', function () {
    return view('webmail::index');
})->name('webmail.index');

});