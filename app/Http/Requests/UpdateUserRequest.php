<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{

    public function rules()
    {
        $userId = $this->route('user');

        return [
            'name' => 'sometimes|string|max:150',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($userId)
            ],
            'password' => 'sometimes|string|min:8|confirmed',
            'fonction' => 'sometimes|in:admin,avocat,secrétaire,clerc,stagiaire',
            'is_active' => 'sometimes|boolean',
            'can_facture' => 'sometimes|boolean',
            'roles' => 'sometimes||exists:roles,name',
            'is_active' => 'boolean',
        ];
    }
}