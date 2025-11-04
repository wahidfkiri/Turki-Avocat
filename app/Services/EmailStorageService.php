<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EmailStorageService
{
    protected $storageDisk = 'emails';

    public function saveEmail($folder, $emailData)
    {
        try {
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $emailId = $emailData['uid'] ?? uniqid();
            $filename = "{$folder}/{$timestamp}_{$emailId}.json";
            
            $emailToSave = [
                'metadata' => [
                    'saved_at' => Carbon::now()->toISOString(),
                    'folder' => $folder,
                    'original_uid' => $emailData['uid'],
                ],
                'email_data' => $emailData
            ];
            
            Storage::disk($this->storageDisk)->put($filename, json_encode($emailToSave, JSON_PRETTY_PRINT));
            return $filename;
            
        } catch (\Exception $e) {
            \Log::error("Erreur sauvegarde email: " . $e->getMessage());
            return false;
        }
    }

    public function getEmailsFromStorage($folder, $limit = 20)
    {
        try {
            $files = Storage::disk($this->storageDisk)->files($folder);
            
            // Trier par date (plus récent en premier)
            usort($files, function($a, $b) {
                return Storage::disk($this->storageDisk)->lastModified($b) - 
                       Storage::disk($this->storageDisk)->lastModified($a);
            });
            
            $emails = [];
            foreach (array_slice($files, 0, $limit) as $file) {
                try {
                    $content = Storage::disk($this->storageDisk)->get($file);
                    $emailData = json_decode($content, true);
                    if (isset($emailData['email_data'])) {
                        $emails[] = $emailData['email_data'];
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            return $emails;
            
        } catch (\Exception $e) {
            return [];
        }
    }

    
}