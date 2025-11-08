<?php
// app/Services/OnlyOfficeService.php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class OnlyOfficeService
{
    private $documentServerUrl;
    private $appUrl;

    public function __construct()
    {
        $this->documentServerUrl = 'http://localhost:8080';
        $this->appUrl = 'http://host.docker.internal:8000'; // Docker container can reach host
    }

    public function createEditorConfig($filePath, $user, $mode = 'edit'): array
    {
        // Ensure file exists and is accessible
        $fullPath = $this->ensureFileExists($filePath);
        $fileName = basename($fullPath);

        $config = [
            'document' => [
                'title' => $fileName,
                'url' => $this->appUrl . '/api/onlyoffice/download?path=' . urlencode($fullPath),
                'fileType' => pathinfo($fileName, PATHINFO_EXTENSION),
                'key' => 'doc_' . md5($fullPath) . '_' . time(),
                'permissions' => [
                    'edit' => true,
                    'download' => true,
                    'print' => true,
                    'review' => true,
                ],
            ],
            'documentType' => $this->getDocumentType($fileName),
            'editorConfig' => [
                'mode' => $mode,
                'lang' => 'en',
                'callbackUrl' => $this->appUrl . '/api/onlyoffice/callback',
                'user' => [
                    'id' => (string) ($user->id ?? 1),
                    'name' => $user->name ?? 'Test User',
                    'group' => 'user',
                ],
                'customization' => [
                    'autosave' => true,
                    'forcesave' => true,
                    'comments' => true,
                    'compactHeader' => false,
                    'compactToolbar' => false,
                    'feedback' => [
                        'visible' => false,
                    ],
                    'goback' => [
                        'text' => 'Back to List',
                        'url' => $this->appUrl . '/onlyoffice/files',
                    ],
                ],
            ],
            'type' => 'desktop',
            'width' => '100%',
            'height' => '800px',
        ];

        return $config;
    }

    private function ensureFileExists($filePath): string
    {
        $fullPath = storage_path('app/public/' . basename($filePath));
        
        if (!file_exists($fullPath)) {
            $content = "This is a test document.\n\nCreated at: " . now()->format('Y-m-d H:i:s');
            file_put_contents($fullPath, $content);
            chmod($fullPath, 0644);
        }
        
        return $fullPath;
    }

    private function getDocumentType($fileName): string
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $wordTypes = ['doc', 'docx', 'odt', 'rtf', 'txt'];
        $cellTypes = ['xls', 'xlsx', 'ods', 'csv'];
        $slideTypes = ['ppt', 'pptx', 'odp'];
        
        if (in_array($extension, $wordTypes)) return 'word';
        if (in_array($extension, $cellTypes)) return 'cell';
        if (in_array($extension, $slideTypes)) return 'slide';
        
        return 'word'; // default
    }

    public function saveDocumentContent($filePath, $content): bool
    {
        try {
            file_put_contents($filePath, $content);
            \Log::info('Document saved successfully', ['file' => $filePath]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to save document: ' . $e->getMessage());
            return false;
        }
    }
}