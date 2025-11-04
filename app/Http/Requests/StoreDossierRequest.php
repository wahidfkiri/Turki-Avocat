<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDossierRequest extends FormRequest
{
    public function rules()
    {
        return [
            'numero_dossier' => 'required|string|max:20|unique:dossiers,numero_dossier',
            'nom_dossier' => 'required|string|max:255',
            'objet' => 'nullable|string',
            'date_entree' => 'required|date',
            'domaine_id' => 'nullable|exists:domaines,id',
            'sous_domaine_id' => 'nullable|exists:sous_domaines,id',
            'conseil' => 'boolean',
            'contentieux' => 'boolean',
            'numero_role' => 'nullable|string|max:50',
            'chambre' => 'nullable|in:civil,commercial,social,pénal',
            'numero_chambre' => 'nullable|string|max:50',
            'numero_parquet' => 'nullable|string|max:50',
            'numero_instruction' => 'nullable|string|max:50',
            'numero_plainte' => 'nullable|string|max:50',
            'archive' => 'boolean',
            'note' => 'nullable|string',
            'date_archive' => 'nullable|date',
            // 'boite_archive' => 'nullable|string|max:100|required_if:archive,true',
            
            // Pour les relations
            'users' => 'sometimes|array',
            // 'users.*.user_id' => 'required|exists:users,id',
            // 'users.*.role' => 'required|in:avocat,clerc,secrétaire,stagiaire',
            // 'users.*.ordre' => 'required|integer',
            
            'intervenants' => 'sometimes|array',
            'intervenants.*.intervenant_id' => 'required|exists:intervenants,id',
            'intervenants.*.role' => 'required|in:client,avocat,avocat_secondaire,adversaire,huissier,notaire,expert,juridiction,administrateur_judiciaire,mandataire_judiciaire,autre'
        ];
    }

    public function messages()
    {
        return [
            'numero_dossier.required' => 'Le numéro de dossier est obligatoire.',
            'numero_dossier.unique' => 'Ce numéro de dossier existe déjà.',
            'nom_dossier.required' => 'Le nom du dossier est obligatoire.',
            'date_entree.required' => 'La date d\'entrée est obligatoire.',
            'date_archive.required_if' => 'La date d\'archivage est obligatoire lorsque le dossier est archivé.',
            'boite_archive.required_if' => 'La boîte d\'archivage est obligatoire lorsque le dossier est archivé.',
        ];
    }

    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         if ($this->archive && !$this->date_archive) {
    //             $validator->errors()->add('date_archive', 'La date d\'archivage est requise pour archiver un dossier.');
    //         }
    //     });
    // }
}