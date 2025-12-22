<?php

namespace Vendor\Webmail\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RoundcubeWindowsService
{
    private $config;
    
    public function __construct()
    {
        $this->config = [
            // Chemins Windows XAMPP
            'roundcube_path' => 'C:/xampp/htdocs/dashboard',
            'roundcube_url' => config('services.roundcube.url', 'http://localhost:8082'),
            
            // Base de données Roundcube
            'db_connection' => 'roundcube',
            
            // Clé secrète (DOIT correspondre à des_key dans config.inc.php)
            'secret_key' => config('services.roundcube.secret_key', 'M3d#kF9@zT2qY8!pL5sX7vR$wN1cB4jG'),
            
            // Configuration IMAP
            'imap_host' => config('services.roundcube.imap_host', 'localhost'),
            'imap_port' => config('services.roundcube.imap_port', 143),
            
            // Timeout
            'timeout' => 30,
        ];
    }
    
    /**
     * Connexion automatique à Roundcube
     */
    public function autoLoginToRoundcube($email, $password)
    {
        try {
            // 1. Vérifier les identifiants IMAP
            if (!$this->validateImapCredentials($email, $password)) {
                throw new \Exception('Identifiants IMAP invalides');
            }
            
            // 2. Créer l'utilisateur dans Roundcube si nécessaire
            $userId = $this->getOrCreateRoundcubeUser($email);
            
            // 3. Créer la session Roundcube
            $sessionId = $this->createRoundcubeSession($userId, $email);
            
            // 4. Générer l'URL de connexion automatique
            $loginUrl = $this->generateAutoLoginUrl($sessionId, $email);
            
            // 5. Stocker en session Laravel pour vérification
            Session::put('roundcube_auth_data', [
                'session_id' => $sessionId,
                'email' => Crypt::encryptString($email),
                'timestamp' => time(),
                'ip' => request()->ip(),
            ]);
            
            return [
                'success' => true,
                'login_url' => $loginUrl,
                'session_id' => $sessionId,
            ];
            
        } catch (\Exception $e) {
            Log::error('Roundcube auto-login error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Valider les identifiants IMAP
     */
    private function validateImapCredentials($email, $password)
    {
        // Pour Windows, utiliser PHP IMAP
        if (!function_exists('imap_open')) {
            throw new \Exception('Extension IMAP non activée dans PHP');
        }
        
        $host = "{{$this->config['imap_host']}:{$this->config['imap_port']}/imap}";
        
        try {
            // Tester la connexion IMAP
            $mailbox = @imap_open($host, $email, $password, OP_READONLY, 1);
            
            if ($mailbox === false) {
                $error = imap_last_error();
                Log::warning('IMAP connection failed: ' . $error);
                return false;
            }
            
            imap_close($mailbox);
            return true;
            
        } catch (\Exception $e) {
            Log::error('IMAP validation exception: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Créer ou récupérer l'utilisateur Roundcube
     */
    private function getOrCreateRoundcubeUser($email)
    {
        try {
            // Vérifier si l'utilisateur existe
            $user = DB::connection($this->config['db_connection'])
                ->table('users')
                ->where('username', $email)
                ->first();
            
            if ($user) {
                return $user->user_id;
            }
            
            // Créer un nouvel utilisateur
            $userId = DB::connection($this->config['db_connection'])
                ->table('users')
                ->insertGetId([
                    'username' => $email,
                    'mail_host' => $this->config['imap_host'],
                    'created' => now()->toDateTimeString(),
                    'language' => 'fr_FR',
                    'preferences' => 'a:1:{s:9:"timezone";s:13:"Europe/Paris";}',
                ]);
            
            // Créer l'identité par défaut
            DB::connection($this->config['db_connection'])
                ->table('identities')
                ->insert([
                    'user_id' => $userId,
                    'changed' => now()->toDateTimeString(),
                    'del' => 0,
                    'standard' => 1,
                    'name' => $email,
                    'email' => $email,
                    'email_other' => '',
                ]);
            
            return $userId;
            
        } catch (\Exception $e) {
            throw new \Exception('Erreur création utilisateur Roundcube: ' . $e->getMessage());
        }
    }
    
    /**
     * Créer une session Roundcube
     */
    private function createRoundcubeSession($userId, $email)
    {
        // Générer un ID de session sécurisé
        $sessionId = hash('sha256', 
            uniqid(microtime(), true) . 
            $this->config['secret_key'] . 
            $email . 
            time()
        );
        
        // Données de session Roundcube
        $sessionData = [
            'user_id' => $userId,
            'username' => $email,
            'language' => 'fr_FR',
            'auth' => true,
            'ip' => request()->ip(),
            'time' => time(),
            'timestamp' => microtime(true),
        ];
        
        // Insérer dans la table session de Roundcube
        DB::connection($this->config['db_connection'])
            ->table('session')
            ->insert([
                'sess_id' => $sessionId,
                'created' => now()->toDateTimeString(),
                'changed' => now()->toDateTimeString(),
                'ip' => request()->ip(),
                'vars' => serialize($sessionData),
            ]);
        
        return $sessionId;
    }
    
    /**
     * Générer l'URL de connexion automatique
     */
    private function generateAutoLoginUrl($sessionId, $email)
    {
        $baseUrl = rtrim($this->config['roundcube_url'], '/');
        
        // Générer un token sécurisé
        $token = $this->generateAuthToken($sessionId, $email);
        
        // Paramètres pour l'auto-login
        $params = [
            '_task' => 'login',
            '_action' => 'login',
            '_autologin' => 1,
            '_token' => $token,
            '_session' => $sessionId,
            '_user' => base64_encode($email),
            '_time' => time(),
            '_auth' => $this->generateSignature($sessionId, $email),
        ];
        
        return $baseUrl . '/?' . http_build_query($params);
    }
    
    /**
     * Générer un token d'authentification
     */
    private function generateAuthToken($sessionId, $email)
    {
        $data = $sessionId . '|' . $email . '|' . time() . '|' . $this->config['secret_key'];
        return hash('sha256', $data);
    }
    
    /**
     * Générer une signature de sécurité
     */
    private function generateSignature($sessionId, $email)
    {
        $string = $sessionId . $email . $this->config['secret_key'] . request()->ip();
        return hash('sha512', $string);
    }
    
    /**
     * Vérifier une session Roundcube
     */
    public function verifyRoundcubeSession($sessionId)
    {
        try {
            $session = DB::connection($this->config['db_connection'])
                ->table('session')
                ->where('sess_id', $sessionId)
                ->first();
            
            if (!$session) {
                return false;
            }
            
            $vars = unserialize($session->vars);
            return isset($vars['auth']) && $vars['auth'] === true;
            
        } catch (\Exception $e) {
            Log::error('Session verification error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Nettoyer les sessions expirées
     */
    public function cleanupExpiredSessions()
    {
        $expiredTime = now()->subHours(2)->toDateTimeString();
        
        DB::connection($this->config['db_connection'])
            ->table('session')
            ->where('changed', '<', $expiredTime)
            ->delete();
            
        Log::info('Roundcube sessions cleaned up');
    }
    
    /**
     * Tester la connexion à Roundcube
     */
    public function testConnection()
    {
        try {
            // Tester la base de données
            DB::connection($this->config['db_connection'])->getPdo();
            
            // Tester le fichier de config
            $configFile = $this->config['roundcube_path'] . '/config/config.inc.php';
            if (!file_exists($configFile)) {
                throw new \Exception('Fichier config.inc.php introuvable');
            }
            
            return [
                'success' => true,
                'database' => 'OK',
                'config_file' => 'OK',
                'roundcube_path' => $this->config['roundcube_path'],
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}