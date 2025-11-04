<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgendaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'date_debut' => $this->date_debut,
            'heure_debut' => $this->heure_debut,
            'date_fin' => $this->date_fin,
            'heure_fin' => $this->heure_fin,
            'all_day' => $this->all_day,
            'dossier' => new DossierResource($this->whenLoaded('dossier')),
            'intervenant' => new IntervenantResource($this->whenLoaded('intervenant')),
            'utilisateur' => new UserResource($this->whenLoaded('user')),
            'categorie' => $this->categorie,
            'couleur' => $this->couleur,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}