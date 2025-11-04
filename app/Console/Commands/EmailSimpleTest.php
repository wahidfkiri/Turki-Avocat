<?php
// app/Console/Commands/EmailSimpleTest.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EmailManagerService;

class EmailSimpleTest extends Command
{
    protected $signature = 'email:simple-test 
                            {--reconnect : Forcer la reconnexion}
                            {--send : Tester l\'envoi}';
    
    protected $description = 'Test très simple de la configuration email';

    public function handle(EmailManagerService $emailService)
    {
        $this->info("🧪 TEST TRÈS SIMPLE - Configuration email Eaboutify");
        
        if ($this->option('reconnect')) {
            $this->info("Forcer la reconnexion...");
            $emailService->reconnect();
        }
        
        $this->testConnection($emailService);
        $this->testFolders($emailService);
        $this->testInbox($emailService);
        
        if ($this->option('send')) {
            $this->testSend($emailService);
        }
    }
    
    protected function testConnection($emailService)
    {
        $this->info("\n🔗 Test de connexion basique...");
        
        $status = $emailService->testConnection();
        
        if ($status['connected']) {
            $this->info("✅ Connexion IMAP réussie");
            $this->info("   Compte: {$status['account']}");
            $this->info("   Dossiers: {$status['folders_count']}");
        } else {
            $this->error("❌ Connexion IMAP échouée");
            $this->error("   Erreur: {$status['error']}");
            
            // Afficher la configuration utilisée
            $this->warn("Configuration utilisée:");
            $this->warn("   Host: " . env('IMAP_HOST'));
            $this->warn("   Port: " . env('IMAP_PORT'));
            $this->warn("   Username: " . env('IMAP_USERNAME'));
        }
    }
    
    protected function testFolders($emailService)
    {
        $this->info("\n📁 Liste des dossiers...");
        
        try {
            $folders = $emailService->getFolders();
            
            $this->info("✅ Dossiers trouvés: " . count($folders));
            
            foreach ($folders as $folder) {
                $status = $emailService->checkFolder($folder['name']);
                $count = $status['success'] ? $status['message_count'] : 'erreur';
                
                $this->line(" - {$folder['name']} ({$count} messages)");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur dossiers: {$e->getMessage()}");
        }
    }
    
    protected function testInbox($emailService)
    {
        $this->info("\n📥 Test INBOX basique...");
        
        $result = $emailService->getBasicEmails('INBOX', 3);
        
        if ($result['success']) {
            $this->info("✅ Emails récupérés: " . count($result['emails']));
            
            foreach ($result['emails'] as $email) {
                $seen = $email['seen'] ? '✓' : '✗';
                $this->line(" {$seen} [{$email['date']}] {$email['from']} - {$email['subject']}");
            }
        } else {
            $this->error("❌ Erreur INBOX: {$result['error']}");
        }
    }
    
    protected function testSend($emailService)
    {
        $this->info("\n📤 Test envoi d'email...");
        
        $result = $emailService->sendEmail(
            env('MAIL_FROM_ADDRESS'), // S'envoyer un email à soi-même
            'Test Eaboutify - ' . now()->format('Y-m-d H:i'),
            'Ceci est un email de test depuis le système Eaboutify.'
        );
        
        if ($result['success']) {
            $this->info("✅ Email envoyé avec succès");
        } else {
            $this->error("❌ Erreur envoi: {$result['error']}");
        }
    }
}