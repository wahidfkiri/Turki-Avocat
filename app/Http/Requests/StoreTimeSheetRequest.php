<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimeSheetRequest extends FormRequest
{
    public function rules()
    {
        return [
            'date_timesheet' => 'required|date',
            'utilisateur_id' => 'required|exists:users,id',
            'dossier_id' => 'required|exists:dossiers,id',
            'description' => 'required|string',
            'categorie' => 'required|exists:categories,id',
            'type' => 'required|exists:types,id',
            'quantite' => 'required|numeric|min:0',
            'prix' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'date_timesheet.required' => 'La date est obligatoire.',
            'utilisateur_id.required' => 'L\'utilisateur est obligatoire.',
            'dossier_id.required' => 'Le dossier est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'categorie.required' => 'La catégorie est obligatoire.',
            'type.required' => 'Le type est obligatoire.',
            'quantite.required' => 'La quantité est obligatoire.',
            'prix.required' => 'Le prix est obligatoire.',
            'total.required' => 'Le total est obligatoire.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $quantite = $this->quantite;
            $prix = $this->prix;
            $total = $this->total;

            if ($quantite * $prix != $total) {
                $validator->errors()->add('total', 'Le total doit être égal à la quantité multipliée par le prix.');
            }
        });
    }
}