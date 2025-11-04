<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'mailer',
        'smtp_host',
        'smtp_port',
        'smtp_encryption',
        'smtp_username',
        'smtp_password',
        'imap_host',
        'imap_port',
        'imap_encryption',
        'imap_username',
        'imap_password',
        'user_id'
    ];

    protected $casts = [
        'smtp_port' => 'integer',
        'imap_port' => 'integer',
    ];

    protected $hidden = [
        'smtp_password',
        'imap_password'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    // Scope for global settings (not user-specific)
    public function scopeGlobal($query)
    {
        return $query->whereNull('user_id');
    }
}