<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domaine extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    public function sousDomaines()
    {
        return $this->hasMany(SousDomaine::class);
    }

    public function dossiers()
    {
        return $this->hasMany(Dossier::class);
    }
}