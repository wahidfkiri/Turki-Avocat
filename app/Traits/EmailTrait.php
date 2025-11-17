<?php 

namespace App\Traits;

trait EmailTrait 
{
    private function getImapConnection($folder) {
        $mailboxRoot = '{mailbox.nextstep-it.com:993/imap/ssl/novalidate-cert}';
        $username = 'slim.turki@turkiavocats.com';
        $password = 'QSD@rty08112017';
        
        $mailboxPath = $mailboxRoot . $folder;
        
        // Désactiver les erreurs IMAP pour gérer manuellement
        $originalErrorLevel = error_reporting();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        
        // Ouvrir la connexion IMAP
        $imap = @imap_open($mailboxPath, $username, $password, OP_READONLY, 1);
        
        // Restaurer le niveau d'erreur
        error_reporting($originalErrorLevel);
        
        // Vérifier les erreurs IMAP
        $errors = imap_errors();
        if (!$imap || $errors) {
            return [
                'success' => false,
                'error' => $errors ? implode(', ', $errors) : 'Connexion IMAP échouée',
                'mailbox_path' => $mailboxPath
            ];
        }
        
        return [
            'success' => true,
            'connection' => $imap,
            'mailbox_path' => $mailboxPath
        ];
    }

    public function createImapFolderSafe($dossier)
{
    // Nouveau nom attendu
    $newFolderName = $dossier->numero_dossier . '-' . $dossier->nom_dossier . '-' . $dossier->id;
    $newFolderRelative = 'Dossiers/' . $newFolderName;

    // Connexion IMAP
    $connection = $this->getImapConnection('INBOX');
    if (!$connection['success']) {
        return "Erreur de connexion : " . $connection['error'];
    }

    $imap = $connection['connection'];
    $mailboxPath = $connection['mailbox_path'];

    /**
     * IMPORTANT : enlever INBOX du path
     * Exemple :
     * {imap.server:993/ssl}INBOX  --> {imap.server:993/ssl}
     */
    $rootMailbox = preg_replace('/INBOX\.?$/i', '', $mailboxPath);

    // Lister tous les dossiers
    $foldersRaw = @imap_list($imap, $rootMailbox, '*');

    $existingFolderRaw = null;
    $existingNormalized = null;
    $usedSeparator = '/';

    if ($foldersRaw !== false) {
        foreach ($foldersRaw as $raw) {

            // Chemin relatif après rootMailbox
            $after = str_replace($rootMailbox, '', $raw);
            $decoded = imap_utf7_decode($after);

            // Détection séparateur réel du serveur
            if (strpos($after, '.') !== false) {
                $usedSeparator = '.';
            } elseif (strpos($after, '/') !== false) {
                $usedSeparator = '/';
            }

            // Normalize → toujours "/" pour comparer
            $normalized = str_replace($usedSeparator, '/', $decoded);

            // On vérifie que ça commence par Dossiers/
            if (stripos($normalized, 'Dossiers/') !== 0) {
                continue;
            }

            // On cherche un suffixe -ID
            if (preg_match('#Dossiers/(.+)-(\d+)$#', $normalized, $m)) {
                $foundId = (int)$m[2];
                if ($foundId === (int)$dossier->id) {
                    // Dossier IMAP correspondant à cet ID trouvé
                    $existingFolderRaw = $raw;
                    $existingNormalized = $normalized;
                    break;
                }
            }
        }
    }

    // Normalize la cible
    $desiredNormalized = $newFolderRelative;

    /**
     * 1. Le dossier existe déjà et a le bon nom
     */
    if ($existingNormalized === $desiredNormalized) {
        imap_close($imap);
        return "Aucun changement : le dossier '$newFolderName' est déjà correct.";
    }

    /**
     * 2. Le dossier existe mais avec un ancien nom → renommer
     */
    if ($existingFolderRaw) {

        $dstServer = str_replace('/', $usedSeparator, $newFolderRelative);

        $src = imap_utf7_encode($existingFolderRaw);
        $dst = imap_utf7_encode($rootMailbox . $dstServer);

        $renamed = @imap_renamemailbox($imap, $src, $dst);
        imap_close($imap);

        if ($renamed) {
            return "Dossier renommé en '$newFolderName'.";
        } else {
            return "Erreur lors du renommage : " . imap_last_error();
        }
    }

    /**
     * 3. Aucun dossier trouvé pour cet ID → créer
     */
    // Détecter un séparateur par défaut
    if ($foldersRaw && count($foldersRaw)) {
        foreach ($foldersRaw as $raw) {
            $after = str_replace($rootMailbox, '', $raw);
            if (strpos($after, '.') !== false) { $usedSeparator = '.'; break; }
            if (strpos($after, '/') !== false) { $usedSeparator = '/'; break; }
        }
    }

    $serverTarget = str_replace('/', $usedSeparator, $newFolderRelative);

    $created = @imap_createmailbox($imap, imap_utf7_encode($rootMailbox . $serverTarget));
    imap_close($imap);

    if ($created) {
        return "Dossier '$newFolderName' créé avec succès.";
    } else {
        return "Erreur lors de la création : " . imap_last_error();
    }
}


    public function getEmailsFromFolder($folderPath) {
        $connection = $this->getImapConnection($folderPath);
        
        if (!$connection['success']) {
            return [
                'success' => false,
                'error' => $connection['error']
            ];
        }

        $imap = $connection['connection'];
        $emails = [];
        
        try {
            // Vérifier si le dossier existe et a des messages
            $numMessages = imap_num_msg($imap);
            
            if ($numMessages === false) {
                imap_close($imap);
                return [
                    'success' => false,
                    'error' => 'Impossible de récupérer le nombre de messages'
                ];
            }

            if ($numMessages > 0) {
                for ($i = 1; $i <= $numMessages; $i++) {
                    $header = imap_headerinfo($imap, $i);
                    
                    if (!$header) {
                        continue;
                    }
                    
                    $body = $this->getEmailBody($imap, $i);
                    $subject = isset($header->subject) ? $this->decodeMimeString($header->subject) : 'Sans objet';
                    $fromName = isset($header->from[0]->personal) ? $this->decodeMimeString($header->from[0]->personal) : '';
                    
                    $emails[] = [
                        'message_number' => $i,
                        'subject' => $this->sanitizeUtf8($subject),
                        'from_email' => isset($header->from[0]->mailbox) ? 
                                      $header->from[0]->mailbox . '@' . $header->from[0]->host : 
                                      'Expéditeur inconnu',
                        'from_name' => $this->sanitizeUtf8($fromName),
                        'date' => isset($header->date) ? $header->date : '',
                        'body' => $this->sanitizeUtf8($body),
                        'body_preview' => $this->sanitizeUtf8(substr(strip_tags($body), 0, 200)),
                        'uid' => imap_uid($imap, $i)
                    ];
                }
            }

            imap_close($imap);

            return [
                'success' => true,
                'count' => $numMessages,
                'emails' => $emails
            ];
            
        } catch (\Exception $e) {
            imap_close($imap);
            return [
                'success' => false,
                'error' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    private function getEmailBody($imap, $messageNumber) {
        // Essayer de récupérer le texte brut d'abord
        $body = imap_fetchbody($imap, $messageNumber, 1);
        
        if (empty(trim($body))) {
            // Si vide, essayer la partie 1.1 (alternative)
            $body = imap_fetchbody($imap, $messageNumber, "1.1");
        }
        
        if (empty(trim($body))) {
            // Si toujours vide, prendre le corps complet
            $body = imap_body($imap, $messageNumber);
        }
        
        // Décoder le texte quoted-printable si nécessaire
        if (preg_match('/=\?([^?]+)\?Q\?(.+)\?=/i', $body)) {
            $body = imap_qprint($body);
        }
        
        return $body;
    }

    private function decodeMimeString($string) {
        if (empty($string)) {
            return '';
        }

        // Si la chaîne est déjà en UTF-8 valide, retourner directement
        if (mb_check_encoding($string, 'UTF-8')) {
            return $string;
        }

        // Décoder les chaînes encodées MIME
        $decodedString = '';
        
        // Gérer les parties encodées
        $parts = imap_mime_header_decode($string);
        
        if (is_array($parts)) {
            foreach ($parts as $part) {
                if ($part->charset === 'default' || $part->charset === 'us-ascii') {
                    $decodedString .= $part->text;
                } else {
                    // Convertir depuis le charset spécifié vers UTF-8
                    $converted = @iconv($part->charset, 'UTF-8//IGNORE', $part->text);
                    if ($converted !== false) {
                        $decodedString .= $converted;
                    } else {
                        // Si la conversion échoue, utiliser le texte original
                        $decodedString .= $part->text;
                    }
                }
            }
        } else {
            // Fallback: essayer de convertir directement
            $decodedString = @iconv('ISO-8859-1', 'UTF-8//IGNORE', $string);
            if ($decodedString === false) {
                $decodedString = $string;
            }
        }
        
        return $decodedString;
    }

    private function sanitizeUtf8($string) {
        if (empty($string)) {
            return '';
        }

        // Vérifier si la chaîne est déjà en UTF-8 valide
        if (mb_check_encoding($string, 'UTF-8')) {
            return $string;
        }

        // Essayer de convertir en UTF-8
        $utf8String = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        
        // Nettoyer les caractères UTF-8 invalides
        $cleanString = preg_replace('/[^\x{0000}-\x{FFFF}]/u', '', $utf8String);
        
        // Alternative: utiliser iconv avec gestion d'erreurs
        if (empty($cleanString)) {
            $cleanString = @iconv('UTF-8', 'UTF-8//IGNORE', $string);
            if ($cleanString === false) {
                $cleanString = preg_replace('/[^\x00-\x7F]/', '', $string); // ASCII seulement
            }
        }
        
        return $cleanString ?: 'Contenu non lisible';
    }

    // Version simplifiée pour récupérer les emails (moins de traitement)
    public function getEmailsFromFolderSimple($folderPath, $limit = 50) {
        $connection = $this->getImapConnection($folderPath);
        
        if (!$connection['success']) {
            return [
                'success' => false,
                'error' => $connection['error']
            ];
        }

        $imap = $connection['connection'];
        $emails = [];
        
        try {
            $numMessages = imap_num_msg($imap);
            $start = max(1, $numMessages - $limit + 1);
            
            if ($numMessages > 0) {
                for ($i = $start; $i <= $numMessages; $i++) {
                    $overview = imap_fetch_overview($imap, $i, FT_UID);
                    $header = imap_headerinfo($imap, $i);
                    
                    if (empty($overview)) {
                        continue;
                    }
                    
                    $overview = $overview[0];
                    
                    // Utiliser imap_utf8 pour décoder le sujet
                    $subject = isset($overview->subject) ? 
                              imap_utf8($overview->subject) : 
                              'Sans objet';
                    
                    $fromName = isset($header->from[0]->personal) ? 
                               $this->decodeMimeStringSafe($header->from[0]->personal) : 
                               '';
                    
                    $emails[] = [
                        'message_number' => $i,
                        'uid' => $overview->uid,
                        'subject' => $this->forceUtf8($subject),
                        'from_email' => isset($header->from[0]->mailbox) ? 
                                      $header->from[0]->mailbox . '@' . $header->from[0]->host : 
                                      '',
                        'from_name' => $this->forceUtf8($fromName),
                        'date' => isset($overview->date) ? $overview->date : '',
                        'seen' => isset($overview->seen) ? $overview->seen : false
                    ];
                }
            }

            imap_close($imap);

            return [
                'success' => true,
                'count' => $numMessages,
                'emails' => array_reverse($emails) // Plus récents en premier
            ];
            
        } catch (\Exception $e) {
            imap_close($imap);
            return [
                'success' => false,
                'error' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    private function decodeMimeStringSafe($string) {
        if (empty($string)) return '';
        
        // Essayer imap_utf8 d'abord
        $decoded = imap_utf8($string);
        if ($decoded !== false && !empty(trim($decoded))) {
            return $decoded;
        }
        
        // Fallback simple
        return preg_replace('/[^\x20-\x7E]/', '', $string) ?: '';
    }

    private function forceUtf8($string) {
        if (empty($string)) return '';
        
        // Vérifier l'encodage
        $encoding = mb_detect_encoding($string, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
        
        if ($encoding !== 'UTF-8') {
            $string = mb_convert_encoding($string, 'UTF-8', $encoding);
        }
        
        // Nettoyer les caractères invalides
        return mb_convert_encoding($string, 'UTF-8', 'UTF-8');
    }

    // Fonction pour tester la connexion
    public function testImapConnection() {
        $connection = $this->getImapConnection('INBOX');
        
        if (!$connection['success']) {
            return [
                'success' => false,
                'error' => $connection['error'],
                'mailbox_path' => $connection['mailbox_path']
            ];
        }
        
        $imap = $connection['connection'];
        
        // Récupérer des informations sur la boîte mail
        $info = imap_mailboxmsginfo($imap);
        $check = imap_check($imap);
        
        $result = [
            'success' => true,
            'mailbox_path' => $connection['mailbox_path'],
            'messages_count' => $check->Nmsgs ?? 0,
            'recent_messages' => $check->Recent ?? 0,
            'mailbox_size' => $info->Size ?? 0
        ];
        
        imap_close($imap);
        
        return $result;
    }
}