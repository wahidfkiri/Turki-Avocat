<?php
// app/Services/NativeImapService.php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class NativeImapService
{
    private $connection;
    private $username;
    private $password;
    private $host;

    public function __construct()
    {
        $this->host = env('IMAP_HOST');
        $this->username = env('IMAP_USERNAME');
        $this->password = env('IMAP_PASSWORD');
        $this->connect();
    }

    public function connect()
    {
        $mailbox = "{{$this->host}:993/imap/ssl}INBOX";
        $this->connection = imap_open($mailbox, $this->username, $this->password);
        
        if (!$this->connection) {
            throw new \Exception('Connexion IMAP échouée: ' . imap_last_error());
        }
    }

    /**
     * SUPPRESSION avec IMAP natif
     */
    public function deleteMessage($folder, $messageNumber)
    {
        try {
            // Se connecter au dossier spécifique
            $mailbox = "{{$this->host}:993/imap/ssl}$folder";
            $connection = imap_open($mailbox, $this->username, $this->password);
            
            // Marquer pour suppression
            imap_delete($connection, $messageNumber);
            
            // Appliquer la suppression
            imap_expunge($connection);
            imap_close($connection);
            
            return ['success' => true, 'message' => 'Email supprimé'];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * DÉPLACEMENT avec IMAP natif
     */
    public function moveMessage($fromFolder, $toFolder, $messageNumber)
    {
        try {
            $sourceMailbox = "{{$this->host}:993/imap/ssl}$fromFolder";
            $sourceConnection = imap_open($sourceMailbox, $this->username, $this->password);
            
            // Copier le message
            imap_mail_copy($sourceConnection, $messageNumber, $toFolder);
            
            // Supprimer l'original
            imap_delete($sourceConnection, $messageNumber);
            imap_expunge($sourceConnection);
            
            imap_close($sourceConnection);
            
            return ['success' => true, 'message' => 'Email déplacé'];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * TÉLÉCHARGEMENT avec IMAP natif
     */
    public function downloadAttachmentNative($folder, $messageNumber, $attachmentNumber)
    {
        try {
            $mailbox = "{{$this->host}:993/imap/ssl}$folder";
            $connection = imap_open($mailbox, $this->username, $this->password);
            
            // Structure du message
            $structure = imap_fetchstructure($connection, $messageNumber);
            
            $attachments = [];
            if (isset($structure->parts)) {
                foreach ($structure->parts as $partNumber => $part) {
                    if ($part->ifdparameters) {
                        foreach ($part->dparameters as $param) {
                            if (strtolower($param->attribute) == 'filename') {
                                $filename = $param->value;
                                $content = imap_fetchbody($connection, $messageNumber, $partNumber + 1);
                                
                                // Décoder si nécessaire
                                if ($part->encoding == 3) { // BASE64
                                    $content = base64_decode($content);
                                } elseif ($part->encoding == 4) { // QUOTED-PRINTABLE
                                    $content = quoted_printable_decode($content);
                                }
                                
                                $attachments[] = [
                                    'filename' => $filename,
                                    'content' => $content,
                                    'size' => strlen($content)
                                ];
                            }
                        }
                    }
                }
            }
            
            imap_close($connection);
            
            if (isset($attachments[$attachmentNumber])) {
                return [
                    'success' => true,
                    'attachment' => $attachments[$attachmentNumber]
                ];
            }
            
            return ['success' => false, 'error' => 'Pièce jointe non trouvée'];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}