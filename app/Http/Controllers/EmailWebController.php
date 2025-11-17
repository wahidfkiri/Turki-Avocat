<?php
// app/Http/Controllers/EmailWebController.php

namespace App\Http\Controllers;

use App\Services\EmailManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Services\EmailDossierService;
use Illuminate\Support\Facades\Log;
use App\Models\Dossier;
use Webklex\PHPIMAP\ClientManager;
use App\Models\EmailDossier;
use Auth;

class EmailWebController extends Controller
{
    protected $emailService;
    protected $emailDossierService;
    
    public function __construct(EmailManagerService $emailService, EmailDossierService $emailDossierService)
    {
        $this->emailService = $emailService;
        $this->emailDossierService = $emailDossierService;
    }

   public function index()
{
    try {
        \Session::put('user_session_id', auth()->user());
        // Essayer d'abord les dossiers principaux
        $folders = $this->emailService->getFoldersSimples();
        
        // Essayer IMAP d'abord, puis storage en fallback
        $inboxEmails = $this->emailService->getEmailsRobust('INBOX', 30);
        
        // Si IMAP échoue, essayer le storage
        // if (!$inboxEmails['success']) {
        //     $inboxEmails = $this->emailService->getEmailsFromStorage('inbox', 30);
            
        //     // Si le storage a des emails, on les utilise
        //     if ($inboxEmails['success'] && !empty($inboxEmails['emails'])) {
        //         $inboxEmails['warning'] = 'IMAP non disponible - Affichage depuis le stockage local';
        //     } else {
        //         // Si storage vide aussi, garder l'erreur originale
        //         $inboxEmails['warning'] = 'Aucun email trouvé - IMAP et stockage local vides';
        //     }
        // }
        
        // Clean email previews et tri
        if ($inboxEmails['success'] && isset($inboxEmails['emails'])) {
            $inboxEmails['emails'] = $this->cleanEmailPreviews($inboxEmails['emails']);
            $inboxEmails['emails'] = $this->filterUnwantedEmails($inboxEmails['emails']); // NOUVEAU
            $inboxEmails['emails'] = $this->sortEmailsByUidDesc($inboxEmails['emails']);
        }
        
        return view('emails.index', [
            'folders' => $folders,
            'emails' => $inboxEmails['success'] ? $inboxEmails['emails'] : [],
            'currentFolder' => 'INBOX',
            'account' => \App\Services\EmailManagerService::userConfig()->username ?? '',
            'warning' => $inboxEmails['warning'] ?? null,
            'source' => $inboxEmails['source'] ?? 'imap', // Nouveau: source des données
            'totalFolders' => count($folders)
        ]);
        
    } catch (\Exception $e) {
        // Fallback complet : essayer le storage directement
        try {
            $folders = $this->emailService->getFoldersSimples();
            $storageEmails = $this->emailService->getEmailsRobust('inbox', 30);
            
            if ($storageEmails['success'] && isset($storageEmails['emails'])) {
                $storageEmails['emails'] = $this->cleanEmailPreviews($storageEmails['emails']);
                $storageEmails['emails'] = $this->sortEmailsByUidDesc($storageEmails['emails']);
            }
            
            return view('emails.index', [
                'folders' => $folders,
                'emails' => $storageEmails['success'] ? $storageEmails['emails'] : [],
                'currentFolder' => 'INBOX',
                'account' => \App\Services\EmailManagerService::userConfig()->username ?? '',
                'warning' => 'IMAP indisponible - Affichage depuis le stockage local: ' . $e->getMessage(),
                'source' => 'storage_fallback',
                'totalFolders' => count($folders)
            ]);
            
        } catch (\Exception $e2) {
            // Dernier fallback : vue vide
            return view('emails.index', [
                'folders' => [],
                'emails' => [],
                'error' => 'Système complètement indisponible: ' . $e2->getMessage(),
                'account' => \App\Services\EmailManagerService::userConfig()->username ?? '',
                'currentFolder' => 'INBOX',
                'totalFolders' => 0,
                'source' => 'error'
            ]);
        }
    }
}


public function createImapFolderSafe() {
    $mailboxRoot = '{mailbox.nextstep-it.com:993/imap/ssl}';
    $username = 'contact@turkiavocats.com';
    $password = '6NsNs23Nu';
    $folderName = 'Dossier Test';
    $fullFolder = 'INBOX/' . $folderName;

    // Connexion au serveur IMAP
    $imap = imap_open($mailboxRoot . 'INBOX', $username, $password);

    if (!$imap) {
        return "Erreur de connexion : " . imap_last_error();
    }

    // Lister les dossiers existants
    $folders = imap_list($imap, $mailboxRoot, '*');
    $exists = false;
    if ($folders !== false) {
        foreach ($folders as $folder) {
            // imap_list renvoie les dossiers en IMAP-UTF7
            if (imap_utf7_decode(str_replace($mailboxRoot, '', $folder)) === $fullFolder) {
                $exists = true;
                break;
            }
        }
    }

    if ($exists) {
        imap_close($imap);
        return "Le dossier '$folderName' existe déjà.";
    }

    // Créer le dossier
    $result = imap_createmailbox($imap, imap_utf7_encode($mailboxRoot . $fullFolder));

    imap_close($imap);

    if ($result) {
        return "Dossier '$folderName' créé avec succès.";
    } else {
        return "Erreur lors de la création du dossier : " . imap_last_error();
    }
}



    public function showFolder($folder)
{
    try {
        $folders = $this->emailService->getFoldersSimples();
        
        // Essayer d'abord IMAP, sinon storage
        $emailsResult = $this->emailService->getEmailsRobust($folder, 30);
        
        // if (!$emailsResult['success']) {
        //     // Fallback: lire depuis le storage
        //     $emailsResult = $this->emailService->getEmailsFromStorage(strtolower($folder), 30);
        // }
        
        // Nettoyer et trier les emails
        if (isset($emailsResult['emails'])) {
            $emailsResult['emails'] = $this->cleanEmailPreviews($emailsResult['emails']);
            $emailsResult['emails'] = $this->sortEmailsByUidDesc($emailsResult['emails']);
        }
        
        return view('emails.index', [
            'folders' => $folders,
            'emails' => $emailsResult['emails'],
            'currentFolder' => $folder,
            'account' => \App\Services\EmailManagerService::userConfig()->username ?? '',
            'source' => $emailsResult['source'] ?? 'imap'
        ]);
        
    } catch (\Exception $e) {
        return redirect()->route('email.index')
            ->with('error', 'Erreur lors du chargement: ' . $e->getMessage());
    }
}

    public function showEmail($folder, $uid)
    {
        try {
            $folders = $this->emailService->getFoldersSimples();
            
            $emailResult = $this->emailService->getEmailSimple($folder, $uid);
            
            if (!$emailResult['success']) {
                $emailResult = $this->emailService->findEmailByUidSequential($folder, $uid, 30);
            }
            
            if (!$emailResult['success']) {
                return redirect()->route('email.folder', $folder)
                    ->with('error', $emailResult['error']);
            }
            
            // Clean email preview for single email view if needed
            if (isset($emailResult['email']['preview'])) {
                $emailResult['email']['clean_preview'] = $this->cleanSinglePreview($emailResult['email']['preview']);
            }
            
            return view('emails.show', [
                'folders' => $folders,
                'email' => $emailResult['email'],
                'currentFolder' => $folder,
                'account' => \App\Services\EmailManagerService::userConfig()->username ?? '',
                'formatFileSize' => function($bytes) {
                return $this->formatFileSize($bytes);
            }
            ]);
            
        } catch (\Exception $e) {
            return redirect()->route('email.folder', $folder)
                ->with('error', 'Erreur lors du chargement de l\'email: ' . $e->getMessage());
        }
    }
    
    /**
     * Clean email previews for multiple emails
     */
    private function cleanEmailPreviews($emails)
    {
        return collect($emails)->map(function ($email) {
            if (isset($email['preview'])) {
                $email['clean_preview'] = $this->cleanSinglePreview($email['preview']);
            }
            return $email;
        })->toArray();
    }
    
    /**
     * Clean a single email preview text
     */
    private function cleanSinglePreview($preview)
    {
        if (empty($preview) || trim($preview) === '...') {
            return '';
        }
        
        // Step 1: Decode Unicode escape sequences (\u00e9 -> é)
        $cleaned = $this->decodeUnicodeEscapes($preview);
        
        // Step 2: Decode HTML entities (&nbsp; -> space)
        $cleaned = html_entity_decode($cleaned, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Step 3: Remove HTML tags
        $cleaned = strip_tags($cleaned);
        
        // Step 4: Normalize and clean whitespace
        $cleaned = $this->normalizeWhitespace($cleaned);
        
        // Step 5: Trim and limit
        $cleaned = trim($cleaned);
        
        return Str::limit($cleaned, 80);
    }
    
    /**
     * Decode Unicode escape sequences like \u00e9, \u00e0, etc.
     */
    private function decodeUnicodeEscapes($text)
    {
        return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $text);
    }
    
    /**
     * Normalize various whitespace characters
     */
    private function normalizeWhitespace($text)
    {
        // Replace various line breaks and tabs with single space
        $text = preg_replace('/[\r\n\t]+/', ' ', $text);
        
        // Replace multiple spaces with single space
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Remove non-breaking spaces and other special spaces
        $text = str_replace(['&nbsp;', '\u00a0', ' '], ' ', $text);
        
        return $text;
    }
    
    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'content' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $result = $this->emailService->sendEmail(
            $request->to,
            $request->subject,
            $request->content,
            $request->only(['cc', 'bcc'])
        );
        
        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['error']);
        }
    }
public function showSentFolder()
{
    try {
        $folders = $this->emailService->getFoldersSimples();
       dd($sentEmails = $this->emailService->getSentFolderEmails(30));
        
        // Clean email previews and sort by date descending
        if ($sentEmails['success'] && isset($sentEmails['emails'])) {
            $sentEmails['emails'] = $this->cleanEmailPreviews($sentEmails['emails']);
            $sentEmails['emails'] = $this->sortEmailsByUidDesc($sentEmails['emails']);
        }
        
        return view('emails.index', [
            'folders' => $folders,
            'emails' => $sentEmails['success'] ? $sentEmails['emails'] : [],
            'currentFolder' => 'Sent',
            'account' => \App\Services\EmailManagerService::userConfig()->username ?? '',
            'warning' => $sentEmails['warning'] ?? ($sentEmails['error'] ?? null),
            'totalFolders' => count($folders)
        ]);
        
    } catch (\Exception $e) {
        return redirect()->route('email.index')
            ->with('error', 'Erreur lors du chargement des emails envoyés: ' . $e->getMessage());
    }
}
    
    public function markAsRead(Request $request)
    {
        // À implémenter ultérieurement
        return response()->json(['success' => true, 'message' => 'Fonction à implémenter']);
    }
    
    public function moveEmail(Request $request)
    {
        // À implémenter ultérieurement
        return response()->json(['success' => true, 'message' => 'Fonction à implémenter']);
    }
    
   public function deleteEmail(Request $request)
{
    $validator = Validator::make($request->all(), [
        'folder' => 'required|string',
        'uid' => 'required',
        'permanent' => 'boolean'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'error' => 'Données invalides: ' . implode(', ', $validator->errors()->all())
        ], 400);
    }

    try {
        $folder = $request->folder;
        $uid = $request->uid;
        $permanent = $request->boolean('permanent', false);

        // 1. Sauvegarder l'email avant suppression (optionnel)
        $emailToDelete = $this->emailService->getEmailSimple($folder, $uid);
        if ($emailToDelete['success']) {
            // Sauvegarder dans le dossier "trash" du storage
            $this->emailService->saveEmailToTrash($emailToDelete['email'], $folder);
        }

        // 2. Supprimer de IMAP
        $deleteResult = $this->emailService->deleteEmailByIndex($folder, $uid, $permanent);

        if ($deleteResult['success']) {
            // 3. Supprimer du storage local si suppression permanente
            if ($permanent) {
                $this->emailService->deleteEmailFromStorage($folder, $uid);
            }

            Log::info("Email supprimé", [
                'folder' => $folder,
                'uid' => $uid,
                'permanent' => $permanent,
                'user' => auth()->user()->email ?? 'unknown'
            ]);

            return response()->json([
                'success' => true,
                'message' => $permanent ? 'Email supprimé définitivement' : 'Email déplacé vers la corbeille',
                'action' => $permanent ? 'deleted' : 'trashed'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => $deleteResult['error']
            ], 500);
        }

    } catch (\Exception $e) {
        Log::error("Erreur suppression email", [
            'folder' => $request->folder,
            'uid' => $request->uid,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'error' => 'Erreur lors de la suppression: ' . $e->getMessage()
        ], 500);
    }
}
    
    public function testConnection()
    {
        $status = $this->emailService->testConnection();
        return response()->json($status);
    }
    
    public function reconnect()
    {
        $result = $this->emailService->reconnect();
        
        if ($result['success']) {
            return redirect()->route('email.index')->with('success', $result['message']);
        } else {
            return redirect()->route('email.index')->with('error', $result['error']);
        }
    }

/**
 * Simple future date correction and sorting
 */
private function sortEmailsByDateDesc($emails)
{
    $currentYear = (int)date('Y');
    
    return collect($emails)
        ->map(function ($email) use ($currentYear) {
            // Fix future dates
            if (isset($email['date']) && preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $email['date'], $matches)) {
                $emailYear = (int)$matches[1];
                
                // If year is in future, change to current year
                if ($emailYear > $currentYear) {
                    $email['date'] = $currentYear . substr($email['date'], 4);
                }
            }
            return $email;
        })
        ->sortByDesc(function ($email) {
            return $email['date'] ?? '0000-00-00 00:00:00';
        })
        ->values()
        ->toArray();
}

/**
 * Sort emails by UID in descending order (newest first)
 */
private function sortEmailsByUidDesc($emails)
{
    return collect($emails)
        ->sortByDesc(function ($email) {
            // Extraire la partie numérique de l'UID
            $uid = $email['uid'] ?? 0;
            
            // Si l'UID est une string avec des caractères, extraire les chiffres
            if (is_string($uid) && preg_match('/\d+/', $uid, $matches)) {
                return (int)$matches[0];
            }
            
            // Si c'est déjà un nombre
            return (int)$uid;
        })
        ->values()
        ->toArray();
}

/**
 * Filtrer les emails indésirables
 */
private function filterUnwantedEmails($emails)
{
    return collect($emails)->filter(function ($email) {
        return !$this->isUnwantedEmail($email);
    })->values()->toArray();
}

/**
 * Vérifier si un email est indésirable
 */
private function isUnwantedEmail($email)
{
    $subject = strtolower($email['subject'] ?? '');
    $from = strtolower($email['from'] ?? '');
    $fromName = strtolower($email['from_name'] ?? '');
    $preview = strtolower($email['preview'] ?? '');
    
    // Emails de test Outlook
    $outlookTests = [
        'microsoft outlook',
        'test settings',
        'testing settings',
        'test message',
        'e-mail message sent automatically',
    ];
    
    foreach ($outlookTests as $test) {
        if (str_contains($subject, $test) || 
            str_contains($fromName, $test) ||
            str_contains($preview, $test)) {
            return true;
        }
    }
    
    // Autres emails indésirables
    $unwantedSenders = [
        'mailer-daemon',
        'postmaster',
        'noreply',
        'no-reply',
        'automatic-reply',
    ];
    
    foreach ($unwantedSenders as $sender) {
        if (str_contains($from, $sender) || str_contains($fromName, $sender)) {
            return true;
        }
    }
    
    return false;
}
/**
 * Supprimer plusieurs emails
 */
public function deleteMultipleEmails(Request $request)
{
    $validator = Validator::make($request->all(), [
        'folder' => 'required|string',
        'uids' => 'required|array',
        'uids.*' => 'required',
        'permanent' => 'boolean'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'error' => 'Données invalides'
        ], 400);
    }

    try {
        $folder = $request->folder;
        $uids = $request->uids;
        $permanent = $request->boolean('permanent', false);

        $results = [];
        $successCount = 0;
        $errorCount = 0;

        foreach ($uids as $uid) {
            $deleteResult = $this->emailService->deleteEmailFromImap($folder, $uid, $permanent);
            
            if ($deleteResult['success']) {
                $successCount++;
                
                if ($permanent) {
                    $this->emailService->deleteEmailFromStorage($folder, $uid);
                }
            } else {
                $errorCount++;
                $results['errors'][$uid] = $deleteResult['error'];
            }
        }

        Log::info("Emails multiples supprimés", [
            'folder' => $folder,
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'permanent' => $permanent
        ]);

        return response()->json([
            'success' => true,
            'message' => "{$successCount} email(s) " . ($permanent ? 'supprimés' : 'déplacés') . ($errorCount > 0 ? ", {$errorCount} erreur(s)" : ""),
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $results['errors'] ?? []
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Erreur lors de la suppression multiple: ' . $e->getMessage()
        ], 500);
    }
}
/**
 * Vider la corbeille
 */
public function emptyTrash(Request $request)
{
    try {
        $permanent = $request->boolean('permanent', true);
        
        $result = $this->emailService->emptyTrashFolder($permanent);
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'deleted_count' => $result['deleted_count'] ?? 0
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => $result['error']
            ], 500);
        }

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Erreur lors du vidage de la corbeille: ' . $e->getMessage()
        ], 500);
    }
}


public function downloadAttachment(Request $request)
{
    $validator = Validator::make($request->all(), [
        'folder' => 'required|string',
        'uid' => 'required',
        'attachment_id' => 'required',
        'filename' => 'required|string'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'error' => 'Paramètres manquants'
        ], 400);
    }

    try {
        $folder = $request->folder;
        $uid = $request->uid;
        $attachmentId = $request->attachment_id;
        $filename = $request->filename;

        // Utiliser IMAP natif au lieu de Webklex/IMAP
        $downloadResult = $this->downloadAttachmentNative($folder, $uid, $attachmentId, $filename);

        if ($downloadResult['success']) {
            return $downloadResult['response'];
        } else {
            return response()->json([
                'success' => false,
                'error' => $downloadResult['error']
            ], 500);
        }

    } catch (\Exception $e) {
        Log::error("Erreur téléchargement pièce jointe IMAP natif", [
            'folder' => $request->folder,
            'uid' => $request->uid,
            'attachment_id' => $request->attachment_id,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'error' => 'Erreur lors du téléchargement: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Téléchargement de pièce jointe avec IMAP natif PHP
 */
private function downloadAttachmentNative($folder, $uid, $attachmentId, $filename)
{
    $connection = null;
    
    try {
        // Configuration IMAP
        $host = env('IMAP_HOST', 'mail.peakmind-solutions.com');
        $username = env('IMAP_USERNAME', 'contact@turkiavocats.com');
        $password = env('IMAP_PASSWORD');
        $port = env('IMAP_PORT', 993);
        $encryption = env('IMAP_ENCRYPTION', 'ssl');

        // Construction de la mailbox
        $mailbox = "{{$host}:{$port}/imap/{$encryption}}{$folder}";
        
        // Connexion IMAP
        $connection = imap_open($mailbox, $username, $password, 0, 1, ['DISABLE_AUTHENTICATOR' => 'GSSAPI']);
        
        if (!$connection) {
            throw new \Exception('Connexion IMAP échouée: ' . imap_last_error());
        }

        // Convertir l'UID en numéro de message
        $messageNumber = imap_msgno($connection, $uid);
        
        if ($messageNumber === 0) {
            throw new \Exception("Message UID {$uid} non trouvé dans le dossier {$folder}");
        }

        // Obtenir la structure du message
        $structure = imap_fetchstructure($connection, $messageNumber);
        
        if (!$structure) {
            throw new \Exception("Impossible de lire la structure du message");
        }

        // Parcourir les parties du message pour trouver les pièces jointes
        $attachments = $this->extractAttachmentsNative($connection, $messageNumber, $structure);
        
        if (empty($attachments)) {
            throw new \Exception("Aucune pièce jointe trouvée dans le message");
        }

        // Trouver la pièce jointe spécifique
        $targetAttachment = null;
        foreach ($attachments as $index => $attachment) {
            if ($attachment['id'] == $attachmentId || $attachment['filename'] == $filename) {
                $targetAttachment = $attachment;
                $targetAttachment['part_number'] = $index + 1; // Les parties commencent à 1
                break;
            }
        }

        if (!$targetAttachment) {
            throw new \Exception("Pièce jointe '{$filename}' non trouvée");
        }

        // Récupérer le contenu de la pièce jointe
        $content = imap_fetchbody($connection, $messageNumber, $targetAttachment['part_number']);
        
        // Décoder selon l'encodage
        $content = $this->decodeContent($content, $targetAttachment['encoding']);
        
        // Nettoyer le nom de fichier
        $cleanFilename = $this->sanitizeFilename($targetAttachment['filename']);

        // Préparer la réponse de téléchargement
        $response = response($content)
            ->header('Content-Type', $targetAttachment['type'])
            ->header('Content-Disposition', 'attachment; filename="' . $cleanFilename . '"')
            ->header('Content-Length', strlen($content))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');

        imap_close($connection);

        return [
            'success' => true,
            'response' => $response
        ];

    } catch (\Exception $e) {
        // Fermer la connexion en cas d'erreur
        if ($connection) {
            imap_close($connection);
        }
        
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

/**
 * Extraire les pièces jointes avec IMAP natif
 */
private function extractAttachmentsNative($connection, $messageNumber, $structure, $partNumber = '')
{
    $attachments = [];

    if (isset($structure->parts)) {
        foreach ($structure->parts as $index => $part) {
            $currentPartNumber = $partNumber ? $partNumber . '.' . ($index + 1) : ($index + 1);
            
            // Vérifier si c'est une pièce jointe
            $isAttachment = false;
            $filename = '';
            $contentId = '';

            // Vérifier les paramètres de disposition
            if (isset($part->disposition) && strtolower($part->disposition) === 'attachment') {
                $isAttachment = true;
                if (isset($part->dparameters)) {
                    foreach ($part->dparameters as $param) {
                        if (strtolower($param->attribute) === 'filename') {
                            $filename = $this->decodeMimeString($param->value);
                            break;
                        }
                    }
                }
            }

            // Vérifier les paramètres de type
            if (!$isAttachment && isset($part->parameters)) {
                foreach ($part->parameters as $param) {
                    if (strtolower($param->attribute) === 'name') {
                        $isAttachment = true;
                        $filename = $this->decodeMimeString($param->value);
                        break;
                    }
                }
            }

            // Vérifier l'ID de contenu
            if (isset($part->id)) {
                $contentId = trim($part->id, '<>');
            }

            if ($isAttachment && $filename) {
                $attachments[] = [
                    'id' => $contentId ?: md5($filename),
                    'filename' => $filename,
                    'type' => $this->getMimeType($part),
                    'encoding' => $part->encoding ?? 0,
                    'size' => $this->getPartSize($connection, $messageNumber, $currentPartNumber),
                    'part_number' => $currentPartNumber
                ];
            }

            // Recherche récursive dans les sous-parties
            if (isset($part->parts)) {
                $subAttachments = $this->extractAttachmentsNative($connection, $messageNumber, $part, $currentPartNumber);
                $attachments = array_merge($attachments, $subAttachments);
            }
        }
    } else {
        // Message sans parties multiples (texte simple)
        if (isset($structure->disposition) && strtolower($structure->disposition) === 'attachment') {
            $filename = '';
            if (isset($structure->dparameters)) {
                foreach ($structure->dparameters as $param) {
                    if (strtolower($param->attribute) === 'filename') {
                        $filename = $this->decodeMimeString($param->value);
                        break;
                    }
                }
            }
            
            if ($filename) {
                $attachments[] = [
                    'id' => md5($filename),
                    'filename' => $filename,
                    'type' => $this->getMimeType($structure),
                    'encoding' => $structure->encoding ?? 0,
                    'size' => $this->getPartSize($connection, $messageNumber, '1'),
                    'part_number' => '1'
                ];
            }
        }
    }

    return $attachments;
}

/**
 * Décoder le contenu selon l'encodage
 */
private function decodeContent($content, $encoding)
{
    switch ($encoding) {
        case 3: // BASE64
            return base64_decode($content);
        case 4: // QUOTED-PRINTABLE
            return quoted_printable_decode($content);
        case 1: // 8BIT
        case 2: // BINARY
        default:
            return $content;
    }
}

/**
 * Obtenir le type MIME
 */
private function getMimeType($part)
{
    if (isset($part->subtype)) {
        $type = strtolower($part->type);
        $subtype = strtolower($part->subtype);
        
        $typeMap = [
            0 => 'text',
            1 => 'multipart',
            2 => 'message',
            3 => 'application',
            4 => 'audio',
            5 => 'image',
            6 => 'video',
            7 => 'other'
        ];
        
        $mainType = $typeMap[$type] ?? 'application';
        return $mainType . '/' . $subtype;
    }
    
    return 'application/octet-stream';
}

/**
 * Obtenir la taille d'une partie
 */
private function getPartSize($connection, $messageNumber, $partNumber)
{
    try {
        $body = imap_fetchbody($connection, $messageNumber, $partNumber, FT_PEEK);
        return strlen($body);
    } catch (\Exception $e) {
        return 0;
    }
}

/**
 * Décoder les strings MIME
 */
private function decodeMimeString($string)
{
    // Décoder les encoded-words MIME (ex: =?utf-8?Q?filename?=)
    if (preg_match('/^=\?([^?]+)\?([QB])\?(.+)\?=$/i', $string, $matches)) {
        $charset = $matches[1];
        $encoding = strtoupper($matches[2]);
        $text = $matches[3];
        
        if ($encoding === 'Q') {
            $text = quoted_printable_decode(str_replace('_', ' ', $text));
        } elseif ($encoding === 'B') {
            $text = base64_decode($text);
        }
        
        if (strtoupper($charset) !== 'UTF-8') {
            $text = mb_convert_encoding($text, 'UTF-8', $charset);
        }
        
        return $text;
    }
    
    return $string;
}

/**
 * Nettoyer le nom de fichier
 */
private function sanitizeFilename($filename)
{
    // Supprimer les caractères dangereux
    $filename = preg_replace('/[^\w\.\-]/', '_', $filename);
    
    // Limiter la longueur
    if (strlen($filename) > 100) {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $filename = substr($name, 0, 95) . '.' . $extension;
    }
    
    return $filename;
}

/**
 * Formater la taille de fichier
 */
private function formatFileSize($bytes)
{
    if ($bytes == 0) return '0 Bytes';
    
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    
    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}

     public function attachToDossier(Request $request)
    {
        $request->validate([
            'folder_id' => 'required|exists:dossiers,id',
            'email_uids' => 'required|array|min:1',
            'email_uids.*' => 'required|string',
            'current_folder' => 'required|string'
        ]);

        // Vérifier que l'utilisateur est authentifié
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }

       try {
            
            // Récupérer les données des emails pour les stocker
            $emailData = [];
            foreach ($request->email_uids as $uid) {
                $result = $this->emailService->getEmailSimple($request->current_folder, $uid);
                // Ici vous pouvez récupérer les infos de l'email depuis IMAP
                $emailData[$uid] = [
                    'subject' => $result['email']['subject'] ?? 'Sujet non disponible',
                    'from' => $result['email']['from'] ?? 'Expéditeur non disponible',
                    'date' => $result['email']['date'] ?? now()
                ];
            }

            $result = $this->emailDossierService->attachEmailsToDossier(
                $request->folder_id,
                $request->email_uids,
                $request->current_folder,
                $emailData
            );

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Erreur contrôleur attachToDossier', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher les emails d'un dossier pour l'utilisateur connecté
     */
    public function showDossierEmails($dossierId)
    {
        $dossier = \App\Models\Dossier::findOrFail($dossierId);
        
        // Vérifier que l'utilisateur peut voir ce dossier
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $emails = $this->emailDossierService->getAttachedEmails($dossierId);

        return view('emails.dossier', compact('dossier', 'emails'));
    }

    /**
     * Détacher des emails d'un dossier
     */
    public function detachFromDossier(Request $request)
    {
        $request->validate([
            'dossier_id' => 'required|exists:dossiers,id',
            'email_uids' => 'required|array|min:1',
            'email_uids.*' => 'required|string'
        ]);

        // Vérifier que l'utilisateur est authentifié
        if (!\Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }

        $result = $this->emailDossierService->detachEmailsFromDossier(
            $request->dossier_id,
            $request->email_uids
        );

       return response()->json($result);
    }

    /**
     * Migration des données existantes (à exécuter une fois après la migration)
     */
    public function migrateData()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }

        $migratedCount = $this->emailDossierService->migrateExistingData(Auth::id());

        return response()->json([
            'success' => true,
            'message' => "{$migratedCount} enregistrements migrés vers l'utilisateur " . Auth::user()->name
        ]);
    }
}