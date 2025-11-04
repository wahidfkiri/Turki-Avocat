<?php
// app/Mail/EaboutifyMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EaboutifyMail extends Mailable implements ShouldQueue
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
        $email = $this->subject($this->subject)
                     ->view('emails.template')
                     ->with(['content' => $this->content]);
        
        // Pièces jointes
        if (!empty($this->options['attachments'])) {
            foreach ($this->options['attachments'] as $attachment) {
                if (isset($attachment['path'])) {
                    $email->attach($attachment['path'], [
                        'as' => $attachment['name'] ?? basename($attachment['path']),
                        'mime' => $attachment['mime'] ?? 'application/octet-stream',
                    ]);
                } elseif (isset($attachment['data'])) {
                    $email->attachData(
                        $attachment['data'],
                        $attachment['name'],
                        ['mime' => $attachment['mime'] ?? 'application/octet-stream']
                    );
                }
            }
        }
        
        // Reply To
        if (!empty($this->options['reply_to'])) {
            $email->replyTo($this->options['reply_to']);
        }
        
        // CC
        if (!empty($this->options['cc'])) {
            $email->cc($this->options['cc']);
        }
        
        // BCC
        if (!empty($this->options['bcc'])) {
            $email->bcc($this->options['bcc']);
        }
        
        return $email;
    }
}