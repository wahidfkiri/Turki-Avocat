<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichier extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_module',
        'module_id',
        'nom_fichier',
        'chemin_fichier',
        'type_mime',
        'taille',
        'description'
    ];

    protected $casts = [
        'taille' => 'integer',
        'date_upload' => 'datetime',
    ];
}