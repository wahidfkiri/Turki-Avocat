<?php
// app/Models/AgendaCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgendaCategory extends Model
{
    use HasFactory;


    protected $fillable = [
        'nom',
        'couleur',
        'description',
        'actif',
        'ordre'
    ];

    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer'
    ];

    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'categorie_id');
    }

    public function scopeActive($query)
    {
        return $query->where('actif', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre')->orderBy('nom');
    }
}