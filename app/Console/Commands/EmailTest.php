<?php
// app/Console/Commands/EmailTest.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EmailManagerService;

class EmailTest extends Command
{
    protected $signature = 'email:test 
                            {--send : Tester l\'envoi d\'email}
                            {--receive : Tester la réception}';
    
    protected $description = 'Tester la configuration email';

    public function handle(EmailManagerService $emailService)
    {
        $this->info("🧪 Test de la configuration email Eaboutify...");
        
        // Test connexion
        $status = $emailService->checkConnection();
        $this->info("Connexion: " . ($status['connected'] ? '✅ OK' : '❌ Échec'));
        $this->info("Compte: {$status['account']}");
        
        if ($this->option('send')) {
            $this->testSendEmail($emailService);
        }
        
        if ($this->option('receive')) {
            $this->testReceiveEmail($emailService);
        }
        
        if (!$this->option('send') && !$this->option('receive')) {
            $this->testSendEmail($emailService);
            $this->testReceiveEmail($emailService);
        }
    }
    
    protected function testSendEmail($emailService)
    {
        $this->info("\n📤 Test d'envoi d'email...");
        
        $result = $emailService->sendEmail(
            'contact@peakmind-solutions.com',
            'Test Email - PeakMind',
            'Ceci est un email de test depuis le système PeakMind.'
        );
        
        if ($result['success']) {
            $this->info("✅ Email envoyé avec succès");
        } else {
            $this->error("❌ Erreur envoi: {$result['error']}");
        }
    }
    
    protected function testReceiveEmail($emailService)
    {
        $this->info("\n📥 Test de réception d'emails...");
        
        try {
            $folders = $emailService->getFolders();
            $this->info("✅ Dossiers trouvés: " . count($folders));
            
            foreach ($folders as $folder) {
                $this->info(" - {$folder['name']} ({$folder['unread_count']} non lus)");
            }
            
            // Récupérer 3 emails de l'INBOX
            $result = $emailService->getEmailsFromFolder('INBOX', 3);
            
            if ($result['success']) {
                $this->info("✅ Emails récupérés: " . count($result['emails']));
            } else {
                $this->error("❌ Erreur réception: {$result['error']}");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur: {$e->getMessage()}");
        }
    }
}