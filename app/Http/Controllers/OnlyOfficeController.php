<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Firebase\JWT\JWT;

class OnlyOfficeController extends Controller
{
    public function open()
    {
        // Fichier docx
        $filePath = storage_path('app/public/view.docx');

        // Créer un fichier vide si manquant
        if (!file_exists($filePath)) {
            file_put_contents($filePath, '');
        }

        // URL publique accessible par OnlyOffice
        $fileUrl = asset('storage/view.docx');

        // Infos utilisateur (auth ou invité)
        $user = [
            "id" => auth()->id() ?? 1,
            "name" => auth()->user()->name ?? "Invité"
        ];

        // Configuration OnlyOffice
        $config = [
            "document" => [
                "fileType" => "docx",
                "key" => md5($filePath . filemtime($filePath)),
                "title" => "view.docx",
                "url" => $fileUrl,
                "permissions" => [
                    "edit" => true,
                    "download" => true,
                    "print" => true,
                    "comment" => true
                ]
            ],
            "documentType" => "word",
            "editorConfig" => [
                "callbackUrl" => route('onlyoffice.callback'),
                "user" => $user,
                "customization" => [
                    "chat" => true,
                    "comments" => true,
                    "help" => true
                ]
            ],
            "token" => $this->generateToken($fileUrl, $user)
        ];

        return view('onlyoffice.editor', compact('config'));
    }

    private function generateToken($fileUrl, $user)
    {
        $secret = env('ONLYOFFICE_JWT_SECRET');

        $payload = [
            'url' => $fileUrl,
            'user' => $user,
            'iat' => time(),
            'exp' => time() + 3600 // token valide 1h
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    public function callback(Request $request)
    {
        // OnlyOffice envoie le status du document
        $data = $request->all();

        // Exemple simple : sauvegarde du fichier si modifié
        if (isset($data['status']) && $data['status'] == 2) { // status 2 = document fermé et sauvegardé
            $downloadUrl = $data['url'];
            $filePath = storage_path('app/public/view.docx');

            // Récupérer le fichier depuis OnlyOffice
            $ch = curl_init($downloadUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer " . $this->generateToken($downloadUrl, $data['user'] ?? [])
            ]);
            $content = curl_exec($ch);
            curl_close($ch);

            if ($content) {
                file_put_contents($filePath, $content);
            }
        }

        return response()->json(['error' => 0]);
    }
}
