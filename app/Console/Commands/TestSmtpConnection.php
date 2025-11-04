<?php
// app/Console/Commands/TestSmtpConnection.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSmtpConnection extends Command
{
    protected $signature = 'test:smtp';
    protected $description = 'Test SMTP connection';

    public function handle()
    {
        try {
            Mail::raw('Test email from Laravel', function ($message) {
                $message->to('wahid.fkiri@peakmind-solutions.com')
                        ->subject('SMTP Test');
            });
            
            $this->info('SMTP connection successful!');
        } catch (\Exception $e) {
            $this->error('SMTP connection failed: ' . $e->getMessage());
        }
    }
}