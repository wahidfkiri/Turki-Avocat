<?php
// app/Services/EmailManagerService.php

namespace App\Services;

use Webklex\PHPIMAP\ClientManager;
use Illuminate\Support\Facades\Mail;
use App\Mail\PeakMindMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\SentEmail;
use App\Models\EmailSetting;
use App\Services\EmailStorageService;

class EmailManagerService
{
    protected $client;
    protected $storageService;
    protected $connected = false;
    
    public function __construct()
    {
        $this->initializeClient();
        $this->storageService = new EmailStorageService();
    }
    
    protected function initializeClient()
    {
        try {
            $clientManager = new ClientManager($this->getConfig(\Auth::id()));
            $this->client = $clientManager->account('default');
            $this->client->connect();
            $this->connected = true;
            
            
        } catch (\Exception $e) {
            Log::error('Erreur connexion IMAP Turki Avocats: ' . $e->getMessage());
            $this->connected = false;
        }
    }

    // Vérifiez votre configuration
protected function getConfig()
{
    $config = \App\Models\EmailSetting::where('user_id', auth()->id())->first();
    return [
        'default' => 'default',
        'accounts' => [
            'default' => [
                'host' => env('IMAP_HOST', 'mailbox.nextstep-it.com'),
                'port' => env('IMAP_PORT', 993),
                'encryption' => env('IMAP_ENCRYPTION', 'ssl'),
                'validate_cert' => true,
                'username' => env('IMAP_USERNAME', $config->imap_username ?? ''),
                'password' => env('IMAP_PASSWORD', $config->imap_password ?? ''),
                'from_name' => $config->user->name ?? '',
                'protocol' => 'imap',
                'timeout' => 30,
            ],
        ],
        'options' => [
            'delimiter' => '/',
            'fetch' => \Webklex\IMAP\Support\MessageCollection::class,
            'fetch_order' => 'desc',
            'fetch_body' => true,
            'fetch_attachment' => true, 
            'fetch_flags' => true,
            'message_key' => 'list', // Éviter 'uid' qui cause des problèmes
            'uid_cache' => false, // Désactiver le cache UID
            'debug' => env('IMAP_DEBUG', false), // Activer le debug
        ],
    ];
}
 
    
    public function testConnection()
    {
        if (!$this->connected) {
            return [
                'connected' => false,
                'error' => 'Client IMAP non connecté',
                'account' => $this->imapConfig['username'] ?? ''
            ];
        }
        
        try {
            $folders = $this->client->getFolders();
            
            return [
                'connected' => true,
                'account' => $this->imapConfig['username'] ?? '',
                'folders_count' => count($folders),
                'status' => 'OK - Turki Avocats'
            ];
            
        } catch (\Exception $e) {
            return [
                'connected' => false,
                'error' => $e->getMessage(),
                'account' => $this->imapConfig['username'] ?? ''
            ];
        }
    }

    public static function userConfig()
    {
        $config = EmailSetting::with('user')->where('user_id', \Auth::id())->first();
        return [
            'host' => env('IMAP_HOST', 'mailbox.nextstep-it.com'),
            'port' => env('IMAP_PORT', 993),
            'encryption' => env('IMAP_ENCRYPTION', 'ssl'),
            'validate_cert' => true,
            'username' => env('IMAP_USERNAME', $config->imap_username ?? ''),
            'password' => env('IMAP_PASSWORD', $config->imap_password ?? ''),
            'from_name' => $config->user->name ?? '',
            'protocol' => 'imap',
            'timeout' => 30,
        ];
    }   
    
    public function getFolders()
    {
        if (!$this->connected) {
            throw new \Exception('Client IMAP Turki Avocats non connecté');
        }
        
        $folderList = [];
        
        try {
            $folders = $this->client->getFolders();
            
            foreach ($folders as $folder) {
                try {
                    $folderInfo = [
                        'name' => $folder->name,
                        'path' => $folder->path,
                        'full_name' => $folder->full_name,
                    ];
                    
                    $folderList[] = $folderInfo;
                    
                } catch (\Exception $e) {
                    $folderList[] = [
                        'name' => $folder->name,
                        'path' => 'error',
                        'full_name' => 'error',
                        'error' => $e->getMessage()
                    ];
                }
            }
            
            return $folderList;
            
        } catch (\Exception $e) {
            Log::error('Erreur récupération dossiers Turki Avocats: ' . $e->getMessage());
            throw new \Exception('Impossible de récupérer les dossiers Turki Avocats: ' . $e->getMessage());
        }
    }

    public function getEmailsFromStorage($folderName, $limit = 20)
    {
        $emails = $this->storageService->getEmailsFromStorage($folderName, $limit);
        return ['success' => true, 'emails' => $emails, 'source' => 'storage'];
    }
    
      public function getEmailsRobust($folderName = 'INBOX', $limit)
    {
        if (!$this->connected) {
            return ['success' => false, 'error' => 'Client IMAP non connecté'];
        }
        
        try {
            $folder = $this->client->getFolder($folderName);
            $messages = $folder->messages()
                ->limit($limit)
                ->all()
                ->get();
            
            $emails = [];
            foreach ($messages as $message) {
                try {
                    $parsedEmail = $this->parseEmailWithPreview($message);
                    $emails[] = $parsedEmail;
                    
                    // SAUVEGARDE AUTOMATIQUE DANS LE STORAGE
                    $this->storageService->saveEmail(strtolower($folderName), $parsedEmail);
                    
                } catch (\Exception $e) {
                    // Continuer avec les autres emails
                }
            }
            
            return ['success' => true, 'emails' => $emails];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function getEmailSimple($folderName, $uid)
    {
        if (!$this->connected) {
            return ['success' => false, 'error' => 'Client IMAP Turki Avocats non connecté'];
        }
        
        try {
            $folder = $this->client->getFolder($folderName);
            
            if (!$folder) {
                return ['success' => false, 'error' => "Dossier {$folderName} non trouvé"];
            }
            
            $messages = $folder->messages()
                ->limit(50)
                ->all()
                ->get();
            
            $targetMessage = null;
            foreach ($messages as $message) {
                if ($message->getUid() == $uid) {
                    $targetMessage = $message;
                    break;
                }
            }
            
            if (!$targetMessage) {
                $messages = $folder->messages()
                    ->limit(200)
                    ->all()
                    ->get();
                    
                foreach ($messages as $message) {
                    if ($message->getUid() == $uid) {
                        $targetMessage = $message;
                        break;
                    }
                }
            }
            
            if (!$targetMessage) {
                return ['success' => false, 'error' => 'Email UID ' . $uid . ' non trouvé dans les premiers 200 emails'];
            }
            
            return [
                'success' => true,
                'email' => $this->parseEmailWithBody($targetMessage)
            ];
            
        } catch (\Exception $e) {
            Log::error("Erreur récupération email simple {$uid}: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function findEmailByUidSequential($folderName, $uid, $batchSize = 50)
    {
        if (!$this->connected) {
            return ['success' => false, 'error' => 'Client IMAP Turki Avocats non connecté'];
        }
        
        try {
            $folder = $this->client->getFolder($folderName);
            
            if (!$folder) {
                return ['success' => false, 'error' => "Dossier {$folderName} non trouvé"];
            }
            
            $offset = 0;
            $found = false;
            $targetMessage = null;
            
            while (!$found) {
                $messages = $folder->messages()
                    ->limit($batchSize)
                    ->offset($offset)
                    ->all()
                    ->get();
                
                if ($messages->count() === 0) {
                    break;
                }
                
                foreach ($messages as $message) {
                    if ($message->getUid() == $uid) {
                        $targetMessage = $message;
                        $found = true;
                        break;
                    }
                }
                
                if ($found) {
                    break;
                }
                
                $offset += $batchSize;
                
                if ($offset >= 500) {
                    break;
                }
            }
            
            if (!$found) {
                return ['success' => false, 'error' => 'Email UID ' . $uid . ' non trouvé après recherche de ' . $offset . ' emails'];
            }
            
            return [
                'success' => true,
                'email' => $this->parseEmailWithBody($targetMessage)
            ];
            
        } catch (\Exception $e) {
            Log::error("Erreur recherche séquentielle email {$uid}: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    
    // In EmailManagerService.php
protected function parseEmailDate($dateHeader)
{
    if (!$dateHeader) {
        return date('Y-m-d H:i:s', time() - rand(3600, 86400)); // 1 hour to 1 day ago
    }

    try {
        $dateString = (string) $dateHeader;
        $timestamp = strtotime($dateString);
        
        if ($timestamp === false) {
            throw new \Exception("Invalid date format");
        }
        
        $currentTimestamp = time();
        
        // Fix future dates
        if ($timestamp > $currentTimestamp) {
            // If it's more than 1 year in future, assume it's a date parsing error
            if (($timestamp - $currentTimestamp) > 31536000) { // 1 year in seconds
                // Extract the time part and apply to current date
                $timePart = date('H:i:s', $timestamp);
                $timestamp = strtotime(date('Y-m-d') . ' ' . $timePart) - rand(86400, 2592000); // 1-30 days ago
            } else {
                // For near future dates, just make them recent past
                $timestamp = $currentTimestamp - rand(3600, 86400); // 1 hour to 1 day ago
            }
        }
        
        return date('Y-m-d H:i:s', $timestamp);

    } catch (\Exception $e) {
        Log::warning("Erreur parsing date email: " . $e->getMessage());
        return date('Y-m-d H:i:s', time() - rand(3600, 86400)); // Recent past
    }
}

    protected function parseEmailWithBody($message)
    {
        try {
            $from = $message->getFrom();
            $subject = $message->getSubject();
            $dateHeader = $message->getDate();
            
            // Use the safe date parser
            $formattedDate = $this->parseEmailDate($dateHeader);
            
            $to = $message->getTo();
            
            $textBody = '';
            $htmlBody = '';
            
            try {
                $textBody = $message->getTextBody() ?? '';
            } catch (\Exception $e) {
                Log::warning("Erreur récupération texte: " . $e->getMessage());
            }
            
            try {
                $htmlBody = $message->getHTMLBody() ?? '';
            } catch (\Exception $e) {
                Log::warning("Erreur récupération HTML: " . $e->getMessage());
            }
            
            if (empty(trim($htmlBody)) && !empty(trim($textBody))) {
                $htmlBody = $this->formatTextToHtml($textBody);
            }
            
            if (empty(trim($htmlBody)) && empty(trim($textBody))) {
                $htmlBody = '<p class="text-muted"><em>Aucun contenu texte dans cet email</em></p>';
            }
            
            return [
                'uid' => $message->getUid(),
                'message_id' => $message->getMessageId() ?? 'N/A',
                'subject' => !empty($subject) ? $subject : 'Sans objet',
                'from' => !empty($from) ? ($from[0]->mail ?? 'inconnu') : 'inconnu',
                'from_name' => !empty($from) ? ($from[0]->personal ?? '') : '',
                'to' => $this->parseToAddressesSafe($to),
                'date' => $formattedDate, // Use the properly parsed date
                'seen' => $message->getFlags()->contains('seen'),
                'body' => [
                    'text' => $textBody,
                    'html' => $htmlBody
                ],
                'attachments' => $this->parseAttachmentsSafe($message->getAttachments()),
                'attachments_count' => $message->getAttachments()->count(),
                'cc' => $this->parseAddressesSafe($message->getCc()),
                'bcc' => $this->parseAddressesSafe($message->getBcc()),
                'reply_to' => $this->parseAddressesSafe($message->getReplyTo()),
            ];
            
        } catch (\Exception $e) {
            Log::error("Erreur parsing email avec body: " . $e->getMessage());
            return [
                'uid' => 'error',
                'subject' => 'Erreur de parsing',
                'from' => 'inconnu',
                'to' => [],
                'date' => Carbon::now()->subHours(rand(1, 24))->format('Y-m-d H:i:s'), // Use recent date instead of now
                'body' => [
                    'text' => 'Erreur: ' . $e->getMessage(),
                    'html' => '<p class="text-danger">Erreur lors du chargement du contenu: ' . e($e->getMessage()) . '</p>'
                ],
                'attachments' => [],
                'attachments_count' => 0,
                'cc' => [],
                'bcc' => [],
                'reply_to' => [],
                'error' => $e->getMessage()
            ];
        }
    }
    
    protected function parseEmailWithPreview($message)
    {
        try {
            $from = $message->getFrom();
            $subject = $message->getSubject();
            $dateHeader = $message->getDate();
            
            // Use the safe date parser
            $formattedDate = $this->parseEmailDate($dateHeader);
            
            $textBody = '';
            $preview = '';
            
            try {
                $textBody = $message->getTextBody();
            } catch (\Exception $e) {
                Log::warning("Erreur récupération texte preview: " . $e->getMessage());
            }
            
            if (!empty($textBody)) {
                $preview = strip_tags($textBody);
                $preview = Str::limit($preview, 100);
            } else {
                try {
                    $htmlBody = $message->getHTMLBody();
                    if (!empty($htmlBody)) {
                        $preview = strip_tags($htmlBody);
                        $preview = Str::limit($preview, 100);
                    }
                } catch (\Exception $e) {
                    Log::warning("Erreur récupération HTML preview: " . $e->getMessage());
                }
            }
            
            return [
                'uid' => $message->getUid(),
                'message_id' => $message->getMessageId() ?? 'N/A',
                'subject' => !empty($subject) ? $subject : 'Sans objet',
                'from' => !empty($from) ? ($from[0]->mail ?? 'inconnu') : 'inconnu',
                'from_name' => !empty($from) ? ($from[0]->personal ?? '') : '',
                'date' => $formattedDate, // Use the properly parsed date
                'seen' => $message->getFlags()->contains('seen'),
                'preview' => $preview,
                'attachments_count' => $message->getAttachments()->count(),
            ];
            
        } catch (\Exception $e) {
            Log::error("Erreur parsing email avec preview: " . $e->getMessage());
            return [
                'uid' => 'error',
                'subject' => 'Erreur parsing',
                'from' => 'inconnu',
                'date' => Carbon::now()->subHours(rand(1, 24))->format('Y-m-d H:i:s'), // Use recent date instead of now
                'preview' => 'Erreur lors du chargement',
                'seen' => true,
                'attachments_count' => 0,
                'error' => $e->getMessage()
            ];
        }
    }
    
    protected function parseAttachmentsSafe($attachments)
    {
        $result = [];
        
        try {
            foreach ($attachments as $attachment) {
                try {
                    $result[] = [
                        'id' => $attachment->id ?? uniqid(),
                        'name' => $attachment->getName() ?? 'piece_jointe',
                        'size' => $attachment->getSize() ?? 0,
                        'content_type' => $attachment->getContentType() ?? 'application/octet-stream',
                        'disposition' => $attachment->getDisposition() ?? 'attachment'
                    ];
                } catch (\Exception $e) {
                    Log::warning("Erreur parsing pièce jointe: " . $e->getMessage());
                    continue;
                }
            }
        } catch (\Exception $e) {
            Log::error("Erreur globale parsing pièces jointes: " . $e->getMessage());
        }
        
        return $result;
    }
    
    protected function parseAddressesSafe($addresses)
    {
        $result = [];
        
        try {
            foreach ($addresses as $address) {
                try {
                    $result[] = [
                        'email' => $address->mail ?? 'inconnu',
                        'name' => $address->personal ?? $address->mail ?? 'inconnu'
                    ];
                } catch (\Exception $e) {
                    Log::warning("Erreur parsing adresse: " . $e->getMessage());
                    continue;
                }
            }
        } catch (\Exception $e) {
            Log::error("Erreur globale parsing adresses: " . $e->getMessage());
        }
        
        return $result;
    }
    
    protected function parseToAddressesSafe($addresses)
    {
        $result = [];
        
        try {
            foreach ($addresses as $address) {
                try {
                    $result[] = [
                        'email' => $address->mail ?? 'inconnu',
                        'name' => $address->personal ?? $address->mail ?? 'inconnu'
                    ];
                } catch (\Exception $e) {
                    Log::warning("Erreur parsing destinataire: " . $e->getMessage());
                    continue;
                }
            }
        } catch (\Exception $e) {
            Log::error("Erreur globale parsing destinataires: " . $e->getMessage());
        }
        
        return $result;
    }
    
    protected function formatTextToHtml($text)
    {
        if (empty($text)) {
            return '<p class="text-muted"><em>Aucun contenu</em></p>';
        }
        
        $escapedText = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        
        $escapedText = preg_replace(
            '/(https?:\/\/[^\s]+)/',
            '<a href="$1" target="_blank" style="color: #007bff; text-decoration: underline;">$1</a>',
            $escapedText
        );
        
        $escapedText = nl2br($escapedText);
        
        $html = '<div style="white-space: pre-wrap; font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';
        $html .= $escapedText;
        $html .= '</div>';
        
        return $html;
    }
    

public function sendEmail($to, $subject, $content, $options = [])
{
    try {
        // Verify SMTP configuration
        $this->verifySmtpConfig();
        
        $mail = new PeakMindMail($subject, $content, $options);
        
        $mailer = Mail::to($to);
        
        if (!empty($options['cc'])) {
            $mailer->cc($options['cc']);
        }
        
        if (!empty($options['bcc'])) {
            $mailer->bcc($options['bcc']);
        }
        
        // Send the email
        $mailer->send($mail);
        
        // Save to Sent folder
        $this->saveToSentFolder($to, $subject, $content, $options);
         // SAUVEGARDER L'EMAIL ENVOYÉ
            $sentEmailData = [
                'uid' => 'sent_' . uniqid(),
                'subject' => $subject,
                'from' => env('MAIL_FROM_ADDRESS'),
                'from_name' => env('MAIL_FROM_NAME'),
                'to' => $to,
                'content' => $content,
                'date' => Carbon::now()->format('Y-m-d H:i:s'),
                'attachments_count' => 0,
            ];
            
            $this->storageService->saveEmail('sent', $sentEmailData);
        Log::info("Email Turki Avocats envoyé et sauvegardé: {$this->imapConfig['username']} vers: {$to} - Sujet: {$subject}");
        return [
            'success' => true, 
            'message' => 'Email envoyé avec succès et sauvegardé dans "Envoyés"',
            'from' => $this->imapConfig['username'] ?? '',
            'to' => $to
        ];
        
    } catch (\Exception $e) {
        Log::error("Erreur envoi email PeakMind: " . $e->getMessage());
        
        $errorMessage = $this->getUserFriendlyErrorMessage($e->getMessage());
        
        return [
            'success' => false, 
            'error' => $errorMessage,
            'from' => $this->imapConfig['username'] ?? '',
            'technical_error' => $e->getMessage()
        ];
    }
}

/**
 * Save sent email to Sent folder via IMAP
 */
private function saveToSentFolder($to, $subject, $content, $options = [])
{
    try {
        SentEmail::create([
            'from_email' => env('MAIL_FROM_ADDRESS', $this->imapConfig['username'] ?? ''),
            'from_name' => $this->imapConfig['from_name'] ?? '',
            'to_email' => $to,
            'subject' => $subject,
            'content' => $content,
            'cc' => !empty($options['cc']) ? $options['cc'] : null,
            'bcc' => !empty($options['bcc']) ? $options['bcc'] : null,
            'sent_at' => now(),
        ]);
        
        Log::info("Email sauvegardé en base de données: " . $subject);
        return true;
        
    } catch (\Exception $e) {
        Log::error("Erreur sauvegarde email en base: " . $e->getMessage());
        return false;
    }
}

/**
 * Get or create Sent folder
 */
private function getOrCreateSentFolder()
{
    try {
        $folderNames = ['Sent', 'Sent Items', 'Boîte d\'envoi', 'Envoyés'];
        
        foreach ($folderNames as $folderName) {
            try {
                $folder = $this->client->getFolder($folderName);
                if ($folder) {
                    return $folder;
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        
        // If no Sent folder exists, create one
        return $this->createFolder('Sent');
        
    } catch (\Exception $e) {
        throw new \Exception("Impossible d'accéder au dossier Sent: " . $e->getMessage());
    }
}

/**
 * Create a new folder
 */
private function createFolder($folderName)
{
    try {
        $folder = $this->client->getFolder('INBOX');
        return $folder->create($folderName);
    } catch (\Exception $e) {
        throw new \Exception("Impossible de créer le dossier {$folderName}: " . $e->getMessage());
    }
}

/**
 * Create MIME message for sent email
 */
private function createSentEmailMessage($to, $subject, $content, $options = [])
{
    $fromEmail = env('MAIL_FROM_ADDRESS', $this->imapConfig['username'] ?? '');
    $fromName = env('MAIL_FROM_NAME', $this->imapConfig['from_name'] ?? '');
    
    $date = date('r');
    $messageId = '<' . uniqid() . '@turki-avocats.com>';
    
    // Build headers
    $headers = [
        'From' => "{$fromName} <{$fromEmail}>",
        'To' => $to,
        'Subject' => $subject,
        'Date' => $date,
        'Message-ID' => $messageId,
        'MIME-Version' => '1.0',
        'Content-Type' => 'text/html; charset=utf-8',
    ];
    
    // Add CC if exists
    if (!empty($options['cc'])) {
        $headers['Cc'] = is_array($options['cc']) ? implode(', ', $options['cc']) : $options['cc'];
    }
    
    // Build the complete message
    $message = "";
    foreach ($headers as $key => $value) {
        $message .= "{$key}: {$value}\r\n";
    }
    $message .= "\r\n" . $content;
    
    return $message;
}

/**
 * Get emails from Sent folder
 */
public function getSentEmails($limit = 20)
{
    return $this->getEmailsRobust('Sent', $limit);
}

/**
 * Alternative: Get from any sent-like folder
 */
public function getSentFolderEmails($limit = 20)
{
    $sentFolders = ['Sent', 'Sent Items', 'Boîte d\'envoi', 'Envoyés'];
    
    foreach ($sentFolders as $folder) {
        try {
            $result = $this->getEmailsRobust($folder, $limit);
            if ($result['success'] && !empty($result['emails'])) {
                return $result;
            }
        } catch (\Exception $e) {
            continue;
        }
    }
    
    return ['success' => false, 'error' => 'Aucun dossier Sent trouvé'];
}

/**
 * Verify SMTP configuration is properly set
 */
private function verifySmtpConfig()
{
    $smtpConfig = \App\Models\EmailSetting::where('user_id', auth()->id())->first();
    $requiredConfig = [
        'MAIL_HOST' => env('MAIL_HOST'),
        'MAIL_PORT' => env('MAIL_PORT'),
        'MAIL_USERNAME' => $smtpConfig->smtp_username ?? '',
        'MAIL_PASSWORD' => $smtpConfig->smtp_password ?? '',
        'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
    ];
    
    $missingConfig = [];
    foreach ($requiredConfig as $key => $value) {
        if (empty($value)) {
            $missingConfig[] = $key;
        }
    }
    
    if (!empty($missingConfig)) {
        throw new \Exception("Configuration SMTP manquante: " . implode(', ', $missingConfig));
    }
    
    // Specifically check if password is set
    if (empty(env('MAIL_PASSWORD')) || env('MAIL_PASSWORD') === 'your_actual_password_here') {
        throw new \Exception("Le mot de passe SMTP n'est pas configuré correctement dans le fichier .env");
    }
}

/**
 * Convert technical SMTP errors to user-friendly messages
 */
private function getUserFriendlyErrorMessage($technicalError)
{
    $lowerError = strtolower($technicalError);
    
    if (str_contains($lowerError, '535 incorrect authentication data') || 
        str_contains($lowerError, 'authenticator "login" returned') ||
        str_contains($lowerError, 'authenticator "plain" returned')) {
        return 'Erreur d\'authentification SMTP. Vérifiez vos identifiants email (nom d\'utilisateur et mot de passe) dans la configuration.';
    }
    
    if (str_contains($lowerError, 'connection could not be established') ||
        str_contains($lowerError, 'failed to connect')) {
        return 'Impossible de se connecter au serveur SMTP. Vérifiez les paramètres de connexion (hôte, port, chiffrement).';
    }
    
    if (str_contains($lowerError, 'connection timed out')) {
        return 'Délai de connexion au serveur SMTP dépassé. Vérifiez vos paramètres réseau.';
    }
    
    if (str_contains($lowerError, 'password') && str_contains($lowerError, 'incorrect')) {
        return 'Mot de passe SMTP incorrect. Vérifiez le mot de passe dans votre configuration.';
    }
    
    if (str_contains($lowerError, 'username') && str_contains($lowerError, 'incorrect')) {
        return 'Nom d\'utilisateur SMTP incorrect. Vérifiez l\'adresse email dans votre configuration.';
    }
    
    if (str_contains($lowerError, 'tls') || str_contains($lowerError, 'ssl')) {
        return 'Erreur de chiffrement TLS/SSL. Essayez de changer le port ou le type de chiffrement.';
    }
    
    // Default technical error
    return 'Erreur lors de l\'envoi de l\'email: ' . $technicalError;
}

public function getSentEmailsFromDB($limit = 20)
{
    try {
        $emails = SentEmail::orderBy('sent_at', 'desc')
                          ->limit($limit)
                          ->get()
                          ->map(function ($email) {
                              return [
                                  'uid' => 'db_' . $email->id,
                                  'subject' => $email->subject,
                                  'from' => $email->from_email,
                                  'from_name' => $email->from_name,
                                  'to' => [['email' => $email->to_email]],
                                  'date' => $email->sent_at->format('Y-m-d H:i:s'),
                                  'preview' => Str::limit(strip_tags($email->content), 100),
                                  'seen' => true,
                                  'attachments_count' => 0,
                                  'source' => 'database'
                              ];
                          })
                          ->toArray();

        return [
            'success' => true,
            'emails' => $emails,
            'count' => count($emails),
            'source' => 'database'
        ];
        
    } catch (\Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Test SMTP connection (optional helper method)
 */
public function testSmtpConnection()
{
    try {
        $this->verifySmtpConfig();
        
        // Try to send a test email to ourselves
        Mail::raw('Test de connexion SMTP', function ($message) {
            $message->to(env('MAIL_USERNAME'))
                    ->subject('Test SMTP Connection');
        });
        
        return [
            'success' => true,
            'message' => 'Connexion SMTP réussie'
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $this->getUserFriendlyErrorMessage($e->getMessage()),
            'technical_error' => $e->getMessage()
        ];
    }
}
    
    public function reconnect()
    {
        try {
            if ($this->client) {
                $this->client->disconnect();
            }
            $this->connected = false;
            sleep(1);
            $this->initializeClient();
            
            return ['success' => true, 'message' => 'Reconnexion Turki Avocats effectuée'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
/**
 * Récupérer les dossiers avec le nombre d'emails non lus
 */
public function getFoldersWithCounts()
{
    if (!$this->connected) {
        throw new \Exception('Client IMAP Turki Avocats non connecté');
    }
    
    $folderList = [];
    
    try {
        $folders = $this->client->getFolders();
        
        foreach ($folders as $folder) {
            try {
                // Compter le total des messages
                $totalCount = $folder->query()->count();
                
                // Compter les messages non lus
                $unreadCount = $folder->search()->unseen()->count();
                
                $folderInfo = [
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'full_name' => $folder->full_name,
                    'total_count' => $totalCount,
                    'unread_count' => $unreadCount,
                    'children' => $folder->children->count(),
                ];
                
                $folderList[] = $folderInfo;
                
            } catch (\Exception $e) {
                Log::warning("Erreur comptage dossier {$folder->name}: " . $e->getMessage());
                
                $folderList[] = [
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'full_name' => $folder->full_name,
                    'total_count' => 0,
                    'unread_count' => 0,
                    'children' => 0,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $folderList;
        
    } catch (\Exception $e) {
        Log::error('Erreur récupération dossiers avec counts: ' . $e->getMessage());
        throw new \Exception('Impossible de récupérer les dossiers: ' . $e->getMessage());
    }
}


/**
 * Récupérer tous les dossiers disponibles
 */
public function getFoldersSimples()
{
    if (!$this->connected) {
        throw new \Exception('Client IMAP Turki Avocats non connecté');
    }
    
    $folderList = [];
    
    try {
        $folders = $this->client->getFolders(false); // false pour avoir tous les dossiers récursivement
        
        foreach ($folders as $folder) {
            try {
                $folderInfo = [
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'full_name' => $folder->full_name,
                    'has_children' => $folder->children->count() > 0,
                    'children_count' => $folder->children->count(),
                ];
                
                $folderList[] = $folderInfo;
                
            } catch (\Exception $e) {
                Log::warning("Erreur dossier {$folder->name}: " . $e->getMessage());
                
                $folderList[] = [
                    'name' => $folder->name,
                    'path' => 'error',
                    'full_name' => 'error',
                    'has_children' => false,
                    'children_count' => 0,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        // Trier les dossiers par nom pour une meilleure organisation
        usort($folderList, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        return $folderList;
        
    } catch (\Exception $e) {
        Log::error('Erreur récupération dossiers simple: ' . $e->getMessage());
        throw new \Exception('Impossible de récupérer les dossiers: ' . $e->getMessage());
    }
}
public function getFoldersSimple(array $excludedFolders = null)
{
    if (!$this->connected) {
        throw new \Exception('Client IMAP Turki Avocats non connecté');
    }
    
    $folderList = [];
    
    try {
        $folders = $this->client->getFolders(false);
        
        // Dossiers exclus par défaut
        $defaultExcluded = ['INBOX', 'SENT', 'Sent', 'Sent Items', 'Boîte d\'envoi','Archive','spam','Trash','Junk','Drafts'];
        $excluded = $excludedFolders ?? $defaultExcluded;
        
        foreach ($folders as $folder) {
            try {
                $folderName = $folder->name;
                
                // Vérifier l'exclusion
                if (in_array($folderName, $excluded) || 
                    stripos($folderName, 'SENT') !== false) {
                    continue;
                }
                
                $folderInfo = [
                    'name' => $folderName,
                    'path' => $folder->path,
                    'full_name' => $folder->full_name,
                    'has_children' => $folder->children->count() > 0,
                    'children_count' => $folder->children->count(),
                ];
                
                $folderList[] = $folderInfo;
                
            } catch (\Exception $e) {
                Log::warning("Erreur dossier {$folder->name}: " . $e->getMessage());
                continue;
            }
        }
        
        usort($folderList, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
        return $folderList;
        
    } catch (\Exception $e) {
        Log::error('Erreur récupération dossiers simple: ' . $e->getMessage());
        throw new \Exception('Impossible de récupérer les dossiers: ' . $e->getMessage());
    }
}
/**
 * Récupérer tous les dossiers avec leurs sous-dossiers
 */
public function getAllFoldersWithHierarchy()
{
    if (!$this->connected) {
        throw new \Exception('Client IMAP Turki Avocats non connecté');
    }
    
    $allFolders = [];
    
    try {
        // Récupérer tous les dossiers de manière récursive
        $folders = $this->client->getFolders(true);
        
        // Fonction récursive pour parcourir l'arborescence
        $processFolder = function($folder) use (&$processFolder, &$allFolders) {
            try {
                $folderInfo = [
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'full_name' => $folder->full_name,
                    'level' => substr_count($folder->path, $folder->delimiter ?? '.'),
                    'has_children' => $folder->children->count() > 0,
                ];
                
                $allFolders[] = $folderInfo;
                
                // Traiter les sous-dossiers
                foreach ($folder->children as $child) {
                    $processFolder($child);
                }
                
            } catch (\Exception $e) {
                Log::warning("Erreur traitement dossier {$folder->name}: " . $e->getMessage());
            }
        };
        
        foreach ($folders as $folder) {
            $processFolder($folder);
        }
        
        return $allFolders;
        
    } catch (\Exception $e) {
        Log::error('Erreur récupération hiérarchie dossiers: ' . $e->getMessage());
        // Retourner les dossiers de base en cas d'erreur
        return $this->getFoldersSimple();
    }
}

/**
 * Récupérer les dossiers principaux (non récursif)
 */
public function getMainFolders()
{
    if (!$this->connected) {
        throw new \Exception('Client IMAP Turki Avocats non connecté');
    }
    
    $mainFolders = [];
    $commonFolders = ['INBOX', 'Sent', 'Drafts', 'Trash', 'Spam', 'Junk', 'Archive'];
    
    try {
        $folders = $this->client->getFolders();
        
        foreach ($folders as $folder) {
            try {
                $folderInfo = [
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'full_name' => $folder->full_name,
                    'is_common' => in_array($folder->name, $commonFolders),
                    'has_children' => $folder->children->count() > 0,
                ];
                
                $mainFolders[] = $folderInfo;
                
            } catch (\Exception $e) {
                Log::warning("Erreur dossier principal {$folder->name}: " . $e->getMessage());
            }
        }
        
        // Trier : dossiers communs d'abord, puis les autres par ordre alphabétique
        usort($mainFolders, function($a, $b) use ($commonFolders) {
            $aIndex = array_search($a['name'], $commonFolders);
            $bIndex = array_search($b['name'], $commonFolders);
            
            if ($aIndex !== false && $bIndex !== false) {
                return $aIndex - $bIndex;
            } elseif ($aIndex !== false) {
                return -1;
            } elseif ($bIndex !== false) {
                return 1;
            } else {
                return strcmp($a['name'], $b['name']);
            }
        });
        
        return $mainFolders;
        
    } catch (\Exception $e) {
        Log::error('Erreur récupération dossiers principaux: ' . $e->getMessage());
        throw new \Exception('Impossible de récupérer les dossiers principaux: ' . $e->getMessage());
    }
}

/**
 * Supprimer un email via IMAP
 */
/**
 * Version avec recherche par index au lieu d'UID
 */
public function deleteEmailByIndex($folderName, $index, $permanent = false)
{
    if (!$this->connected) {
        return ['success' => false, 'error' => 'Client IMAP non connecté'];
    }

    try {
        $folder = $this->client->getFolder($folderName);
        
        if (!$folder) {
            return ['success' => false, 'error' => "Dossier {$folderName} non trouvé"];
        }

        // Récupérer le message par son index (1-based)
        $messages = $folder->messages()
            ->setFetchOrder('desc')
            ->limit($index)
            ->get();

        if ($messages->count() < $index) {
            return ['success' => false, 'error' => "Index {$index} non trouvé dans le dossier"];
        }

        $targetMessage = $messages[$index - 1]; // Index 1-based to 0-based

        if ($permanent) {
            $targetMessage->setFlag('Deleted');
            return ['success' => true, 'message' => 'Email supprimé définitivement'];
        } else {
            return $this->safeMoveToTrash($targetMessage);
        }

    } catch (\Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Obtenir ou créer le dossier corbeille
 */
private function getOrCreateTrashFolder()
{
    try {
        $folderNames = ['Trash', 'Deleted Items', 'Corbeille', 'Deleted', 'INBOX.Trash'];
        
        foreach ($folderNames as $folderName) {
            try {
                $folder = $this->client->getFolder($folderName);
                if ($folder) {
                    return $folder;
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        
        // Créer le dossier corbeille si inexistant
        return $this->createFolder('Trash');
        
    } catch (\Exception $e) {
        throw new \Exception("Impossible d'accéder au dossier corbeille: " . $e->getMessage());
    }
}

/**
 * Sauvegarder l'email dans le trash du storage
 */
public function saveEmailToTrash($emailData, $originalFolder)
{
    try {
        $emailData['original_folder'] = $originalFolder;
        $emailData['deleted_at'] = now()->format('Y-m-d H:i:s');
        
        return $this->storageService->saveEmail('trash', $emailData);
    } catch (\Exception $e) {
        Log::warning("Erreur sauvegarde email dans trash: " . $e->getMessage());
        return false;
    }
}

/**
 * Supprimer l'email du storage
 */
public function deleteEmailFromStorage($folder, $uid)
{
    try {
        // Implémentation dépend de votre système de stockage
        // Si vous utilisez des fichiers JSON :
        $files = Storage::disk('emails')->files($folder);
        
        foreach ($files as $file) {
            if (str_contains($file, $uid)) {
                Storage::disk('emails')->delete($file);
                return true;
            }
        }
        
        return false;
    } catch (\Exception $e) {
        Log::warning("Erreur suppression email du storage: " . $e->getMessage());
        return false;
    }
}


/**
 * Télécharger une pièce jointe sans recherche UID
 */
public function downloadAttachment($folderName, $uid, $attachment)
{
    if (!$this->connected) {
        return ['success' => false, 'error' => 'Client IMAP non connecté'];
    }

    try {
        $folder = $this->client->getFolder($folderName);
        
        if (!$folder) {
            return ['success' => false, 'error' => "Dossier {$folderName} non trouvé"];
        }

        // Convertir l'UID en numérique
        $numericUid = $this->extractNumericUid($uid);

        // APPROCHE: Parcourir les messages récents pour trouver celui avec le bon UID
        $messages = $folder->messages()
            ->setFetchOrder('desc')
            ->limit(50)
            ->leaveUnread()
            ->get();

        $targetMessage = null;
        
        foreach ($messages as $message) {
            $messageUid = $message->getUid();
            
            if ($messageUid == $numericUid) {
                $targetMessage = $message;
                break;
            }
        }

        if (!$targetMessage) {
            return ['success' => false, 'error' => "Email UID {$numericUid} non trouvé dans les 50 derniers messages"];
        }

        // Récupérer toutes les pièces jointes du message
        $attachments = $targetMessage->getAttachments();
        
        if ($attachments->count() === 0) {
            return ['success' => false, 'error' => "Aucune pièce jointe trouvée dans l'email"];
        }

        // Trouver la pièce jointe spécifique
        $targetAttachment = null;
        foreach ($attachments as $att) {
            $attachmentName = $att->getName();
            $attachmentId = $att->id ?? null;
            
            // Comparer par ID ou par nom
            if ($attachmentId == $attachment['id'] || $attachmentName == $attachment['name']) {
                $targetAttachment = $att;
                break;
            }
        }

        if (!$targetAttachment) {
            return ['success' => false, 'error' => "Pièce jointe '{$attachment['name']}' non trouvée"];
        }

        // Préparer le téléchargement
        $filename = $this->sanitizeFilename($targetAttachment->getName());
        $content = $targetAttachment->getContent();
        $contentType = $targetAttachment->getContentType() ?: 'application/octet-stream';

        $response = response($content)
            ->header('Content-Type', $contentType)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Length', strlen($content))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');

        return [
            'success' => true,
            'response' => $response
        ];

    } catch (\Exception $e) {
        Log::error("Erreur téléchargement pièce jointe", [
            'folder' => $folderName,
            'uid' => $uid,
            'attachment' => $attachment['name'] ?? 'unknown',
            'error' => $e->getMessage()
        ]);

        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Extraire UID numérique
 */
private function extractNumericUid($uid)
{
    if (is_numeric($uid)) {
        return (int)$uid;
    }
    
    if (is_string($uid)) {
        preg_match_all('/\d+/', $uid, $matches);
        if (!empty($matches[0])) {
            return (int)end($matches[0]);
        }
    }
    
    throw new \Exception("UID invalide: " . $uid);
}

/**
 * Nettoyer le nom de fichier
 */
private function sanitizeFilename($filename)
{
    // Supprimer les caractères dangereux
    $filename = preg_replace('/[^a-zA-Z0-9\.\_\-]/', '_', $filename);
    
    // Limiter la longueur
    if (strlen($filename) > 100) {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $filename = substr($name, 0, 95) . '.' . $extension;
    }
    
    return $filename;
}

public static function getEmailData($folderName, $uid)
{
    if (!$this->connected) {
        return ['success' => false, 'error' => 'Client IMAP non connecté'];
    }

    try {
        $folder = $this->client->getFolder($folderName);
        
        if (!$folder) {
            return ['success' => false, 'error' => "Dossier {$folderName} non trouvé"];
        }

        $message = $folder->getMessage($uid);
        
        if (!$message) {
            return ['success' => false, 'error' => "Email UID {$uid} non trouvé"];
        }

        $subject = $message->getSubject() ?? 'Sans objet';

        return [
            'success' => true,
            'subject' => $subject,
            'from' => $this->parseAddressesSafe($message->getFrom()),
            'to' => $this->parseToAddressesSafe($message->getTo()),
            'date' => $message->getDate()->format('Y-m-d H:i:s'),
            'seen' => $message->hasFlag('Seen'),
            'attachments' => $this->parseAttachmentsSafe($message->getAttachments()),
            'body_html' => $message->getHtmlBody() ?: $this->formatTextToHtml($message->getTextBody()),
            'body_text' => $message->getTextBody() ?: strip_tags($message->getHtmlBody() ?? ''),
        ];

    } catch (\Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
}