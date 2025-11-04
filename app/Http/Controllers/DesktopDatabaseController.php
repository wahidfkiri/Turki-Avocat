<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class DesktopDatabaseController extends Controller
{
    public function testConnection()
    {
        try {
            DB::connection()->getPdo();
            
            return response()->json([
                'connected' => true,
                'database' => config('database.connections.mysql.database'),
                'version' => DB::connection()->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'connected' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getStats()
    {
        if (!is_desktop()) {
            return response()->json(['error' => 'Not available in web mode'], 403);
        }

        try {
            $tables = DB::select('SHOW TABLES');
            $tableCount = count($tables);
            
            $userCount = DB::table('users')->count();
            $migrations = DB::table('migrations')->count();

            return response()->json([
                'tables' => $tableCount,
                'users' => $userCount,
                'migrations' => $migrations,
                'database_size' => $this->getDatabaseSize()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function backupDatabase(Request $request)
    {
        if (!is_desktop()) {
            return response()->json(['error' => 'Not available in web mode'], 403);
        }

        try {
            $backupPath = $request->input('filepath', storage_path('app/backups/backup-' . date('Y-m-d-H-i-s') . '.sql'));
            
            // Ensure directory exists
            $dir = dirname($backupPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // Simple backup - in production, use spatie/laravel-backup or similar
            $tables = DB::select('SHOW TABLES');
            $output = "";
            
            foreach ($tables as $table) {
                $tableName = $table->{'Tables_in_' . config('database.connections.mysql.database')};
                
                // Drop table
                $output .= "DROP TABLE IF EXISTS `$tableName`;\n";
                
                // Create table
                $createTable = DB::select("SHOW CREATE TABLE `$tableName`")[0];
                $output .= $createTable->{'Create Table'} . ";\n\n";
                
                // Table data
                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $values = implode("', '", array_map(function($value) {
                        return addslashes($value);
                    }, (array)$row));
                    
                    $output .= "INSERT INTO `$tableName` VALUES ('$values');\n";
                }
                $output .= "\n";
            }
            
            file_put_contents($backupPath, $output);
            
            return response()->json([
                'success' => true,
                'filepath' => $backupPath,
                'size' => filesize($backupPath)
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function getDatabaseSize()
    {
        $databaseName = config('database.connections.mysql.database');
        $size = DB::select("
            SELECT SUM(data_length + index_length) as size
            FROM information_schema.TABLES 
            WHERE table_schema = ?", [$databaseName])[0]->size;
            
        return round($size / 1024 / 1024, 2); // Size in MB
    }
}