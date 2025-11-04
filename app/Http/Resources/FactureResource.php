<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FactureResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'dossier' => new DossierResource($this->whenLoaded('dossier')),
            'client' => new IntervenantResource($this->whenLoaded('client')),
            'type_piece' => $this->type_piece,
            'numero' => $this->numero,
            'date_emission' => $this->date_emission,
            'montant_ht' => (float) $this->montant_ht,
            'montant_tva' => (float) $this->montant_tva,
            'montant' => (float) $this->montant,
            'statut' => $this->statut,
            'commentaires' => $this->commentaires,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}