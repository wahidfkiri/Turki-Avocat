<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UpdateUserPrivilegesRequest extends FormRequest
{

    public function rules()
    {
        return [
            'roles' => 'required',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,name',
        ];
    }

    public function messages()
    {
        return [
            'roles.required' => 'Veuillez séléctionner un rôle.',
            'roles.*.exists' => 'Un des rôles sélectionnés n\'existe pas.',
            'permissions.*.exists' => 'Une des permissions sélectionnées n\'existe pas.',
        ];
    }
}