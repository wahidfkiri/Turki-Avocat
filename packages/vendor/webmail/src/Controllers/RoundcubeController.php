<?php

namespace Vendor\Webmail\Controllers;

use App\Http\Controllers\Controller;
use Vendor\Webmail\Services\RoundcubeWindowsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RoundcubeController extends Controller
{
    protected $roundcubeService;
    private $logFile = 'roundcube/roundcube_logs.txt';
    
    public function __construct(RoundcubeWindowsService $roundcubeService)
    {
        $this->roundcubeService = $roundcubeService;
        $this->setupLogging();
    }
    
    /**
     * Setup logging directory and file
     */
    private function setupLogging()
    {
        $logPath = storage_path('logs/roundcube');
        
        if (!is_dir($logPath)) {
            mkdir($logPath, 0755, true);
        }
        
        $this->logFile = $logPath . '/roundcube_' . date('Y-m-d') . '.log';
    }
    
    /**
     * Write log message
     */
    private function writeLog($message, $type = 'INFO', $data = [])
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$type] $message";
        
        if (!empty($data)) {
            $logMessage .= " | Data: " . json_encode($data);
        }
        
        $logMessage .= PHP_EOL;
        
        // Write to Laravel log
        Log::channel('roundcube')->info($message, $data);
        
        // Write to custom log file
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        
        // Also write to system log
        error_log("Roundcube: $message");
    }
    
    /**
     * Redirection vers Roundcube avec auto-login (PRINCIPAL)
     */
    public function redirectToWebmail(Request $request)
    {
        $this->writeLog('=== DEBUT REDIRECTION ROUNDCUBE ===', 'DEBUG', [
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        $user = Auth::user();
        
        if (!$user) {
            $this->writeLog('Utilisateur non authentifié', 'ERROR');
            return redirect()->route('login')->with('error', 'Veuillez vous connecter d\'abord.');
        }
        
        $this->writeLog('Utilisateur Laravel authentifié', 'INFO', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
        
        // Récupérer les identifiants email
        $emailCredentials = $this->getUserEmailCredentials($user->id);
        
        if (!$emailCredentials) {
            $this->writeLog('Identifiants email non trouvés', 'ERROR', [
                'user_id' => $user->id,
            ]);
            return back()->with('error', 'Identifiants email non configurés.');
        }
        
        $this->writeLog('Identifiants email récupérés', 'INFO', [
            'email' => $emailCredentials['email'],
            'has_password' => !empty($emailCredentials['password']),
        ]);
        
        // Test de connexion IMAP d'abord
        $this->writeLog('Test de connexion IMAP...', 'DEBUG');
        $imapTest = $this->testImapConnection(
            $emailCredentials['email'],
            $emailCredentials['password']
        );
        
        if (!$imapTest['success']) {
            $this->writeLog('Échec test IMAP', 'ERROR', $imapTest);
            return back()->with('error', 'Connexion IMAP impossible: ' . $imapTest['error']);
        }
        
        $this->writeLog('Test IMAP réussi', 'INFO', $imapTest);
        
        // Générer la connexion automatique
        $this->writeLog('Génération de l\'auto-login Roundcube...', 'DEBUG');
        $startTime = microtime(true);
        
        $result = $this->roundcubeService->autoLoginToRoundcube(
            $emailCredentials['email'],
            $emailCredentials['password']
        );
        
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        
        $this->writeLog('Résultat auto-login', 'INFO', [
            'success' => $result['success'],
            'execution_time_ms' => $executionTime,
            'session_id' => $result['session_id'] ?? null,
        ]);
        
        if (!$result['success']) {
            $this->writeLog('Échec auto-login', 'ERROR', $result);
            return back()->with('error', $result['error'] ?? 'Connexion Roundcube impossible.');
        }
        
        // Journaliser l'accès réussi
        $this->writeLog('Auto-login réussi, redirection...', 'SUCCESS', [
            'user_id' => $user->id,
            'email' => $emailCredentials['email'],
            'session_id' => $result['session_id'],
            'login_url_length' => strlen($result['login_url']),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        // Ajouter un log visuel pour debug
        $this->createDebugFile($result, $emailCredentials, $user);
        
        // Redirection vers Roundcube
        return redirect()->away($result['login_url']);
    }
    
    /**
     * Page de test de connexion complète
     */
    public function testConnection(Request $request)
    {
        $this->writeLog('=== TEST DE CONNEXION COMPLET ===', 'DEBUG');
        
        $tests = [];
        
        // Test 1: Base de données Roundcube
        $tests['database'] = $this->testDatabaseConnection();
        
        // Test 2: Fichier de configuration
        $tests['config_file'] = $this->testConfigFile();
        
        // Test 3: Service Roundcube
        $tests['service'] = $this->roundcubeService->testConnection();
        
        // Test 4: IMAP
        $tests['imap'] = $this->testImapConnection('contact@turkiavocats.com', '6NsNs23Nu');
        
        // Test 5: Sessions
        $tests['sessions'] = $this->testSessionTable();
        
        // Test 6: Laravel User
        $tests['laravel_user'] = $this->testLaravelUser();
        
        // Test 7: URL génération
        $tests['url_generation'] = $this->testUrlGeneration();
        
        $this->writeLog('Tests complets terminés', 'INFO', $tests);
        
        return view('webmail::index', [
            'tests' => $tests,
            'logs' => $this->getRecentLogs(),
            'request' => $request->all(),
        ]);
    }
    
    /**
     * Test de connexion IMAP
     */
    private function testImapConnection($email, $password)
    {
        $this->writeLog("Test IMAP pour: $email", 'DEBUG');
        
        try {
            if (!function_exists('imap_open')) {
                return [
                    'success' => false,
                    'error' => 'Extension IMAP non activée',
                    'action' => 'Activer extension=imap dans php.ini',
                ];
            }
            
            $host = '{mailbox.nextstep-it.com:993/imap/ssl}';
            $startTime = microtime(true);
            
            $this->writeLog("Connexion à: $host", 'DEBUG');
            
            // Essayer avec timeout court
            $mailbox = @imap_open($host, $email, $password, OP_READONLY, 1, [
                'DISABLE_AUTHENTICATOR' => 'GSSAPI'
            ]);
            
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            
            if ($mailbox === false) {
                $error = imap_last_error();
                $this->writeLog("Échec IMAP", 'ERROR', ['error' => $error]);
                
                return [
                    'success' => false,
                    'error' => $error,
                    'response_time_ms' => $responseTime,
                    'host' => $host,
                ];
            }
            
            // Récupérer des infos sur la boîte mail
            $mailboxInfo = @imap_check($mailbox);
            $mailboxStats = $mailboxInfo ? [
                'messages' => $mailboxInfo->Nmsgs,
                'recent' => $mailboxInfo->Recent,
                'unread' => $mailboxInfo->Unread,
            ] : [];
            
            imap_close($mailbox);
            
            $this->writeLog("Connexion IMAP réussie", 'SUCCESS', [
                'response_time_ms' => $responseTime,
                'mailbox_info' => $mailboxStats,
            ]);
            
            return [
                'success' => true,
                'response_time_ms' => $responseTime,
                'mailbox_info' => $mailboxStats,
                'host' => $host,
            ];
            
        } catch (\Exception $e) {
            $this->writeLog("Exception IMAP", 'ERROR', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'type' => 'exception',
            ];
        }
    }
    
    /**
     * Test de la base de données Roundcube
     */
    private function testDatabaseConnection()
    {
        $this->writeLog('Test connexion DB Roundcube...', 'DEBUG');
        
        try {
            $connection = config('database.connections.roundcube');
            
            if (!$connection) {
                return [
                    'success' => false,
                    'error' => 'Configuration DB roundcube manquante',
                ];
            }
            
            $this->writeLog('Configuration DB trouvée', 'INFO', [
                'host' => $connection['host'],
                'database' => $connection['database'],
            ]);
            
            // Tester la connexion
            DB::connection('roundcube')->getPdo();
            
            // Vérifier les tables
            $tables = DB::connection('roundcube')
                ->select("SHOW TABLES LIKE '%session%'");
            
            $hasSessionTable = count($tables) > 0;
            
            $usersTable = DB::connection('roundcube')
                ->select("SHOW TABLES LIKE '%users%'");
            
            $hasUsersTable = count($usersTable) > 0;
            
            // Compter les sessions existantes
            $sessionCount = 0;
            if ($hasSessionTable) {
                $sessionCount = DB::connection('roundcube')
                    ->table('session')
                    ->count();
            }
            
            $this->writeLog('Connexion DB réussie', 'SUCCESS', [
                'has_session_table' => $hasSessionTable,
                'has_users_table' => $hasUsersTable,
                'session_count' => $sessionCount,
            ]);
            
            return [
                'success' => true,
                'has_session_table' => $hasSessionTable,
                'has_users_table' => $hasUsersTable,
                'session_count' => $sessionCount,
                'connection' => [
                    'host' => $connection['host'],
                    'database' => $connection['database'],
                    'username' => $connection['username'],
                ],
            ];
            
        } catch (\Exception $e) {
            $this->writeLog('Échec connexion DB', 'ERROR', [
                'message' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'config' => config('database.connections.roundcube'),
            ];
        }
    }
    
    /**
     * Test du fichier de configuration
     */
    private function testConfigFile()
    {
        $configPath = 'C:/xampp/htdocs/roundcube/config/config.inc.php';
        
        $this->writeLog("Vérification fichier: $configPath", 'DEBUG');
        
        if (!file_exists($configPath)) {
            return [
                'success' => false,
                'error' => 'Fichier config.inc.php introuvable',
                'path' => $configPath,
            ];
        }
        
        $content = file_get_contents($configPath);
        $fileSize = filesize($configPath);
        $permissions = substr(sprintf('%o', fileperms($configPath)), -4);
        
        // Vérifier les paramètres importants
        $hasDesKey = strpos($content, "des_key") !== false;
        $hasExternalAuth = strpos($content, "enable_external_auth") !== false;
        $hasSessionStorage = strpos($content, "session_storage") !== false;
        
        // Extraire la clé DES
        $desKey = 'NON TROUVÉE';
        if (preg_match("/\\\$config\\['des_key'\\]\\s*=\\s*'(.+?)';/", $content, $matches)) {
            $desKey = $matches[1];
            $keyLength = strlen($desKey);
            $desKey = substr($desKey, 0, 10) . '...' . substr($desKey, -10);
        }
        
        $this->writeLog('Analyse fichier config', 'INFO', [
            'size' => $fileSize,
            'permissions' => $permissions,
            'has_des_key' => $hasDesKey,
            'has_external_auth' => $hasExternalAuth,
            'key_length' => $keyLength ?? 0,
        ]);
        
        return [
            'success' => true,
            'path' => $configPath,
            'size' => $fileSize,
            'permissions' => $permissions,
            'has_des_key' => $hasDesKey,
            'has_external_auth' => $hasExternalAuth,
            'has_session_storage' => $hasSessionStorage,
            'des_key_preview' => $desKey,
            'key_length' => $keyLength ?? 0,
        ];
    }
    
    /**
     * Test de la table session
     */
    private function testSessionTable()
    {
        try {
            $tableExists = DB::connection('roundcube')
                ->select("SHOW TABLES LIKE 'session'");
            
            if (empty($tableExists)) {
                return [
                    'success' => false,
                    'error' => 'Table session n\'existe pas',
                ];
            }
            
            // Structure de la table
            $structure = DB::connection('roundcube')
                ->select("DESCRIBE session");
            
            // Dernières sessions
            $recentSessions = DB::connection('roundcube')
                ->table('session')
                ->orderBy('changed', 'desc')
                ->limit(5)
                ->get()
                ->map(function($session) {
                    return [
                        'id' => substr($session->sess_id, 0, 20) . '...',
                        'created' => $session->created,
                        'changed' => $session->changed,
                        'ip' => $session->ip,
                    ];
                });
            
            // Nombre total de sessions
            $totalSessions = DB::connection('roundcube')
                ->table('session')
                ->count();
            
            return [
                'success' => true,
                'table_exists' => true,
                'total_sessions' => $totalSessions,
                'columns' => count($structure),
                'recent_sessions' => $recentSessions,
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Test de l'utilisateur Laravel
     */
    private function testLaravelUser()
    {
        $user = Auth::user();
        
        if (!$user) {
            return [
                'success' => false,
                'error' => 'Aucun utilisateur authentifié',
            ];
        }
        
        $emailCredentials = $this->getUserEmailCredentials($user->id);
        
        return [
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'email_credentials' => [
                'has_credentials' => !empty($emailCredentials),
                'email' => $emailCredentials['email'] ?? null,
                'has_password' => !empty($emailCredentials['password'] ?? ''),
            ],
        ];
    }
    
    /**
     * Test de génération d'URL
     */
    private function testUrlGeneration()
    {
        try {
            $testResult = $this->roundcubeService->autoLoginToRoundcube(
                'test@nextstep-it.com',
                'testpassword'
            );
            
            return [
                'success' => $testResult['success'],
                'session_id' => $testResult['session_id'] ?? null,
                'login_url' => $testResult['login_url'] ?? null,
                'error' => $testResult['error'] ?? null,
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Callback pour vérification
     */
    public function callback(Request $request)
    {
        $this->writeLog('=== CALLBACK ROUNDCUBE ===', 'DEBUG', $request->all());
        
        $validated = $request->validate([
            'session_id' => 'required|string',
            'token' => 'required|string',
            'email' => 'required|email',
            'timestamp' => 'required|integer',
            'checksum' => 'required|string',
        ]);
        
        $this->writeLog('Validation callback', 'INFO', [
            'session_id' => substr($validated['session_id'], 0, 20) . '...',
            'email' => $validated['email'],
            'timestamp' => $validated['timestamp'],
        ]);
        
        // Vérifier le timestamp (max 5 minutes)
        $timeDiff = time() - $validated['timestamp'];
        
        if ($timeDiff > 300) {
            $this->writeLog('Timestamp expiré', 'ERROR', [
                'time_diff' => $timeDiff,
                'max_allowed' => 300,
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Session expirée',
                'time_diff' => $timeDiff,
            ], 403);
        }
        
        // Vérifier la session
        $isValid = $this->roundcubeService->verifyRoundcubeSession(
            $validated['session_id']
        );
        
        if (!$isValid) {
            $this->writeLog('Session invalide', 'ERROR');
            return response()->json([
                'success' => false,
                'error' => 'Session invalide',
            ], 403);
        }
        
        $this->writeLog('Callback réussi', 'SUCCESS');
        
        return response()->json([
            'success' => true,
            'message' => 'Session Roundcube valide',
            'session_id' => $validated['session_id'],
            'timestamp' => time(),
        ]);
    }
    
    /**
     * Afficher les logs
     */
    public function showLogs(Request $request)
    {
        $logs = $this->getRecentLogs(100);
        $logFiles = $this->getLogFiles();
        
        return view('webmail::logs', [
            'logs' => $logs,
            'log_files' => $logFiles,
            'current_file' => basename($this->logFile),
            'log_path' => dirname($this->logFile),
        ]);
    }
    
    /**
     * Effacer les logs
     */
    public function clearLogs(Request $request)
    {
        $days = $request->get('days', 7);
        $deleted = $this->clearOldLogs($days);
        
        return response()->json([
            'success' => true,
            'message' => "Logs effacés ($deleted fichiers)",
            'deleted_count' => $deleted,
        ]);
    }
    
    /**
     * Tester une connexion IMAP manuelle
     */
    public function testImapManual(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        $result = $this->testImapConnection(
            $request->email,
            $request->password
        );
        
        return response()->json($result);
    }
    
    /**
     * Générer une URL de test
     */
    public function generateTestUrl(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        $result = $this->roundcubeService->autoLoginToRoundcube(
            $request->email,
            $request->password
        );
        
        if (!$result['success']) {
            return response()->json($result, 400);
        }
        
        // Créer un fichier de debug
        $debugInfo = [
            'generated_at' => date('Y-m-d H:i:s'),
            'email' => $request->email,
            'session_id' => $result['session_id'],
            'login_url' => $result['login_url'],
            'request_ip' => $request->ip(),
        ];
        
        $debugFile = storage_path('logs/roundcube/debug_' . date('Ymd_His') . '.json');
        file_put_contents($debugFile, json_encode($debugInfo, JSON_PRETTY_PRINT));
        
        return response()->json([
            'success' => true,
            'login_url' => $result['login_url'],
            'session_id' => $result['session_id'],
            'debug_file' => $debugFile,
            'message' => 'Copiez cette URL dans votre navigateur pour tester',
        ]);
    }
    
    /**
     * Récupérer les logs récents
     */
    private function getRecentLogs($limit = 50)
    {
        if (!file_exists($this->logFile)) {
            return [];
        }
        
        $lines = file($this->logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if (!$lines) {
            return [];
        }
        
        $logs = array_slice($lines, -$limit);
        $formattedLogs = [];
        
        foreach ($logs as $log) {
            if (preg_match('/^\[(.*?)\] \[(.*?)\] (.*)$/', $log, $matches)) {
                $formattedLogs[] = [
                    'timestamp' => $matches[1],
                    'type' => $matches[2],
                    'message' => $matches[3],
                    'raw' => $log,
                ];
            }
        }
        
        return array_reverse($formattedLogs);
    }
    
    /**
     * Récupérer la liste des fichiers de log
     */
    private function getLogFiles()
    {
        $logPath = dirname($this->logFile);
        $files = [];
        
        if (is_dir($logPath)) {
            $scan = scandir($logPath);
            
            foreach ($scan as $file) {
                if ($file !== '.' && $file !== '..' && strpos($file, 'roundcube_') === 0) {
                    $filePath = $logPath . '/' . $file;
                    $files[] = [
                        'name' => $file,
                        'path' => $filePath,
                        'size' => filesize($filePath),
                        'modified' => date('Y-m-d H:i:s', filemtime($filePath)),
                    ];
                }
            }
        }
        
        // Trier par date décroissante
        usort($files, function($a, $b) {
            return strtotime($b['modified']) - strtotime($a['modified']);
        });
        
        return $files;
    }
    
    /**
     * Effacer les anciens logs
     */
    private function clearOldLogs($days = 7)
    {
        $logPath = dirname($this->logFile);
        $deleted = 0;
        
        if (is_dir($logPath)) {
            $files = scandir($logPath);
            $cutoff = time() - ($days * 24 * 60 * 60);
            
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = $logPath . '/' . $file;
                    
                    if (filemtime($filePath) < $cutoff) {
                        unlink($filePath);
                        $deleted++;
                    }
                }
            }
        }
        
        return $deleted;
    }
    
    /**
     * Créer un fichier de debug
     */
    private function createDebugFile($result, $credentials, $user)
    {
        $debugData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'laravel_user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'email_credentials' => [
                'email' => $credentials['email'],
                'password_length' => strlen($credentials['password']),
            ],
            'roundcube_result' => $result,
            'server' => [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'host' => request()->getHost(),
            ],
            'config_check' => [
                'des_key_set' => !empty(config('services.roundcube.secret_key')),
                'roundcube_url' => config('services.roundcube.url'),
                'db_connection' => config('database.connections.roundcube') ? 'OK' : 'Missing',
            ],
        ];
        
        $debugFile = storage_path('logs/roundcube/debug_' . date('Ymd_His') . '.json');
        file_put_contents($debugFile, json_encode($debugData, JSON_PRETTY_PRINT));
        
        $this->writeLog('Fichier debug créé', 'DEBUG', ['file' => $debugFile]);
    }
    
    /**
     * Récupérer les identifiants email de l'utilisateur
     */
    private function getUserEmailCredentials($userId)
    {
        // À ADAPTER selon votre structure
        // Exemple avec une table dédiée
        /*
        $emailConfig = \App\Models\UserEmail::where('user_id', $userId)->first();
        
        if ($emailConfig) {
            return [
                'email' => $emailConfig->email,
                'password' => Crypt::decryptString($emailConfig->password_encrypted),
            ];
        }
        */
        
        // Pour le test, utilisez l'email Laravel et un mot de passe de test
        // EN PRODUCTION: stockez le mot de passe email séparément et de manière sécurisée
        $user = Auth::user();
        
        return [
            'email' => $user->email,
            'password' => 'votre_mot_de_passe_email', // À REMPLACER
        ];
    }
}