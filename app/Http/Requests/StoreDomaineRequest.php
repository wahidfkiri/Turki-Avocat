<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDomaineRequest extends FormRequest
{

    public function rules()
    {
        return [
            'nom' => 'required|string|max:100|unique:domaines,nom'
        ];
    }

    public function messages()
    {
        return [
            'nom.required' => 'Le nom du domaine est obligatoire.',
            'nom.unique' => 'Ce domaine existe déjà.',
        ];
    }
}