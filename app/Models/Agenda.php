<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'heure_debut',
        'date_fin',
        'heure_fin',
        'all_day',
        'file_path',
        'file_name',
        'dossier_id',
        'intervenant_id',
        'utilisateur_id',
        'categorie',
        'couleur'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'all_day' => 'boolean',
    ];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }

    public function intervenant()
    {
        return $this->belongsTo(Intervenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    /**
     * Get the full file URL (if stored in storage)
     */
    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    /**
     * Check if task has a file
     */
    public function hasFile()
    {
        return !is_null($this->file_path) && !is_null($this->file_name);
    }

      /**
     * Scope for tasks with files
     */
    public function scopeWithFiles($query)
    {
        return $query->whereNotNull('file_path');
    }

    /**
     * Scope for tasks without files
     */
    public function scopeWithoutFiles($query)
    {
        return $query->whereNull('file_path');
    }
}