<?php
// app/Http/Controllers/EmailSimpleController.php

namespace App\Http\Controllers;

use App\Services\EmailManagerService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
    protected $emailService;
    
    public function __construct(EmailManagerService $emailService)
    {
        $this->emailService = $emailService;
    }
    
    public function testConnection(): JsonResponse
    {
        $status = $this->emailService->testConnection();
        return response()->json($status);
    }
    
    public function getFolders(): JsonResponse
    {
        try {
            $folders = $this->emailService->getFolders();
            return response()->json([
                'success' => true,
                'folders' => $folders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getBasicEmails(Request $request, $folderName = 'INBOX'): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $result = $this->emailService->getBasicEmails($folderName, $limit);
        return response()->json($result);
    }
    
    /**
     * NOUVELLE METHODE : Emails très basiques (sans problème de date)
     */
    public function getVeryBasicEmails(Request $request, $folderName = 'INBOX'): JsonResponse
    {
        $limit = $request->get('limit', 5);
        $result = $this->emailService->getVeryBasicEmails($folderName, $limit);
        return response()->json($result);
    }
    
    public function sendEmail(Request $request): JsonResponse
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string',
            'content' => 'required|string'
        ]);
        
        $result = $this->emailService->sendEmail(
            $request->to,
            $request->subject,
            $request->content,
            $request->only(['cc', 'bcc'])
        );
        
        return response()->json($result);
    }
    
    public function reconnect(): JsonResponse
    {
        $result = $this->emailService->reconnect();
        return response()->json($result);
    }
}