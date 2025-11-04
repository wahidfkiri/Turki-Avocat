<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormeSociale extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    public function intervenants()
    {
        return $this->hasMany(Intervenant::class);
    }
}