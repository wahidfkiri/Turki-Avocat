<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserSecurityRequest extends FormRequest
{

    public function rules()
    {
        return [
            'password' => 'nullable|min:8|confirmed',
            'password_confirmation' => 'required_with:password',
        ];
    }

    public function messages()
    {
        return [
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password_confirmation.required_with' => 'La confirmation du mot de passe est requise.',
        ];
    }
}