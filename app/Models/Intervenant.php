<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'identite_fr',
        'identite_ar',
        'type',
        'numero_cni',
        'rne',
        'numero_cnss',
        'forme_sociale_id',
        'categorie',
        'fonction',
        'adresse1',
        'adresse2',
        'portable1',
        'portable2',
        'mail1',
        'mail2',
        'site_internet',
        'fixe1',
        'fixe2',
        'fax',
        'notes',
        'piece_jointe',
        'archive'
    ];

    protected $casts = [
        'archive' => 'boolean',
    ];

    public function formeSociale()
    {
        return $this->belongsTo(FormeSociale::class);
    }

    public function dossiers()
    {
        return $this->belongsToMany(Dossier::class, 'dossier_intervenant')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function files()
    {
        return $this->hasMany(IntervenantFile::class, 'intervenant_id');
    }

    public function intervenantsLies()
    {
        return $this->belongsToMany(Intervenant::class, 'intervenant_intervenant', 
                    'intervenant_id', 'intervenant_lie_id')
                    ->withPivot('relation')
                    ->withTimestamps();
    }

    public function factures()
    {
        return $this->hasMany(Facture::class, 'client_id');
    }

    public function agendas()
    {
        return $this->hasMany(Agenda::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

public function intervenantsLiesInverse()
{
    return $this->belongsToMany(Intervenant::class, 'intervenant_intervenant', 
                'intervenant_lie_id', 'intervenant_id')
                ->withPivot('relation')
                ->withTimestamps();
}

public function getAllIntervenantsLiesAttribute()
{
    return $this->intervenantsLies->merge($this->intervenantsLiesInverse);
}
}