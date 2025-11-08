<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OnlyOfficeController extends Controller
{
    public function open()
    {
        $filePath = storage_path('app/view.docx');
        $fileUrl = asset('storage/view.docx'); // public URL si storage:link

        $user = [
            "id" => auth()->id() ?? 1,
            "name" => auth()->user()->name ?? "Invité",
        ];

        $config = [
            "document" => [
                "fileType" => "docx",
                "key" => md5($filePath . filemtime($filePath)),
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
            "token" => $this->generateToken($fileUrl, $user),
        ];

        return view('onlyoffice.editor', compact('config'));
    }

    private function generateToken($fileUrl, $user)
    {
        $secret = 'secret123';
        return \Firebase\JWT\JWT::encode([
            'url' => $fileUrl,
            'user' => $user,
            'iat' => time()
        ], $secret, 'HS256');
    }

    public function callback(Request $request)
    {
        // Here OnlyOffice will send file updates
        $data = $request->all();
        // handle save, status, etc.
        return response()->json(['error' => 0]);
    }
}
