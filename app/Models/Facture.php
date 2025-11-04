<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'dossier_id',
        'client_id',
        'type_piece',
        'numero',
        'date_emission',
        'montant_ht',
        'montant_tva',
        'montant',
        'statut',
        'commentaires',
        'piece_jointe',
        'file_name',
    ];

    protected $casts = [
        'date_emission' => 'date',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant' => 'decimal:2',
    ];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }

    public function client()
    {
        return $this->belongsTo(Intervenant::class, 'client_id');
    }

    public function getPieceJointeUrlAttribute()
    {
        return $this->piece_jointe ? asset('storage/factures/' . $this->piece_jointe) : null;
    }

    /**
     * Get the file extension
     */
    public function getPieceJointeExtensionAttribute()
    {
        return $this->piece_jointe ? pathinfo($this->piece_jointe, PATHINFO_EXTENSION) : null;
    }
}