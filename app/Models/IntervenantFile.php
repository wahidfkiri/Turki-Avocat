<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntervenantFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'intervenant_id',
        'file_path',
        'file_name',
        'description',
    ];

    public function intervenant()
    {
        return $this->belongsTo(Intervenant::class);
    }
 // Accessor pour l'URL publique du fichier
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    // Accessor pour le chemin de stockage
    public function getStoragePathAttribute()
    {
        return storage_path('app/public/' . $this->file_path);
    }
}
