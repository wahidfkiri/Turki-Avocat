<?php
// app/Console/Commands/EmailSync.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EmailManagerService;

class EmailSync extends Command
{
    protected $signature = 'email:sync 
                            {--folder=INBOX : Dossier à synchroniser}
                            {--limit=50 : Nombre d\'emails à récupérer}
                            {--unread : Seulement les emails non lus}';
    
    protected $description = 'Synchroniser les emails depuis le serveur IMAP';

    public function handle(EmailManagerService $emailService)
    {
        $this->info("Début de la synchronisation des emails...");
        
        $folder = $this->option('folder');
        $limit = $this->option('limit');
        $filters = [];
        
        if ($this->option('unread')) {
            $filters['unread'] = true;
        }
        
        try {
            $result = $emailService->getEmailsFromFolder($folder, $limit, 1, $filters);
            
            if ($result['success']) {
                $this->info("✅ Synchronisation réussie !");
                $this->info("Dossier: {$result['folder']}");
                $this->info("Emails récupérés: " . count($result['emails']));
                $this->info("Total emails: {$result['pagination']['total']}");
                
                // Afficher les derniers emails
                $this->table(
                    ['De', 'Sujet', 'Date'],
                    array_map(function($email) {
                        return [
                            $email['from']['email'],
                            $email['subject'],
                            $email['date']
                        ];
                    }, array_slice($result['emails'], 0, 5))
                );
            } else {
                $this->error("❌ Erreur: {$result['error']}");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur: {$e->getMessage()}");
        }
    }
}