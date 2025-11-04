<?php
// app/Models/EmailDossier.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailDossier extends Model
{
    use HasFactory;

    protected $table = 'email_dossier';

    protected $fillable = [
        'user_id',
        'dossier_id',
        'email_uid',
        'folder_name',
        'subject',
        'from',
        'email_date'
    ];

    protected $casts = [
        'email_date' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le dossier
     */
    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }

    /**
     * Scope pour les emails d'un utilisateur spécifique
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour les emails de l'utilisateur connecté
     */
    public function scopeForCurrentUser($query)
    {
        return $query->where('user_id', auth()->id());
    }
}