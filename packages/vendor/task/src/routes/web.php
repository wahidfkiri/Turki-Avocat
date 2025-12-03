<?php

use Illuminate\Support\Facades\Route;
use Vendor\Task\Controllers\TaskController;


Auth::routes();
Route::middleware(['web','auth','active'])
    ->group(function () {

Route::resource('tasks', TaskController::class);
Route::get('tasks/status/{statut}', [TaskController::class, 'byStatus']);
Route::get('users/{userId}/tasks', [TaskController::class, 'byUser']);
Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus']);
Route::get('/get/tasks/data', [TaskController::class, 'getTasksData'])->name('tasks.data');
Route::get('tasks/{taskId}/download', [TaskController::class, 'downloadFile'])->name('tasks.download');
Route::get('/tasks/{task}/data', [TaskController::class, 'getTaskData'])->name('tasks.data.get');
Route::get('tasks/download/{id}', [TaskController::class,'downloadFile']);
Route::get('tasks/display/{id}', [TaskController::class,'displayFile']);

});