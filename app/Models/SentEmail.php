<?php
// app/Models/SentEmail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    protected $fillable = [
        'from_email',
        'from_name', 
        'to_email',
        'subject',
        'content',
        'cc',
        'bcc',
        'sent_at'
    ];

    protected $casts = [
        'cc' => 'array',
        'bcc' => 'array',
        'sent_at' => 'datetime'
    ];
}