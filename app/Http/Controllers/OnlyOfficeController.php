<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Firebase\JWT\JWT;

class OnlyOfficeController extends Controller
{
    private $jwtSecret = '9PYbJQIoas57t61v4MGkCyNjsT2VPgpriq17xqRPC0uw3KW96SQw6cleyrglpxeN'; // must match OnlyOffice Docker JWT_SECRET

    public function open()
    {
        $filePath = storage_path('app/view.docx');

        if (!file_exists($filePath)) {
            abort(404, "Le fichier n'existe pas.");
        }

        $fileUrl = asset('storage/view.docx'); // Assure-toi d'avoir fait php artisan storage:link

        $user = [
            "id" => auth()->id() ?? 1,
            "name" => auth()->user()->name ?? "Invité",
        ];

        $documentKey = md5($filePath . filemtime($filePath));

        $payload = [
            "document" => [
                "fileType" => "docx",
                "key" => $documentKey,
                "title" => "example.docx",
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
                    "help" => true,
                ]
            ],
            "iat" => time(),
            "exp" => time() + 3600, // token valable 1h
        ];

        // Génération du JWT
        $token = JWT::encode($payload, $this->jwtSecret, 'HS256');

        return view('onlyoffice.editor', [
            'config' => $payload,
            'token' => $token,
        ]);
    }

    public function callback(Request $request)
    {
        $data = $request->all();

        // OnlyOffice envoie les modifications du fichier
        // Vérifier status et sauvegarder
        if (isset($data['status']) && $data['status'] == 2) { // status 2 = ready to save
            $downloadUrl = $data['url'];
            $fileContent = file_get_contents($downloadUrl);

            Storage::put('view.docx', $fileContent);
        }

        return response()->json(['error' => 0]);
    }
}
