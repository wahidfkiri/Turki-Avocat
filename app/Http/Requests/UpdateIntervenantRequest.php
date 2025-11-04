<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIntervenantRequest extends FormRequest
{

    public function rules()
    {
        return [
            'identite_fr' => 'sometimes|string|max:255',
            'identite_ar' => 'nullable|string|max:255',
            'type' => 'sometimes|in:personne physique,personne morale,entreprise individuelle',
            'numero_cni' => 'nullable|string|max:50',
            'rne' => 'nullable|string|max:50',
            'numero_cnss' => 'nullable|string|max:50',
            'forme_sociale_id' => 'nullable|exists:forme_sociales,id',
            'categorie' => 'sometimes|in:contact,client,avocat,notaire,huissier,juridiction,administrateur_judiciaire,mandataire_judiciaire,adversaire,expert_judiciaire',
            'fonction' => 'nullable|string|max:255',
            'adresse1' => 'nullable|string|max:255',
            'adresse2' => 'nullable|string|max:255',
            'portable1' => 'nullable|string|max:30',
            'portable2' => 'nullable|string|max:30',
            'mail1' => 'nullable|email|max:255',
            'mail2' => 'nullable|email|max:255',
            'site_internet' => 'nullable|url|max:255',
            'fixe1' => 'nullable|string|max:30',
            'fixe2' => 'nullable|string|max:30',
            'fax' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
            'archive' => 'sometimes|boolean'
        ];
    }
}