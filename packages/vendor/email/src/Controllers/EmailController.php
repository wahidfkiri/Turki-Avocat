<?php

namespace Vendor\Email\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\EmailTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    use EmailTrait;

    /**
     * Récupérer la liste des emails d'un dossier
     */
    public function getMailList(Request $request): JsonResponse
    {
        try {
            // Valider les paramètres de la requête
            $request->validate([
                'folder' => 'required|string',
               // 'limit' => 'sometimes|integer|min:1|max:1000'
            ]);

            $folder = $request->input('folder');
          //  $limit = $request->input('limit', 50);

            // Utiliser la version simple pour éviter les problèmes UTF-8
            $result = $this->getEmailsFromFolder($folder);

            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Paramètres invalides',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur interne du serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les emails avec tous les détails
     */
    public function getMailListDetailed(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'folder' => 'required|string'
            ]);

            $folder = $request->input('folder', 'INBOX');

            $result = $this->getEmailsFromFolder($folder);

            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Paramètres invalides',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur interne du serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tester la connexion IMAP
     */
    public function testConnection(): JsonResponse
    {
        try {
            $result = $this->testImapConnection();

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur de test: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lister tous les dossiers disponibles
     */
    public function getFolders(): JsonResponse
    {
        try {
            $connection = $this->getImapConnection('INBOX');

            if (!$connection['success']) {
                return response()->json([
                    'success' => false,
                    'error' => $connection['error']
                ], 500);
            }

            $imap = $connection['connection'];
            $folders = imap_list($imap, $connection['mailbox_path'], '*');

            $folderList = [];
            if ($folders !== false) {
                foreach ($folders as $folder) {
                    $folderName = imap_utf7_decode(str_replace($connection['mailbox_path'], '', $folder));
                    $folderList[] = [
                        'name' => $folderName,
                        'full_path' => $folder
                    ];
                }
            }

            imap_close($imap);

            return response()->json([
                'success' => true,
                'folders' => $folderList
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer un email spécifique par son UID
     */
   public function getEmailByUid(Request $request): JsonResponse
{
    try {
       
        $request->validate([
            'folder' => 'required|string',
            'uid' => 'required|integer'
        ]);

        $folder = $request->input('folder');
        $uid = $request->input('uid');

        $connection = $this->getImapConnection($folder);

        if (!$connection['success']) {
            return response()->json([
                'success' => false,
                'error' => $connection['error']
            ], 500);
        }

        $imap = $connection['connection'];

        // Trouver le numéro de message à partir de l'UID
        $messageNumber = imap_msgno($imap, $uid);

        if ($messageNumber === 0) {
            imap_close($imap);
            return response()->json([
                'success' => false,
                'error' => 'Email non trouvé'
            ], 404);
        }

        $header = imap_headerinfo($imap, $messageNumber);
        $body = $this->getEmailBody($imap, $messageNumber);

        // Décoder le sujet correctement
        $rawSubject = isset($header->subject) ? $header->subject : '';
        $decodedSubject = $this->decodeSubject($rawSubject);

        $email = [
            'uid' => $uid,
            'message_number' => $messageNumber,
            'subject' => $decodedSubject ?: 'Sans objet',
            'from_email' => isset($header->from[0]->mailbox) ? 
                          $header->from[0]->mailbox . '@' . $header->from[0]->host : 
                          'Expéditeur inconnu',
            'from_name' => isset($header->from[0]->personal) ? 
                          $this->decodeMimeString($header->from[0]->personal) : '',
            'to' => $this->formatAddresses($header->to ?? []),
            'date' => isset($header->date) ? $header->date : '',
            'body' => $this->sanitizeUtf8($body),
            'body_preview' => $this->sanitizeUtf8(substr(strip_tags($body), 0, 200))
        ];

        imap_close($imap);

        return response()->json([
            'success' => true,
            'email' => $email
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Formater les adresses email
     */
    private function formatAddresses($addresses): array
    {
        $formatted = [];
        
        if (is_array($addresses)) {
            foreach ($addresses as $address) {
                $formatted[] = [
                    'name' => isset($address->personal) ? $this->decodeMimeString($address->personal) : '',
                    'email' => isset($address->mailbox, $address->host) ? 
                              $address->mailbox . '@' . $address->host : ''
                ];
            }
        }
        
        return $formatted;
    }
    private function decodeSubject($subject) {
    if (empty($subject)) {
        return 'Sans objet';
    }
    
    // Vérifier si le sujet est encodé en MIME
    if (preg_match('/^=\?([^?]+)\?(B|Q)\?(.+)\?=$/i', $subject, $matches)) {
        $charset = $matches[1];
        $encoding = $matches[2];
        $encodedText = $matches[3];
        
        if (strtoupper($encoding) === 'B') {
            // Décodage Base64
            $decoded = base64_decode($encodedText);
        } else {
            // Décodage Quoted-Printable
            $decoded = quoted_printable_decode($encodedText);
        }
        
        // Convertir le charset si nécessaire
        if (strtoupper($charset) !== 'UTF-8') {
            $decoded = mb_convert_encoding($decoded, 'UTF-8', $charset);
        }
        
        return $decoded;
    }
    
    // Si pas d'encodage MIME détecté, retourner tel quel
    return $subject;
}
}