<?php
// app/Console/Commands/PeakMindEmailTest.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EmailManagerService;

class PeakMindEmailTest extends Command
{
    protected $signature = 'peakmind:email-test 
                            {--reconnect : Forcer la reconnexion}
                            {--send : Tester l\'envoi}
                            {--folder=INBOX : Dossier spécifique}';
    
    protected $description = 'Test email pour PeakMind Solutions';

    public function handle(EmailManagerService $emailService)
    {
        $this->info("🧪 TEST EMAIL PEAKMIND SOLUTIONS");
        $this->info("Compte: wahid.fkiri@peakmind-solutions.com");
        
        if ($this->option('reconnect')) {
            $this->info("Forcer la reconnexion...");
            $result = $emailService->reconnect();
            $this->info($result['message']);
        }
        
        $this->testConnection($emailService);
        $this->testFolders($emailService);
        $this->testFolderEmails($emailService, $this->option('folder'));
        
        if ($this->option('send')) {
            $this->testSend($emailService);
        }
    }
    
    protected function testConnection($emailService)
    {
        $this->info("\n🔗 Test de connexion PeakMind...");
        
        $status = $emailService->testConnection();
        
        if ($status['connected']) {
            $this->info("✅ Connexion IMAP PeakMind réussie");
            $this->info("   Compte: {$status['account']}");
            $this->info("   Dossiers: {$status['folders_count']}");
            $this->info("   Statut: {$status['status']}");
        } else {
            $this->error("❌ Connexion IMAP PeakMind échouée");
            $this->error("   Erreur: {$status['error']}");
            
            // Afficher la configuration
            $this->warn("Configuration PeakMind:");
            $this->warn("   Host: " . env('IMAP_HOST'));
            $this->warn("   Port: " . env('IMAP_PORT'));
            $this->warn("   Username: " . env('IMAP_USERNAME'));
        }
    }
    
    protected function testFolders($emailService)
    {
        $this->info("\n📁 Dossiers PeakMind disponibles:");
        
        try {
            $folders = $emailService->getFolders();
            
            $this->info("✅ " . count($folders) . " dossiers trouvés");
            
            foreach ($folders as $folder) {
                $count = isset($folder['message_count']) ? $folder['message_count'] : 'N/A';
                $this->line(" - {$folder['name']} ({$count} messages)");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur dossiers: {$e->getMessage()}");
        }
    }
    
    protected function testFolderEmails($emailService, $folderName)
    {
        $this->info("\n📥 Emails dans {$folderName}:");
        
        $result = $emailService->getBasicEmails($folderName, 5);
        
        if ($result['success']) {
            $this->info("✅ " . count($result['emails']) . " emails récupérés");
            
            foreach ($result['emails'] as $email) {
                $seen = $email['seen'] ? '✓' : '✗';
                $from = $email['from_name'] ? "{$email['from_name']} <{$email['from']}>" : $email['from'];
                $this->line(" {$seen} [{$email['date']}] {$from}");
                $this->line("   📝 {$email['subject']}");
                $this->line("   ---");
            }
        } else {
            $this->error("❌ Erreur: {$result['error']}");
        }
    }
    
    protected function testSend($emailService)
    {
        $this->info("\n📤 Test envoi d'email depuis PeakMind...");
        
        $result = $emailService->sendEmail(
            'wahid.fkiri@peakmind-solutions.com', // S'envoyer un test
            'Test PeakMind System - ' . now()->format('Y-m-d H:i'),
            '<h3>Test du système email PeakMind</h3>
            <p>Ceci est un email de test envoyé via le système Laravel.</p>
            <p><strong>Compte utilisé:</strong> wahid.fkiri@peakmind-solutions.com</p>
            <p><strong>Date:</strong> ' . now()->format('d/m/Y H:i:s') . '</p>'
        );
        
        if ($result['success']) {
            $this->info("✅ Email envoyé avec succès");
            $this->info("   De: {$result['from']}");
            $this->info("   Vers: {$result['to']}");
            $this->info("   Message: {$result['message']}");
        } else {
            $this->error("❌ Erreur envoi: {$result['error']}");
        }
    }
}