<?php
// app/Console/Commands/EmailTestSafe.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EmailManagerService;

class EmailTestSafe extends Command
{
    protected $signature = 'email:test-safe 
                            {--folders : Lister les dossiers}
                            {--inbox : Tester l\'INBOX}
                            {--folder= : Tester un dossier spécifique}';
    
    protected $description = 'Test sécurisé de la configuration email';

    public function handle(EmailManagerService $emailService)
    {
        $this->info("🧪 Test SÉCURISÉ de la configuration email Eaboutify...");
        
        // Test connexion détaillé
        $this->testConnection($emailService);
        
        if ($this->option('folders')) {
            $this->testFolders($emailService);
        }
        
        if ($this->option('inbox')) {
            $this->testInbox($emailService);
        }
        
        if ($this->option('folder')) {
            $this->testSpecificFolder($emailService, $this->option('folder'));
        }
        
        // Si aucune option, faire un test complet mais sécurisé
        if (!$this->option('folders') && !$this->option('inbox') && !$this->option('folder')) {
            $this->testFolders($emailService);
            $this->testInbox($emailService);
        }
    }
    
    protected function testConnection($emailService)
    {
        $this->info("\n🔗 Test de connexion...");
        
        $status = $emailService->testConnection();
        
        if ($status['connected']) {
            $this->info("✅ Connexion IMAP réussie");
            $this->info("   Compte: {$status['account']}");
            $this->info("   Dossiers: {$status['folders_count']}");
            $this->info("   Emails INBOX: {$status['inbox_count']}");
        } else {
            $this->error("❌ Connexion IMAP échouée");
            $this->error("   Erreur: {$status['error']}");
        }
    }
    
    protected function testFolders($emailService)
    {
        $this->info("\n📁 Test des dossiers...");
        
        try {
            $folders = $emailService->getFolders();
            
            $this->info("✅ Dossiers trouvés: " . count($folders));
            
            $tableData = [];
            foreach ($folders as $folder) {
                $tableData[] = [
                    $folder['name'],
                    $folder['unread_count'],
                    $folder['total_count'],
                    $folder['path']
                ];
            }
            
            $this->table(
                ['Nom', 'Non lus', 'Total', 'Chemin'],
                $tableData
            );
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur dossiers: {$e->getMessage()}");
        }
    }
    
    protected function testInbox($emailService)
    {
        $this->info("\n📥 Test de l'INBOX (méthode simple)...");
        
        try {
            $result = $emailService->getSimpleFolderEmails('INBOX', 5);
            
            if ($result['success']) {
                $this->info("✅ Emails récupérés: " . count($result['emails']));
                
                $tableData = [];
                foreach ($result['emails'] as $email) {
                    $tableData[] = [
                        $email['uid'],
                        $email['from'],
                        $email['subject'],
                        $email['date'],
                        $email['seen'] ? 'Oui' : 'Non'
                    ];
                }
                
                $this->table(
                    ['UID', 'De', 'Sujet', 'Date', 'Lu'],
                    $tableData
                );
            } else {
                $this->error("❌ Erreur INBOX: {$result['error']}");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur INBOX: {$e->getMessage()}");
        }
    }
    
    protected function testSpecificFolder($emailService, $folderName)
    {
        $this->info("\n📂 Test du dossier: {$folderName}");
        
        try {
            $result = $emailService->getSimpleFolderEmails($folderName, 5);
            
            if ($result['success']) {
                $this->info("✅ Emails récupérés: " . count($result['emails']));
                
                if (count($result['emails']) > 0) {
                    $tableData = [];
                    foreach ($result['emails'] as $email) {
                        $tableData[] = [
                            $email['uid'],
                            $email['from'],
                            $email['subject'],
                            $email['date']
                        ];
                    }
                    
                    $this->table(
                        ['UID', 'De', 'Sujet', 'Date'],
                        $tableData
                    );
                } else {
                    $this->info("ℹ️  Aucun email dans ce dossier");
                }
            } else {
                $this->error("❌ Erreur dossier: {$result['error']}");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur dossier: {$e->getMessage()}");
        }
    }
}