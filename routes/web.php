<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{
    UserController, IntervenantController, DossierController,
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
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DomaineController;

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


// Notification routes
Route::prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/{notificationId}', [NotificationController::class, 'show'])->name('notification.show');
    Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/{notification}/unread', [NotificationController::class, 'markAsUnread'])->name('notifications.markAsUnread');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/mark-multiple-read', [NotificationController::class, 'markMultipleRead'])->name('notifications.markMultipleRead');
    Route::post('/delete-multiple', [NotificationController::class, 'deleteMultiple'])->name('notifications.deleteMultiple');
    Route::post('/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('notifications.deleteAllRead');
});



    // Routes pour domaines
    Route::post('/domaines', [DomaineController::class, 'store'])->name('domaines.store');
    Route::post('/sous-domaines', [DomaineController::class, 'storeSubdomaine'])->name('sous-domaines.store');
    Route::get('/sous-domaines/by-domaine', [DomaineController::class, 'getByDomaine'])->name('sous-domaines.by-domaine');


// Routes profil
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

// Email Web Routes
Route::prefix('email')->group(function () {
        Route::get('/', [EmailWebController::class, 'index'])->name('email.index');
        Route::get('/create/folder', [EmailWebController::class, 'createImapFolderSafe'])->name('email.createFolderForm');
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


Route::post('/onlyoffice/save', [OnlyOfficeController::class, 'save'])->name('onlyoffice.save');
Route::post('/dossier/create-file-backend', [DossierController::class, 'createFileBackend'])->name('dossier.create.file.backend');
Route::post('/intervenant/create-file-backend', [IntervenantController::class, 'createFileBackend'])->name('intervenant.create.file.backend');