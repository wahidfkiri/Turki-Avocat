<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgendaRequest extends FormRequest
{

    public function rules()
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'heure_debut' => 'nullable|date_format:H:i',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'heure_fin' => 'nullable|date_format:H:i',
            'all_day' => 'boolean',
            'dossier_id' => 'nullable|exists:dossiers,id',
            'intervenant_id' => 'nullable|exists:intervenants,id',
            'utilisateur_id' => 'required|exists:users,id',
            'categorie' => 'required|in:rdv,audience,delai,tache,autre',
            'couleur' => 'nullable|string|max:20'
        ];
    }

    public function messages()
    {
        return [
            'titre.required' => 'Le titre est obligatoire.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'utilisateur_id.required' => 'L\'utilisateur est obligatoire.',
            'categorie.required' => 'La catégorie est obligatoire.',
        ];
    }
}