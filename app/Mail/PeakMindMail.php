<?php
// app/Mail/PeakMindMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PeakMindMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;
    public $options;

    public function __construct($subject, $content, $options = [])
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->options = $options;
    }

    public function build()
    {
        $email = $this->subject("[Turki Avocats] " . $this->subject)
                     ->view('emails.templates.welcome')
                     ->with(['content' => $this->content]);
        
        // Pièces jointes
        if (!empty($this->options['attachments'])) {
            foreach ($this->options['attachments'] as $attachment) {
                if (isset($attachment['path'])) {
                    $email->attach($attachment['path'], [
                        'as' => $attachment['name'] ?? basename($attachment['path']),
                        'mime' => $attachment['mime'] ?? 'application/octet-stream',
                    ]);
                }
            }
        }
        
        return $email;
    }
}