<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_dossier',
        'nom_dossier',
        'objet',
        'date_entree',
        'domaine_id',
        'sous_domaine_id',
        'conseil',
        'contentieux',
        'numero_role',
        'chambre',
        'numero_chambre',
        'numero_parquet',
        'numero_instruction',
        'numero_plainte',
        'archive',
        'note',
        'date_archive',
        'boite_archive'
    ];

    protected $casts = [
        'conseil' => 'boolean',
        'contentieux' => 'boolean',
        'archive' => 'boolean',
        'date_entree' => 'datetime',
        'date_archive' => 'date',
    ];

    public function domaine()
    {
        return $this->belongsTo(Domaine::class);
    }

    public function sousDomaine()
    {
        return $this->belongsTo(SousDomaine::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'dossier_user')
                    ->withPivot('ordre', 'role')
                    ->withTimestamps();
    }

    public function intervenants()
    {
        return $this->belongsToMany(Intervenant::class, 'dossier_intervenant')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function dossiersLies()
    {
        return $this->belongsToMany(Dossier::class, 'dossier_dossier', 
                    'dossier_id', 'dossier_lie_id')
                    ->withPivot('relation')
                    ->withTimestamps();
    }

    public function timeSheets()
    {
        return $this->hasMany(TimeSheet::class);
    }

    public function agendas()
    {
        return $this->hasMany(Agenda::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function fichiers()
    {
        return $this->hasMany(Fichier::class, 'module_id');
    }

     public function attachedEmails()
    {
        return $this->hasMany(EmailDossier::class);
    }

    public function userAttachedEmails()
    {
        return $this->hasMany(EmailDossier::class)
                    ->where('user_id', Auth::id());
    }


    /**
     * Scope pour les dossiers actifs
     */
    public function scopeActive($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Récupérer les emails attachés avec pagination
     */
    public function getEmailsPaginated($perPage = 15)
    {
        return $this->attachedEmails()
                    ->orderBy('email_date', 'desc')
                    ->paginate($perPage);
    }

    // Dans votre modèle Dossier.php, ajoutez ces méthodes
// (à ajouter à votre modèle existant)

/**
 * Scope pour rechercher des dossiers par numéro ou objet
 */
public function scopeSearch($query, $search)
{
    return $query->where(function($q) use ($search) {
        $q->where('numero_dossier', 'LIKE', "%{$search}%")
          ->orWhere('nom_dossier', 'LIKE', "%{$search}%")
          ->orWhere('objet', 'LIKE', "%{$search}%");
    });
}

/**
 * Récupérer tous les dossiers liés (dans les deux sens)
 */
public function getAllLinkedDossiers()
{
    $linkedTo = $this->dossiersLies;
    $linkedFrom = $this->dossiersLieDe; // Vous devez ajouter cette relation
    
    return $linkedTo->merge($linkedFrom);
}

/**
 * Vérifier si un dossier est lié à un autre
 */
public function isLinkedTo($dossierId)
{
    return $this->dossiersLies()
        ->where('dossier_lie_id', $dossierId)
        ->exists();
}

/**
 * Vérifier si un dossier est lié depuis un autre
 */
public function isLinkedFrom($dossierId)
{
    return DB::table('dossier_dossier')
        ->where('dossier_id', $dossierId)
        ->where('dossier_lie_id', $this->id)
        ->exists();
}

// Ajoutez cette relation inverse dans votre modèle Dossier
public function dossiersLieDe()
{
    return $this->belongsToMany(Dossier::class, 'dossier_dossier', 
                'dossier_lie_id', 'dossier_id')
                ->withPivot('relation')
                ->withTimestamps();
}
}