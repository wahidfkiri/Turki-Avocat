<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'priorite' => $this->priorite,
            'statut' => $this->statut,
            'dossier' => new DossierResource($this->whenLoaded('dossier')),
            'intervenant' => new IntervenantResource($this->whenLoaded('intervenant')),
            'utilisateur' => new UserResource($this->whenLoaded('user')),
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}