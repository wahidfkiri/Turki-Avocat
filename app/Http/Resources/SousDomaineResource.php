<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SousDomaineResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'domaine_id' => $this->domaine_id,
            'nom' => $this->nom,
            'domaine' => new DomaineResource($this->whenLoaded('domaine')),
            'dossiers' => DossierResource::collection($this->whenLoaded('dossiers')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}