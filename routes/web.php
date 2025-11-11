<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{
    UserController, IntervenantController, DossierController, DomaineController,
    TimeSheetController, AgendaController, TaskController, FactureController, ProfileController
};

use App\Http\Controllers\EmailController;
use App\Http\Controllers\EmailWebController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\DesktopDatabaseController;
use App\Http\Controllers\ExplorerController;
use App\Http\Controllers\OnlyOfficeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::middleware(['web','auth','active'])
    ->group(function () {
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::resource('users', UserController::class);
Route::put('/users/{user}/general', [UserController::class, 'updateGeneralInfo'])
    ->name('users.update.general');
Route::put('/users/{user}/security', [UserController::class, 'updateSecurity'])
    ->name('users.update.security');
Route::put('/users/{user}/privileges', [UserController::class, 'updatePrivileges'])
    ->name('users.update.privileges');

Route::resource('intervenants', IntervenantController::class);
Route::get('intervenants/search', [IntervenantController::class, 'search']);
Route::post('intervenants/{intervenant}/attach-dossier', [IntervenantController::class, 'attachDossier']);
Route::post('intervenants/detach-intervenant', [IntervenantController::class, 'detachIntervenant'])->name('intervenants.detach-intervenant');
Route::delete('intervenant-files/{file}', [IntervenantController::class, 'destroyFile'])->name('intervenants.files.destroy');
Route::get('intervenant/download/{file}', [IntervenantController::class,'downloadFile']);
Route::get('intervenant/display/{file}', [IntervenantController::class,'displayFile']);
Route::get('/intervenants/{intervenant}/files', [IntervenantController::class, 'getFiles'])->name('intervenants.files');

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
Route::post('/dossier/view', [DossierController::class, 'viewFile'])->name('dossier.view');

// Gardez l'ancienne route GET pour la compatibilité si nécessaire
Route::get('/dossier/download/{dossierId}/{fileName}', [DossierController::class, 'downloadFile'])->name('dossier.download.get');
Route::post('/dossiers/{dossier}/delete', [DossierController::class, 'deleteFile'])->name('dossiers.delete');
Route::post('/dossiers/{dossier}/rename', [DossierController::class, 'renameFile'])->name('dossiers.rename');
Route::post('/dossiers/{dossier}/move', [DossierController::class, 'moveFile'])->name('dossiers.move');
Route::get('/dossiers/{dossier}/folders', [DossierController::class, 'getFoldersTree'])->name('dossiers.folders');
Route::post('/dossiers/{dossier}/create-folder', [DossierController::class, 'createFolder'])->name('dossiers.create-folder');
Route::post('/dossiers/{dossier}/file-url', [DossierController::class, 'getFileUrl'])->name('dossiers.file-url');
Route::post('/dossiers/{dossier}/upload-folder', [DossierController::class, 'uploadFolder'])->name('dossiers.upload-folder');
Route::resource('domaines', DomaineController::class);

Route::resource('time-sheets', TimeSheetController::class);
Route::get('dossiers/{dossierId}/time-sheets', [TimeSheetController::class, 'byDossier']);
Route::get('users/{userId}/time-sheets', [TimeSheetController::class, 'byUser']);
Route::get('time-sheets/report', [TimeSheetController::class, 'report']);
Route::get('/timesheets/data', [TimesheetController::class, 'getTimesheetsData'])->name('timesheets.data');
Route::get('/get/categories', [TimesheetController::class, 'getCategories']);
Route::get('/get/types', [TimesheetController::class, 'getTypes']);
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


Route::resource('tasks', TaskController::class);
Route::get('tasks/status/{statut}', [TaskController::class, 'byStatus']);
Route::get('users/{userId}/tasks', [TaskController::class, 'byUser']);
Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus']);
Route::get('/get/tasks/data', [TaskController::class, 'getTasksData'])->name('tasks.data');
Route::get('tasks/{taskId}/download', [TaskController::class, 'downloadFile'])->name('tasks.download');

Route::get('tasks/download/{id}', [TaskController::class,'downloadFile']);
Route::get('tasks/display/{id}', [TaskController::class,'displayFile']);

Route::resource('factures', FactureController::class);
 Route::get('/get/factures/data', [FactureController::class, 'getFacturesData'])->name('factures.data');
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
Route::get('factures/display/{id}', [FactureController::class,'displayFile']);



// Routes profil
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

// Email Web Routes
Route::prefix('email')->group(function () {
        Route::get('/', [EmailWebController::class, 'index'])->name('email.index');
        Route::get('/folder/{folder}', [EmailWebController::class, 'showFolder'])->name('email.folder');
        Route::get('/email/{folder}/{uid}', [EmailWebController::class, 'showEmail'])->name('email.show');
        Route::post('/send', [EmailWebController::class, 'sendEmail'])->name('email.send');
        Route::post('/email/mark-read', [EmailWebController::class, 'markAsRead'])->name('email.mark-read');
        Route::post('/email/move', [EmailWebController::class, 'moveEmail'])->name('email.move');
        Route::post('/email/delete', [EmailWebController::class, 'deleteEmail'])->name('email.delete');
        Route::post('/emails/delete-multiple', [EmailWebController::class, 'deleteMultipleEmails'])->name('email.delete.multiple');
        Route::get('/emails/trash', [EmailWebController::class, 'showTrashFolder'])->name('email.trash');
        Route::post('/emails/empty-trash', [EmailWebController::class, 'emptyTrash'])->name('email.empty.trash');
        Route::post('/reconnect', [EmailWebController::class, 'reconnect'])->name('email.reconnect');
        Route::get('/emails/sent', [EmailWebController::class, 'showSentFolder'])->name('email.sent');
        Route::get('/download-attachment', [EmailWebController::class, 'downloadAttachment'])->name('email.download.attachment');
        Route::post('/emails/attach-to-dossier', [EmailWebController::class, 'attachToDossier'])->name('email.attach-to-dossier');
        Route::post('/emails/detach-from-dossier', [EmailWebController::class, 'detachFromDossier'])->name('email.detach-from-dossier');
        Route::get('/dossiers/{dossier}/emails', [EmailWebController::class, 'showDossierEmails'])->name('dossier.emails');
    });

    // Backup Routes
    Route::get('/backups', [App\Http\Controllers\BackupController::class, 'index'])->name('backups.index');
    Route::post('/backups/create', [App\Http\Controllers\BackupController::class, 'createBackup'])->name('backups.create');
    Route::delete('/backups/delete/{filename}', [App\Http\Controllers\BackupController::class, 'deleteBackup'])->name('backups.delete');
    Route::get('/backups/download/{filename}', [App\Http\Controllers\BackupController::class, 'downloadBackup'])->name('backups.download');

    
Route::get('/open-folder', [ExplorerController::class, 'showForm'])->name('folder.form');
Route::post('/open-folder', [ExplorerController::class, 'openFolder'])->name('folder.open');
Route::post('/open-folder-network', [ExplorerController::class, 'openFolderNetwork'])->name('folder.open.network');

// Simple callback
Route::get('/onlyoffice/open/{file}', [OnlyOfficeController::class, 'open'])->name('onlyoffice.open');
Route::post('/onlyoffice/save', [OnlyOfficeController::class, 'save'])->name('onlyoffice.save');


    });


Route::middleware(['auth','web'])->group(function () {
    // Editor route - without file ID, uses path parameter
    Route::get('/onlyoffice/editor', [OnlyOfficeController::class, 'editor'])
        ->name('onlyoffice.editor');
    
    // File download route
    Route::get('/onlyoffice/download', [OnlyOfficeController::class, 'download'])
        ->name('onlyoffice.download');
    
    
    // File list route
    Route::get('/onlyoffice/files', [OnlyOfficeController::class, 'fileList'])
        ->name('onlyoffice.files');
    
    // Quick test routes for different file types
    Route::get('/onlyoffice/test/word', function () {
        return redirect()->route('onlyoffice.editor', ['path' => storage_path('app/public/sample.docx')]);
    });
    
    Route::get('/onlyoffice/test/excel', function () {
        return redirect()->route('onlyoffice.editor', ['path' => storage_path('app/public/sample.xlsx')]);
    });
});
// 1️⃣ Serve DOCX file with correct MIME type
Route::get('/file/{filename}', function ($filename) {
    $path = storage_path("app/public/intervenants/2/{$filename}");
    if (!file_exists($path)) {
        abort(404, "File not found");
    }

    return response()->file($path, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ]);
});

// 2️⃣ ONLYOFFICE editor page
Route::get('/editor', function () {
    $filename = '1759774533_example.docx'; // your file name in storage/app/public
    $document = [
        'fileType' => 'docx',
        'key' => uniqid(),
        'title' => $filename,
        'url' => url("file/{$filename}"), // route that serves the file
    ];

    $config = [
        'document' => [
            'fileType' => $document['fileType'],
            'key' => $document['key'],
            'title' => $document['title'],
            'url' => $document['url'],
        ],
        'editorConfig' => [
            'callbackUrl' => url('/onlyoffice/callback'),
            'mode' => 'edit',
            'coEditing' => [
                'mode' => 'fast',
                'change' => true
            ],
        ],
    ];

    return view('onlyoffice', compact('config'));
});

// 3️⃣ ONLYOFFICE callback route (save edits)
Route::post('/onlyoffice/callback', function (Request $request) {
    $data = $request->all();
    Log::info('ONLYOFFICE callback', $data);

    // status = 2 means document is ready to be saved
    if (($data['status'] ?? null) == 2) {
        $fileUrl = $data['url'] ?? null;
        if ($fileUrl) {
            $contents = file_get_contents($fileUrl);
            Storage::disk('public/intervenants/2')->put('1759774533_example.docx', $contents);
        }
    }

    return response()->json(['error' => 0]);
});


// Desktop API routes
Route::prefix('api/desktop')->group(function () {
    Route::get('/test-db', [DesktopDatabaseController::class, 'testConnection']);
    Route::get('/db-stats', [DesktopDatabaseController::class, 'getStats']);
    Route::post('/backup-database', [DesktopDatabaseController::class, 'backupDatabase']);
    
    Route::get('/info', function () {
        return response()->json([
            'version' => config('app.version', '1.0.0'),
            'environment' => app()->environment(),
            'is_desktop' => is_desktop(),
            'database' => [
                'connection' => config('database.default'),
                'name' => config('database.connections.mysql.database'),
                'host' => config('database.connections.mysql.host')
            ]
        ]);
    });
});
Route::get('/debug-uids', function() {
    $emailService = app()->make(App\Services\EmailManagerService::class);
    $result = $emailService->debugUids('INBOX', 20);
    return response()->json($result);
});


Route::get('/test-jwt', function () {
    $service = app(App\Services\OnlyOfficeService::class);
    return response()->json($service->testJwt());
});


    // Callback route - Make sure this is defined as POST
    Route::post('/onlyoffice/callback', [OnlyOfficeController::class, 'callback'])
        ->name('onlyoffice.callback');

        // routes/web.php

// TEMPORARY: Completely public test callback
Route::get('/onlyoffice-callback-test', function (Request $request) {
    \Log::info('Public callback test received', $request->all());
    return response()->json(['error' => 0, 'message' => 'Public callback working']);
});



// Simple test route
Route::get('/test', function () {
    return response()->json(['status' => 'OK', 'message' => 'Laravel is working']);
});

// Simple file serve
Route::get('/file/{filename}', function ($filename) {
    $path = storage_path('app/public/' . $filename);
    
    if (!file_exists($path)) {
        // Create a simple file
        file_put_contents($path, "Test document content");
    }
    
    return response()->file($path, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'Access-Control-Allow-Origin' => '*'
    ]);
});


// Health check
Route::get('/health', function () {
    return response()->json(['status' => 'healthy']);
});