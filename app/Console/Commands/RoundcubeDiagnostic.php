<?php

namespace App\Console\Commands;

use App\Services\RoundcubeWindowsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RoundcubeDiagnostic extends Command
{
    protected $signature = 'roundcube:diagnostic';
    protected $description = 'Diagnostic complet Roundcube';
    
    public function handle(RoundcubeWindowsService $service)
    {
        $this->info('🔍 Diagnostic Roundcube - NextStep IT');
        $this->line(str_repeat('=', 50));
        
        // 1. Test service
        $this->info('1. Test du service Roundcube...');
        $test = $service->testConnection();
        $this->table(
            ['Test', 'Résultat'],
            [
                ['Base de données', $test['database'] ?? 'N/A'],
                ['Fichier config', $test['config_file'] ?? 'N/A'],
                ['Chemin Roundcube', $test['roundcube_path'] ?? 'N/A'],
            ]
        );
        
        // 2. Sessions
        $this->info('2. Sessions Roundcube...');
        try {
            $sessions = DB::connection('roundcube')
                ->table('session')
                ->count();
            $this->info("   Sessions actives: $sessions");
        } catch (\Exception $e) {
            $this->error("   Erreur: " . $e->getMessage());
        }
        
        // 3. Configuration Laravel
        $this->info('3. Configuration Laravel...');
        $config = [
            ['ROUNDCUBE_URL', config('services.roundcube.url')],
            ['ROUNDCUBE_SECRET_KEY', substr(config('services.roundcube.secret_key'), 0, 20) . '...'],
            ['ROUNDCUBE_DB_CONNECTION', config('database.connections.roundcube.database')],
        ];
        $this->table(['Variable', 'Valeur'], $config);
        
        // 4. Logs
        $this->info('4. Fichiers de log...');
        $logPath = storage_path('logs/roundcube');
        if (is_dir($logPath)) {
            $files = scandir($logPath);
            $logFiles = array_filter($files, function($file) {
                return $file !== '.' && $file !== '..';
            });
            
            $this->info("   Fichiers trouvés: " . count($logFiles));
            
            foreach ($logFiles as $file) {
                $size = filesize($logPath . '/' . $file);
                $this->line("   - $file (" . round($size/1024, 2) . " KB)");
            }
        } else {
            $this->error("   Dossier logs non trouvé: $logPath");
        }
        
        // 5. Recommendations
        $this->info('5. Recommendations:');
        $this->line("   - Vérifiez que la clé DES correspond dans Laravel et Roundcube");
        $this->line("   - Testez avec: php artisan webmail:test");
        $this->line("   - Consultez les logs: " . $logPath);
        
        $this->line(str_repeat('=', 50));
        $this->info('✅ Diagnostic terminé');
    }
}