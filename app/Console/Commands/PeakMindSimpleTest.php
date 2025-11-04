<?php
// app/Console/Commands/PeakMindSimpleTest.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EmailManagerService;

class PeakMindSimpleTest extends Command
{
    protected $signature = 'peakmind:simple-test 
                            {--folder=INBOX : Dossier à tester}
                            {--very-basic : Utiliser la méthode très basique}';
    
    protected $description = 'Test ultra-simple email PeakMind';

    public function handle(EmailManagerService $emailService)
    {
        $this->info("🧪 TEST ULTRA-SIMPLE PEAKMIND");
        $this->info("Compte: wahid.fkiri@peakmind-solutions.com");
        
        $this->testConnection($emailService);
        
        if ($this->option('very-basic')) {
            $this->testVeryBasicEmails($emailService, $this->option('folder'));
        } else {
            $this->testBasicEmails($emailService, $this->option('folder'));
        }
    }
    
    protected function testConnection($emailService)
    {
        $this->info("\n🔗 Test connexion...");
        
        $status = $emailService->testConnection();
        
        if ($status['connected']) {
            $this->info("✅ Connexion réussie");
            $this->info("   Dossiers disponibles: {$status['folders_count']}");
        } else {
            $this->error("❌ Erreur: {$status['error']}");
            return false;
        }
        
        return true;
    }
    
    protected function testBasicEmails($emailService, $folderName)
    {
        $this->info("\n📥 Emails basiques de {$folderName}:");
        
        $result = $emailService->getBasicEmails($folderName, 3);
        
        if ($result['success']) {
            $this->info("✅ {$result['count']} emails trouvés");
            
            foreach ($result['emails'] as $email) {
                $seen = $email['seen'] ? '✓' : '✗';
                $this->line(" {$seen} {$email['from']}");
                $this->line("   📝 {$email['subject']}");
                $this->line("   📅 {$email['date']}");
                $this->line("   ---");
            }
        } else {
            $this->error("❌ Erreur: {$result['error']}");
        }
    }
    
    protected function testVeryBasicEmails($emailService, $folderName)
    {
        $this->info("\n📥 Emails TRÈS basiques de {$folderName}:");
        
        $result = $emailService->getVeryBasicEmails($folderName, 3);
        
        if ($result['success']) {
            $this->info("✅ {$result['count']} emails trouvés");
            
            foreach ($result['emails'] as $email) {
                $seen = $email['seen'] ? '✓ Lu' : '✗ Non lu';
                $this->line(" {$seen} - De: {$email['from']}");
                $this->line("   Sujet: {$email['subject']}");
                $this->line("   ---");
            }
        } else {
            $this->error("❌ Erreur: {$result['error']}");
        }
    }
}