<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDossierRequest extends FormRequest
{

    public function rules()
    {
        $dossierId = $this->route('dossier');

        return [
            'numero_dossier' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('dossiers')->ignore($dossierId)
            ],
            'nom_dossier' => 'sometimes|string|max:255',
            'note' => 'nullable|string',
            'objet' => 'nullable|string',
            'date_entree' => 'sometimes|date',
            'domaine_id' => 'nullable|exists:domaines,id',
            'sous_domaine_id' => 'nullable|exists:sous_domaines,id',
            'conseil' => 'sometimes|boolean',
            'contentieux' => 'sometimes|boolean',
            'numero_role' => 'nullable|string|max:50',
            'chambre' => 'nullable|in:civil,commercial,social,pénal',
            'numero_chambre' => 'nullable|string|max:50',
            'numero_parquet' => 'nullable|string|max:50',
            'numero_instruction' => 'nullable|string|max:50',
            'numero_plainte' => 'nullable|string|max:50',
            'archive' => 'sometimes|boolean',
            'date_archive' => 'nullable|date',
            // 'boite_archive' => 'nullable|string|max:100|required_if:archive,true'
        ];
    }
}