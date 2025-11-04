<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\BackupDestination\BackupDestination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermission('export_data')) {
            abort(403, 'Unauthorized action.');
        }
        $backupFiles = [];
        
        try {
            $backupDestination = BackupDestination::create('local', config('backup.backup.name'));
            
            if ($backupDestination) {
                $backupFiles = $backupDestination->backups()
                    ->map(function (Backup $backup) {
                        return [
                            'filename' => $backup->path(),
                            // 'size' => $this->formatBytes($backup->size()),
                            'date' => $backup->date()->format('d/m/Y H:i:s'),
                            'age' => $backup->date()->diffForHumans(),
                        ];
                    })
                    ->sortByDesc('date')
                    ->values()
                    ->toArray();
            }
        } catch (\Exception $e) {
            // Gérer l'erreur silencieusement
        }
        
        $databaseSize = $this->getDatabaseSize();
        
        return view('backups.index', compact('backupFiles', 'databaseSize'));
    }

    public function createBackup(Request $request)
    {
        // try {
            \Artisan::call('backup:run --only-db');
            
            return response()->json([
                'success' => true,
                'message' => 'Sauvegarde de la base de données créée avec succès!'
            ]);
        // } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la sauvegarde: ' . $e->getMessage()
            ], 500);
        // }
    }

    public function deleteBackup($filename)
    {
        try {
            $backupDestination = BackupDestination::create('local', config('backup.backup.name'));
            
            if ($backupDestination) {
                $backup = $backupDestination->backups()->first(function (Backup $backup) use ($filename) {
                    return $backup->path() === $filename;
                });
                
                if ($backup) {
                    $backupDestination->deleteBackup($backup);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Sauvegarde supprimée avec succès!'
                    ]);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Sauvegarde non trouvée'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadBackup($filename)
    {
        try {
            $backupDestination = BackupDestination::create('local', config('backup.backup.name'));
            
            if ($backupDestination) {
                $backup = $backupDestination->backups()->first(function (Backup $backup) use ($filename) {
                    return $backup->path() === $filename;
                });
                
                if ($backup) {
                    $storagePath = Storage::disk('local')->path($filename);
                    
                    return response()->download($storagePath);
                }
            }
            
            return redirect()->back()->with('error', 'Sauvegarde non trouvée');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du téléchargement: ' . $e->getMessage());
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    private function getDatabaseSize()
    {
        try {
            // Pour MySQL
            $result = \DB::select('SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb 
                FROM information_schema.tables 
                WHERE table_schema = ?', [config('database.connections.mysql.database')]);
            
            if (!empty($result)) {
                return $this->formatBytes($result[0]->size_mb * 1024 * 1024);
            }
        } catch (\Exception $e) {
            // En cas d'erreur, retourner une valeur par défaut
        }
        
        return 'N/A';
    }
}