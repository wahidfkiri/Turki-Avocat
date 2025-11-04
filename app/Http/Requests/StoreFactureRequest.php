<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFactureRequest extends FormRequest
{

    public function rules()
    {
        return [
            'dossier_id' => 'nullable|exists:dossiers,id',
            'client_id' => 'nullable|exists:intervenants,id',
            'type_piece' => 'required|in:facture,note_frais,note_provision,avoir',
            'numero' => 'required|string|max:100|unique:factures,numero',
            'date_emission' => 'required|date',
            'montant_ht' => 'required|numeric|min:0',
            'montant_tva' => 'required|numeric|min:0',
            'montant' => 'required|numeric|min:0',
            'statut' => 'required|in:payé,non_payé',
            'commentaires' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'dossier_id.required' => 'Le dossier est obligatoire.',
            'client_id.required' => 'Le client est obligatoire.',
            'numero.required' => 'Le numéro de facture est obligatoire.',
            'numero.unique' => 'Ce numéro de facture existe déjà.',
            'date_emission.required' => 'La date d\'émission est obligatoire.',
            'montant_ht.required' => 'Le montant HT est obligatoire.',
            'montant_tva.required' => 'Le montant TVA est obligatoire.',
            'montant.required' => 'Le montant total est obligatoire.',
            'statut.required' => 'Le statut est obligatoire.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $montantHt = $this->montant_ht;
            $montantTva = $this->montant_tva;
            $montantTotal = $this->montant;

            if ($montantHt + $montantTva != $montantTotal) {
                $validator->errors()->add('montant', 'Le montant total doit être égal au montant HT plus la TVA.');
            }
        });
    }
}