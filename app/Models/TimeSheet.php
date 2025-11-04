<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_timesheet',
        'utilisateur_id',
        'dossier_id',
        'description',
        'categorie',
        'type',
        'quantite',
        'prix',
        'total',
        'file_path',
        'file_name',
    ];

    protected $casts = [
        'date_timesheet' => 'datetime',
        'quantite' => 'decimal:2',
        'prix' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }

    public function categorieRelation()
    {
        return $this->belongsTo(Categorie::class, 'categorie');
    }

    public function typeRelation()
    {
        return $this->belongsTo(Type::class, 'type');
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