<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

// ONLYOFFICE API Routes - NO AUTHENTICATION
Route::get('users', function () {
    return User::all();
})->name('users.list');
Route::post('/onlyoffice/callback', function (Request $request) {
    Log::info('=== ONLYOFFICE CALLBACK START ===');
    Log::info('Callback data:', $request->all());
    
    $data = $request->all();
    $status = $data['status'] ?? null;
    $key = $data['key'] ?? null;
    
    Log::info('Callback status: ' . $status . ', key: ' . $key);
    
    // Handle different statuses
    switch ($status) {
        case 1: // Document ready
            Log::info('Document ready for editing');
            break;
        case 2: // Document editing
            Log::info('Document is being edited');
            break;
        case 3: // Save error
            Log::error('Document save error');
            break;
        case 4: // Closed without changes
            Log::info('Document closed without changes');
            break;
        case 6: // Force save while editing
        case 7: // Force save with error
            Log::info('Document force saved', ['status' => $status]);
            if (isset($data['url'])) {
                // Download and save the document
                $saved = $this->downloadAndSaveDocument($data['url'], $key);
                Log::info('Document save result: ' . ($saved ? 'SUCCESS' : 'FAILED'));
            }
            break;
        default:
            Log::info('Unknown status: ' . $status);
    }
    
    Log::info('=== ONLYOFFICE CALLBACK END ===');
    
    // ALWAYS return success to ONLYOFFICE
    return response()->json(['error' => 0]);
});

Route::get('/onlyoffice/download', function (Request $request) {
    $filePath = $request->get('path');
    
    Log::info('Download request', ['path' => $filePath]);
    
    if (!$filePath || !file_exists($filePath)) {
        Log::error('File not found: ' . $filePath);
        return response()->json(['error' => 'File not found'], 404);
    }
    
    $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
    $fileName = basename($filePath);
    
    Log::info('Serving file', [
        'file' => $fileName,
        'size' => filesize($filePath),
        'mime_type' => $mimeType
    ]);
    
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
    ]);
});

// Helper function to download and save document
function downloadAndSaveDocument($documentUrl, $key) {
    try {
        Log::info('Downloading document from: ' . $documentUrl);
        
        $client = new \GuzzleHttp\Client();
        $response = $client->get($documentUrl);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getBody();
            
            // Extract filename from key or use default
            $fileName = 'saved_document_' . time() . '.docx';
            $filePath = storage_path('app/public/' . $fileName);
            
            file_put_contents($filePath, $content);
            Log::info('Document saved: ' . $filePath);
            
            return true;
        }
    } catch (\Exception $e) {
        Log::error('Error downloading document: ' . $e->getMessage());
    }
    
    return false;
}