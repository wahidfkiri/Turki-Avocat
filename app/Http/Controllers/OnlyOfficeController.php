<?php
// app/Http/Controllers/OnlyOfficeController.php

namespace App\Http\Controllers;

use App\Services\OnlyOfficeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlyOfficeController extends Controller
{
    private $onlyOfficeService;

    public function __construct(OnlyOfficeService $onlyOfficeService)
    {
        $this->onlyOfficeService = $onlyOfficeService;
    }

    public function editor(Request $request)
    {
        $user = Auth::user() ?? (object)['id' => 1, 'name' => 'Test User'];
        $filePath = storage_path('app/public/example.docx');
        
        $config = $this->onlyOfficeService->createEditorConfig($filePath, $user);
        
        return view('onlyoffice.editor', compact('config'));
    }

    public function fileList()
    {
        $files = [];
        $storagePath = storage_path('app/public');
        
        if (is_dir($storagePath)) {
            $fileList = scandir($storagePath);
            foreach ($fileList as $file) {
                if ($file !== '.' && $file !== '..') {
                    $fullPath = $storagePath . '/' . $file;
                    if (is_file($fullPath)) {
                        $files[] = [
                            'name' => $file,
                            'path' => $fullPath,
                            'size' => filesize($fullPath),
                            'modified' => filemtime($fullPath),
                        ];
                    }
                }
            }
        }
        
        return view('onlyoffice.file-list', compact('files'));
    }
}