<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDomaineRequest extends FormRequest
{

    public function rules()
    {
        $domaineId = $this->route('domaine');

        return [
            'nom' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('domaines')->ignore($domaineId)
            ]
        ];
    }
}