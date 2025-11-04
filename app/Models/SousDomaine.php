<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousDomaine extends Model
{
    use HasFactory;

    protected $fillable = ['domaine_id', 'nom'];

    public function domaine()
    {
        return $this->belongsTo(Domaine::class);
    }

    public function dossiers()
    {
        return $this->hasMany(Dossier::class);
    }
}