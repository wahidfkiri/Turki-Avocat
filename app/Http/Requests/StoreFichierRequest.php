<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFichierRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type_module' => 'required|in:intervenant,facture,agenda,tache,timesheet',
            'module_id' => 'required|integer',
            'fichier' => 'required|file|max:10240', // 10MB max
            'description' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'fichier.required' => 'Le fichier est obligatoire.',
            'fichier.max' => 'Le fichier ne doit pas dépasser 10MB.',
            'type_module.required' => 'Le type de module est obligatoire.',
            'module_id.required' => 'L\'ID du module est obligatoire.',
        ];
    }
}