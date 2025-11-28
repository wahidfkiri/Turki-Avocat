<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIntervenantRequest extends FormRequest
{

    public function rules()
    {
        return [
            'identite_fr' => 'required|string|max:255',
            'identite_ar' => 'nullable|string|max:255',
            'type' => 'required|in:personne physique,personne morale,entreprise individuelle',
            'numero_cni' => 'nullable|string|max:50',
            'rne' => 'nullable|string|max:50',
            'numero_cnss' => 'nullable|string|max:50',
            'forme_sociale_id' => 'nullable|exists:forme_sociales,id',
            'categorie' => 'required|in:contact,client,avocat,notaire,huissier,juridiction,administrateur_judiciaire,mandataire_judiciaire,adversaire,expert_judiciaire,traducteur',
            'fonction' => 'nullable|string|max:255',
            'adresse1' => 'nullable|string|max:255',
            'adresse2' => 'nullable|string|max:255',
            'portable1' => 'nullable|string|max:30',
            'portable2' => 'nullable|string|max:30',
            'mail1' => 'nullable|email|max:255',
            'mail2' => 'nullable|email|max:255',
            'site_internet' => 'nullable|url|max:255',
            'fixe1' => 'nullable|string|max:30',
            'fixe2' => 'nullable|string|max:30',
            'fax' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
            'archive' => 'boolean',
            'intervenants_lies' => 'sometimes|array',
            'intervenants_lies.*' => 'exists:intervenants,id',
            'relations.*' => 'sometimes|string|max:50',
            'piece_jointe.*' => 'nullable|file', // 10MB
        ];
    }

    public function messages()
    {
        return [
            'identite_fr.required' => 'L\'identité en français est obligatoire.',
            'type.required' => 'Le type d\'intervenant est obligatoire.',
            'categorie.required' => 'La catégorie est obligatoire.',
            'mail1.email' => 'L\'email principal doit être une adresse email valide.',
            'mail2.email' => 'L\'email secondaire doit être une adresse email valide.',
            'site_internet.url' => 'Le site internet doit être une URL valide.',
            'piece_jointe.*.file' => 'Chaque pièce jointe doit être un fichier valide.',
            'piece_jointe.*.mimes' => 'Les types de fichiers autorisés sont: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX, TXT, ZIP, RAR.',
            'piece_jointe.*.max' => 'Chaque fichier ne doit pas dépasser 10MB.',
        ];
    }
}